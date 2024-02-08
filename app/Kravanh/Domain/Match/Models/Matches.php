<?php

namespace App\Kravanh\Domain\Match\Models;

use App\Kravanh\Domain\Environment\Models\Environment;
use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Collections\MatchCollection;
use App\Kravanh\Domain\Match\Observers\MatchObserver;
use App\Kravanh\Domain\Match\Scopes\EnvironmentScope;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\GroupEnum;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\Match\Supports\MemberTypeHelper;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use BenSampo\Enum\Traits\CastsEnums;
use Database\Factories\MatchesFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class Matches extends Model
{
    use CastsEnums;
    use HasFactory;

    const KEY_LAST_FIGHT_NUMBER = 'env:match:fight_number:';
    const MATCH_RESULT_TODAY = 'match:result:today:'; //environmentId:groupId
    const MATCH_NO_LIVE = 'match:not:live:';
    const MATCH_LIVE = 'match:live:'; //match:live:environmentId:groupId
    const ADJUST = 'adjust:';
    const MATCH_BET_INFO = 'match:bet:info:';

    protected $table = 'matches';

    protected $guarded = [];

    protected $appends = [
        'bet_opened',
        'bet_closed',
        'match_end',
        'payout_wala',
        'total_bet_duration',
        'type',
        'red_label',
        'blue_label',
    ];

    protected $hidden = ['created_at', 'updated_at', 'meta'];

    protected $casts = [
        'user_id' => 'integer',
        'environment_id' => 'integer',
        'match_date' => 'date:Y-m-d',
        'result' => MatchResult::class,
        'fight_number' => 'integer',
        'group_id' => 'integer',
        'payout_total' => 'integer',
        'payout_meron' => 'integer',
        'total_ticket' => 'integer',
        'wala_total_bet' => 'integer',
        'wala_total_payout' => 'integer',
        'meron_total_bet' => 'integer',
        'meron_total_payout' => 'integer',
        'match_started_at' => 'datetime',
        'match_end_at' => 'datetime',
        'bet_started_at' => 'datetime',
        'bet_stopped_at' => 'datetime',
        'meta' => 'json'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new EnvironmentScope());
        static::observe(MatchObserver::class);
    }

    protected static function newFactory(): MatchesFactory
    {
        return MatchesFactory::new();
    }

    public function newCollection(array $models = []): MatchCollection
    {
        return new MatchCollection($models);
    }


    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function betRecords(): HasMany
    {
        return $this->hasMany(BetRecord::class, 'match_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }


    public function owner($user): bool
    {
        return $this->user_id === $user->id;
    }


    //scope
    public function scopeToday(Builder $query)
    {
        $query
            ->where('match_date', Date::today()->format('Y-m-d'));
    }

    public function scopeOnlyWinLossResult(Builder $query)
    {
        $query->whereIn('result', [MatchResult::MERON, MatchResult::WALA]);
    }

    public function scopeMatchEnded(Builder $query)
    {
        $query
            ->whereNotNull('match_end_at');
    }

    public function scopeActiveMatch(Builder $query)
    {
        $query
            ->whereNull('match_end_at');
    }

    public static function onBetting(): Matches|null
    {
        return Matches::activeMatch()
            ->whereNull('bet_stopped_at')
            ->first();
    }

    public static function live($user): Matches|null
    {
        return Matches::liveFromCache(
            envId: $user->environment_id,
            groupId: $user->group_id
        );
    }


    public function totalBetOfMember($userId): array
    {

        $data = BetRecord::query()
            ->select(['bet_on', DB::raw('SUM(amount) AS total_bet')])
            ->where('user_id', $userId)
            ->where('match_id', $this->id)
            ->groupBy('bet_on')
            ->get()
            ->map(fn($bet) => [strtolower($bet->bet_on->key) => $bet->total_bet])
            ->collapse()
            ->toArray();

        $member = Member::select('currency')->find($userId);

        return [
            'meron' => priceFormat(fromKHRtoCurrency($data['meron'] ?? 0, $member->currency), ''),
            'wala' => priceFormat(fromKHRtoCurrency($data['wala'] ?? 0, $member->currency), ''),
        ];
    }

    public static function liveWithDefault($user): array
    {
        /**
         * @var User $user
         */
        $match = Matches::live($user);

        if ($match) {

            $data = $match->broadCastDataToMember();

            if (config('option.betting.show_only_own_bet', false) && $user->type->is(UserType::MEMBER)) {
                $totalBetOfMember = $match->totalBetOfMember(userId: $user->id);
                $data['meron_total_bet'] = $totalBetOfMember['meron'];
                $data['wala_total_bet'] = $totalBetOfMember['wala'];
            }

            if (!$user->isNormalMember() && !$data['bet_closed']) {
                $memberTypeStatus = MemberTypeHelper::from($match->id, $user->getMemberTypeId())->getBetStatus();
                $data['status'] = $memberTypeStatus['status'];
                $data['disable_bet_button'] = $memberTypeStatus['disable_bet_button'];
                $data['bet_status'] = $memberTypeStatus['bet_status'];
                $data['bet_opened'] = $memberTypeStatus['bet_status'] === 'open';
            }

            return $data;
        }

        return [
            'id' => 0,
            'environment_id' => $user->environment_id,
            'fight_number' => '#',
            'meron_payout' => '#.##',
            'meron_total_bet' => '0',
            'wala_payout' => '#.##',
            'wala_total_bet' => '0',
            'status' => 'close',
            'bet_opened' => false,
            'bet_status' => 'Close',
            'bet_closed' => false,
            'match_end' => false,
            'result' => 'None',
            'disable_bet_button' => true,
            'total_ticket' => 0,
        ];
    }


    public static function todayResultCount($groupId): array
    {
        return Matches::todayMatchResult($groupId)->toResultCount();
    }

    public static function todayMatchResult($groupId): MatchCollection
    {

        // The first match of the day = 1
        $lastFightNumber = Matches::nextFightNumber($groupId) - 1;

        if ($lastFightNumber === 0) {
            return MatchCollection::make();
        }

        return Cache::remember(
            self::MATCH_RESULT_TODAY . ':' . $groupId,
            now()->addSeconds(3),
            function () use ($lastFightNumber, $groupId) {
                return Matches::query()
                    ->withoutGlobalScopes()
                    ->select(['id', 'result', 'fight_number'])
                    ->where('group_id', $groupId)
                    ->where('fight_number', '<=', $lastFightNumber)
                    ->when(Date::now()->hour <= 6, fn($query) => $query->where('match_started_at', '>', Date::today()->subDay()->format('Y-m-d') . ' 6:00'))
                    ->when(Date::now()->hour > 6, fn($query) => $query->whereDate('match_date', Date::today()->format('Y-m-d')))
                    ->orderByDesc('id')
                    ->limit($lastFightNumber)
                    ->get()->reverse();
            }
        );
    }

    public static function todayResultAsSymbol($groupId): array
    {
        return Matches::todayMatchResult($groupId)->toSymbolResultV2();
    }

    public static function todayResultSummary($groupId): array
    {
        return [
            'count' => Matches::todayResultCount($groupId),
            'symbol' => Matches::todayResultAsSymbol($groupId)
        ];
    }

    public function payoutRate(int $betOn): float
    {
        if ($betOn === BetOn::MERON) {
            return ($this->payout_meron / 100);
        }

        return (($this->payout_total - $this->payout_meron) / 100);
    }

    public function canBet()
    {
        return $this->isBettingOpened() && !$this->isBettingClosed();
    }

    public function isBettingOpened(): bool
    {
        return !is_null($this->bet_started_at);
    }

    public function isBettingClosed(): bool
    {
        return !is_null($this->bet_stopped_at);
    }

    public function isMatchEnded(): bool
    {
        return !is_null($this->match_end_at);
    }

    public function totalBet()
    {
        return $this->meron_total_bet + $this->wala_total_bet;
    }

    public function totalPayout()
    {
        return $this->meron_total_payout + $this->wala_total_payout;
    }

    public static function nextFightNumber(int $groupId): int
    {

        $todayLastFightNumber = Matches::getLastFightNumberByDate($groupId, Date::today()->toDateString());

        if (!is_null($todayLastFightNumber)) {
            return $todayLastFightNumber + 1;
        }

        $yesterdayLastFightNumber = Matches::getLastFightNumberByDate($groupId, Date::yesterday()->toDateString());

        if (!is_null($yesterdayLastFightNumber)) {
            return $yesterdayLastFightNumber + 1;
        }

        return 1;

    }

    public static function getLastFightNumberByDate(
        int    $groupId,
        string $date
    ): ?int
    {
        return Matches::query()
            ->where('group_id', $groupId)
            ->whereDate('match_date', $date)
            ->orderByDesc('id')
            ->value('fight_number');
    }

    public function getTotalBetDurationAttribute(): float|int
    {
        return $this->bet_started_at?->diffInSeconds($this->bet_stopped_at) ?? 0;
    }


    public function getBetClosedAttribute(): bool
    {
        return $this->isBettingClosed();
    }

    public function getBetOpenedAttribute(): bool
    {
        return $this->isBettingOpened();
    }

    public function getMatchEndAttribute(): bool
    {
        return !is_null($this->match_end_at);
    }

    public function getPayoutWalaAttribute()
    {
        return $this->payout_total - $this->payout_meron;
    }

    public function walaBenefit()
    {
        return ($this->meron_total_bet - $this->wala_total_payout);
    }

    public function meronBenefit()
    {
        return ($this->wala_total_bet - $this->meron_total_payout);
    }

    public function matchWinLoss()
    {

        return $this->result->is(MatchResult::WALA)
            ? $this->walaBenefit()
            : $this->meronBenefit();
    }

    public static function todayWinLoss(int $envId, int $groupId)
    {
        return Cache::remember('MATCH:WINLOSS:TODAY:' . $groupId, now()->addSeconds(5), function () use ($groupId) {
            return BetRecord::query()
                ->withoutLiveMatch()
                ->selectRaw('SUM(
                CASE
                    WHEN matches.result = ' . MatchResult::CANCEL . ' THEN 0
                    WHEN matches.result = ' . MatchResult::DRAW . ' THEN 0
                    WHEN matches.result = ' . MatchResult::PENDING . ' THEN 0
                    WHEN matches.result = ' . MatchResult::NONE . ' THEN 0
                    WHEN bet_records.bet_on = matches.result THEN bet_records.payout
                    ELSE -(bet_records.amount)
                END
            ) AS win_lose')
                ->leftJoin('matches', 'bet_records.match_id', '=', 'matches.id')
                ->where('matches.group_id', $groupId)
                ->whereDate('bet_records.bet_date', Date::today()->format('Y-m-d'))
                ->where('bet_records.status', BetStatus::ACCEPTED)
                ->value('win_lose') ?? 0;
        });
    }

    /**
     * @return array{id: int, environment_id: int, group_id: int, meron_payout: string, wala_payout: string}
     */

    public function broadCastPayout(): array
    {
        return [
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
            'meron_payout' => formatPayout($this->payoutRate(BetOn::MERON)),
            'wala_payout' => formatPayout($this->payoutRate(BetOn::WALA)),
        ];
    }

    public function broadCastToggleBet(): array
    {
        return array_merge([
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
        ], $this->betStatus());
    }

    public function betStatus(): array
    {
        $status = 'close';
        $disableBetButton = true;

        if ($this->bet_opened) {
            $status = 'open';
            $disableBetButton = false;
        }

        if ($this->bet_closed) {
            $status = 'close';
            $disableBetButton = true;
        }

        return [
            'status' => $status,
            'disable_bet_button' => $disableBetButton,
            'bet_status' => $status
        ];
    }

    public function broadcastMatchSummary($ticketMemberType): array
    {

        $meronTotalBet = $this->meron_total_bet;
        $walaTotalBet = $this->wala_total_bet;
        $totalTicket = $this->total_ticket;
        $walaBenefit = $this->walaBenefit();
        $meronBenefit = $this->meronBenefit();
        $totalBetByMemberType = 0;

        $matchBetInfoKey = $this->getCacheKey(self::MATCH_BET_INFO);

        if (Cache::has($matchBetInfoKey)) {

            $payload = Cache::get($matchBetInfoKey);
            $totalTicket = $payload['totalTicket'];
            $meronTotalBet = $payload['meronTotalBet'];
            $walaTotalBet = $payload['walaTotalBet'];

            $walaBenefit = $meronTotalBet - $payload['walaTotalPayout'];
            $meronBenefit = $walaTotalBet - $payload['meronTotalPayout'];

            $totalBetByMemberType = $payload['totalBetByMemberType'] ?? [];
        }
        $totalBetByMemberTypeFormat = [];

        foreach ($totalBetByMemberType as $key => $value) {
            $tmp = $value;
            $tmp['total'] = priceFormat($value['total'] ?? 0, '');
            $tmp['wala'] = priceFormat($value['wala'] ?? 0, '');
            $tmp['meron'] = priceFormat($value['meron'] ?? 0, '');
            $totalBetByMemberTypeFormat[$key] = $tmp;
        }

        return [
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
            'ticketMemberType' => $ticketMemberType,
            'meron_total_bet' => priceFormat($meronTotalBet, ''),
            'wala_total_bet' => priceFormat($walaTotalBet, ''),
            'wala_benefit' => priceFormat($walaBenefit, ''),
            'meron_benefit' => priceFormat($meronBenefit, ''),
            'total_ticket' => $totalTicket,
            'totalBetByMemberType' => $totalBetByMemberTypeFormat
        ];
    }


    public function broadCastDataToMember(): array
    {
        $status = 'close';
        $disableBetButton = true;

        if ($this->bet_opened) {
            $status = 'open';
            $disableBetButton = false;
        }

        if ($this->bet_closed) {
            $status = 'close';
            $disableBetButton = true;
        }

        $meronPayout = \formatPayout($this->payoutRate(BetOn::MERON));
        $walaPayout = \formatPayout($this->payoutRate(BetOn::WALA));

        $payoutAdjusted = true;

        if (!Cache::has(Matches::ADJUST . $this->id)) {
            $meronPayout = '#.##';
            $walaPayout = '#.##';
            $payoutAdjusted = false;
        }

        $matchBetInfoFromCache = Cache::get($this->getCacheKey(self::MATCH_BET_INFO), [
            'meronTotalBet' => $this->meron_total_bet,
            'walaTotalBet' => $this->wala_total_bet
        ]);

        return [
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
            'fight_number' => $this->fight_number,
            'meron_payout' => $meronPayout,
            'meron_total_bet' => priceFormat($matchBetInfoFromCache['meronTotalBet'], ''),
            'wala_payout' => $walaPayout,
            'wala_total_bet' => priceFormat($matchBetInfoFromCache['walaTotalBet'], ''),
            'status' => $status,
            'bet_opened' => $this->bet_opened,
            'bet_status' => $status,
            'bet_closed' => $this->bet_closed,
            'match_end' => $this->match_end,
            'result' => $this->result->description,
            'disable_bet_button' => $disableBetButton,
            'total_ticket' => $this->total_ticket,
            'payout_adjusted' => $payoutAdjusted
        ];
    }

    public static function estimateBenefit($envId, $groupId, $initialize = false): array
    {
        $initializePayload = [
            'id' => 0,
            'environment_id' => $envId,
            'group_id' => $groupId,
            'wala_benefit' => 0,
            'meron_benefit' => 0,
            'total_ticket' => 0
        ];

        if ($initialize) return $initializePayload;

        $match = Matches::withoutGlobalScopes()
            ->where('environment_id', $envId)
            ->where('group_id', $groupId)
            ->activeMatch()
            ->first();

        if (!$match) return $initializePayload;

        return $match->broadcastEstimateBenefit();
    }

    public function broadcastEstimateBenefit(): array
    {
        return [
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
            'wala_benefit' => priceFormat($this->walaBenefit(), ''),
            'meron_benefit' => priceFormat($this->meronBenefit(), ''),
            'total_ticket' => $this->total_ticket
        ];
    }

    public function cacheKey(): string
    {
        return self::MATCH_LIVE . $this->environment_id . $this->group_id;
    }

    public function liveRefreshCache(): void
    {
        Cache::put($this->getCacheKey(self::MATCH_LIVE), $this, now()->addSeconds(10));

        if (!is_null($this->match_end_at)) {
            $this->liveClearCache();
        }
    }

    public function clearCacheMatchNotLive()
    {
        Cache::forget($this->getCacheKey(self::MATCH_NO_LIVE));
    }

    public function getCacheKey(string $key): string
    {

        $unique = $this->environment_id . ':' . $this->group_id;

        return match ($key) {
            self::MATCH_NO_LIVE => self::MATCH_NO_LIVE . $unique,
            self::MATCH_LIVE => self::MATCH_LIVE . $unique,
            self::KEY_LAST_FIGHT_NUMBER => self::KEY_LAST_FIGHT_NUMBER . $unique,
            self::MATCH_RESULT_TODAY => self::MATCH_RESULT_TODAY . ':' . $this->group_id,
            self::MATCH_BET_INFO => self::MATCH_BET_INFO . ':' . $this->group_id . ':' . $this->fight_number
        };
    }

    public function liveClearCache()
    {
        Cache::forget($this->getCacheKey(self::MATCH_LIVE));
    }

    public static function LiveFromCache($envId, $groupId)
    {
        $unique = $envId . ':' . $groupId;

        $cacheKeyMatchLive = self::MATCH_LIVE . $unique;
        $cacheKeyNoMatchLive = self::MATCH_NO_LIVE . $unique;

        Cache::forget($cacheKeyMatchLive);

        return Cache::remember(
            $cacheKeyMatchLive,

            now()->addSeconds(5),
            function () use ($cacheKeyNoMatchLive, $envId, $groupId) {

                if (Cache::has($cacheKeyNoMatchLive)) {
                    return null;
                }

                $match = Matches::withoutGlobalScopes()
                    ->where('environment_id', $envId)
                    ->where('group_id', $groupId)
                    ->activeMatch()
                    ->first();

                if (!$match) {
                    Cache::put($cacheKeyNoMatchLive, true, now()->addSeconds(5));
                }

                if ($match) {
                    $match->total_ticket = Cache::get($match->getCacheKey(Matches::MATCH_BET_INFO))['totalTicket'] ?? 0;
                }

                return $match;
            }
        );
    }

    public function cacheLastFightNumber(): void
    {
        Cache::put($this->getCacheKey(self::KEY_LAST_FIGHT_NUMBER), $this->fight_number);
    }

    public function getLastFightNumber(): int
    {
        return Cache::get($this->getCacheKey(self::KEY_LAST_FIGHT_NUMBER), 0);
    }

    public static function lastFightNumber($envId, $groupId)
    {
        return Cache::get(self::KEY_LAST_FIGHT_NUMBER . $envId . ':' . $groupId, 0);
    }

    public function endMatch(int $result): void
    {
        $this->match_end_at = now();
        $this->bet_started_at ??= now();
        $this->bet_stopped_at ??= now();
        $this->result = $result;
        $this->saveQuietly();

        $this->liveRefreshCache();
    }

    public function getTypeAttribute(): string
    {
        if ($this->group_id === 3) {
            return GroupEnum::BOXING;
        }

        return GroupEnum::COCK_FIGHT;
    }

    public function getRedLabelAttribute(): string
    {
        return $this->type === GroupEnum::BOXING ? __('frontend.red') : __('frontend.meron');
    }

    public function getBlueLabelAttribute(): string
    {
        return $this->type === GroupEnum::BOXING ? 'blue' : 'wala';
    }


    public function isWinResult(): bool
    {
        return in_array($this->result->value, [MatchResult::WALA, MatchResult::MERON]);
    }

    public function isInvalidResultForPayout(): bool
    {
        return in_array($this->result->value, [MatchResult::NONE, MatchResult::PENDING]);
    }

    public function isNotAllowToBet($type, $amount, Member|User $member): bool
    {
        $isBetDisable = Cache::get("match:{$this->id}:disable:{$type}", false);

        if (!$isBetDisable) {
            return false;
        }


        if ($member->canBetWhenDisable()) {

            return false;
        }

        $threshold = appGetSetting('disable_bet_threshold_amount', 400000);

        if ($amount >= $threshold) {
            return true;
        }

        return false;
    }
}
