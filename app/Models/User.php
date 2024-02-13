<?php

namespace App\Models;

use App\Kravanh\Domain\BetCondition\Actions\GetBetConditionAction;
use App\Kravanh\Domain\DragonTiger\Support\HasDragonTigerGame;
use App\Kravanh\Domain\Environment\Models\Domain;
use App\Kravanh\Domain\Environment\Models\Environment;
use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasGameCondition as HasAF88GameCondition;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasGameCondition as HasT88GameCondition;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Models\Spread;
use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use App\Kravanh\Domain\User\Actions\GetMemberWinLoseTodayAction;
use App\Kravanh\Domain\User\Events\RefreshBalance;
use App\Kravanh\Domain\User\Events\RefreshTotalBet;
use App\Kravanh\Domain\User\Models\LoginHistory;
use App\Kravanh\Domain\User\Models\MemberPassword;
use App\Kravanh\Domain\User\Models\MemberType;
use App\Kravanh\Domain\User\Models\Message;
use App\Kravanh\Domain\User\Observers\UserObserver;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Domain\User\Supports\Traits\HasAttribute;
use App\Kravanh\Domain\User\Supports\Traits\HasBlockOption;
use App\Kravanh\Domain\User\Supports\Traits\HasLocalQueryScope;
use App\Kravanh\Domain\User\Supports\Traits\HasRoles;
use App\Kravanh\Domain\User\Supports\Traits\HasUserType;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use BenSampo\Enum\Traits\CastsEnums;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Nova\Actions\Actionable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Tags\HasTags;

class User extends Authenticatable implements Wallet
{
    use Actionable;
    use CastsEnums;
    use HasAF88GameCondition;
    use HasApiTokens;
    use HasAttribute;
    use HasBlockOption;
    use HasDragonTigerGame;
    use HasFactory;
    use HasLocalQueryScope;
    use HasProfilePhoto;
    use HasRoles;
    use HasT88GameCondition;
    use HasTags;
    use HasUserType;
    use HasWallet;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const KEY_LAST_ACTIVITY_AT = 'user:online:at:';

    const KEY_LAST_BET = 'user:bet:';

    const KEY_BLOCK_VIDEO_STREAM = 'user:stream:block:';

    const KEY_TODAY_BET_AMOUNT = 'user:bet:today:amount:'; // id:date

    const KEY_TODAY_BET_PAYOUT_AMOUNT = 'user:bet:today:payout:amount:'; //id:date

    const KEY_TOTAL_BET_PER_MATCH = 'user:bet:total-per-match:';

    const BALANCE_BLOCK = 'balance:block:';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'environment_id' => 'integer',
        'email_verified_at' => 'datetime',
        'group_id' => 'integer',
        'type' => UserType::class,
        'status' => Status::class,
        'bet_type' => BetType::class,
        'condition' => 'array',
        'internet_betting' => 'boolean',
        'last_login_at' => 'datetime',
        'currency' => Currency::class,
    ];

    protected $appends = [
        'profile_photo_url',
        'minimum_bet_per_ticket',
        'maximum_bet_per_ticket',
        'normal_member',
        'can_play_dragon_tiger',
        'can_play_baccarat'
    ];

    public function isSuspend(): bool
    {
        return $this->suspend == 1;
    }

    public static function booted(): void
    {
        static::observe(UserObserver::class);
    }

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function spread(): BelongsTo
    {
        return $this->belongsTo(Spread::class);
    }

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class, 'current_team_id', 'id');
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_user', 'user_id', 'message_id');
    }

    public function memberPassword(): HasOne
    {
        return $this->hasOne(MemberPassword::class);
    }

    public function disableGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    public static function frontendLogin(string $name): ?User
    {
        //create index on name column
        $user = User::where('name', $name)
            ->where('suspend', false)
            ->first();

        if (is_null($user)) {
            return null;
        }

        if (! $user->isMember() && ! $user->isTrader()) {
            return null;
        }

        return $user;
    }

    public function canImpersonate(): bool
    {
        return $this->isRoot();
    }

    // user member only
    public function matches(): HasMany
    {
        return $this->hasMany(Matches::class);
    }

    public function bets(): HasMany
    {
        return $this->hasMany(BetRecord::class, 'user_id', 'id');
    }

    public function isNormalMember(): bool
    {

        if (in_array($this->getMemberTypeId(), [null, 1, 2])) {
            return true;
        }

        return false;
    }

    public function getBalance()
    {
        return DB::table('wallets')
            ->select('balance')
            ->where('holder_id', $this->id)
            ->limit(1)->first()?->balance ?? 0;
    }

    public function getCurrentBalance(): float|int
    {
        return fromKHRtoCurrency($this->balanceInt, $this->currency);
    }

    public function toKHR($amount): float|int
    {
        return toKHR($amount, $this->currency);
    }

    public function getLastActivity(bool $format = false): ?string
    {
        $last = Cache::get(self::KEY_LAST_ACTIVITY_AT.$this->id);

        if ($last && $format) {
            return Date::createFromTimestamp($last)->diffForHumans();
        }

        return $last;
    }

    public function updateLastActivity(): void
    {
        Cache::put(self::KEY_LAST_ACTIVITY_AT.$this->id, now()->timestamp);
    }

    public function getLastBetAt(bool $formatTime = false): array
    {
        $lastBet = Cache::get(self::KEY_LAST_BET.$this->id);

        if ($lastBet && $formatTime) {
            $lastBet['at'] = Date::createFromTimestamp($lastBet['at'])->diffForHumans();
        }

        return [
            'at' => $lastBet['at'] ?? null,
            'fight_number' => $lastBet['fight_number'] ?? 0,
            'group_id' => $lastBet['group_id'] ?? null,
        ];
    }

    public function updateLastBetAt(int $fightNumber, int $groupId): void
    {
        Cache::put(self::KEY_LAST_BET.$this->id, [
            'at' => now()->timestamp,
            'fight_number' => $fightNumber,
            'group_id' => $groupId,
        ]);
    }

    /**
     * @throws Exception
     */
    public function getParent(): array
    {
        if ($this->type->isNot(UserType::SUB_ACCOUNT)) {
            throw new Exception('This account type don\'t have parent');
        }

        if ($this->super_senior) {
            return [
                'id' => $this->super_senior,
                'type' => UserType::SUPER_SENIOR,
            ];
        }

        if ($this->senior) {
            return [
                'id' => $this->senior,
                'type' => UserType::SENIOR,
            ];
        }

        if ($this->master_agent) {
            return [
                'id' => $this->master_agent,
                'type' => UserType::MASTER_AGENT,
            ];
        }

        if ($this->agent) {
            return [
                'id' => $this->agent,
                'type' => UserType::AGENT,
            ];
        }

        return [
            'id' => 0,
            'type' => UserType::COMPANY,
        ];

    }

    public function isOverWinLimitPerDay(): bool
    {
        $winLimitPerDay = (int) $this->condition['credit_limit'] ?? 0;

        return $this->totalWinToday() >= $this->toKHR($winLimitPerDay);
    }

    public function totalWinToday(): int
    {
        return app(GetMemberWinLoseTodayAction::class)($this->id);
    }

    public function todayBetAmountIncrement(int $amount): void
    {
        $key = self::KEY_TODAY_BET_AMOUNT.$this->id.Date::today()->format('Ymd');
        $totalAmount = Cache::get($key, 0);
        Cache::put($key, $totalAmount + $amount, Date::today()->addDay());
    }

    public function getCacheKey(string $key): string
    {
        $date = Date::today()->format('Ymd');
        $userId = $this->id;

        return match ($key) {
            self::KEY_TODAY_BET_AMOUNT => self::KEY_TODAY_BET_AMOUNT.$userId.$date,
            self::KEY_TODAY_BET_PAYOUT_AMOUNT => self::KEY_TODAY_BET_PAYOUT_AMOUNT.$userId.$date,
            self::KEY_LAST_BET => self::KEY_LAST_BET.$userId,
        };
    }

    public function getMatchCacheKey(string $key, string $machId): string
    {
        $userId = $this->id;

        return match ($key) {
            self::KEY_TOTAL_BET_PER_MATCH => self::KEY_TOTAL_BET_PER_MATCH.$userId.':'.$machId
        };
    }

    public function todayBetPayoutAmountIncrement(int $amount): void
    {
        if ($amount === 0) {
            return;
        }

        $key = self::KEY_TODAY_BET_PAYOUT_AMOUNT.$this->id.Date::today()->format('Ymd');
        $totalAmount = Cache::get($key, 0);
        Cache::put($key, $totalAmount + $amount, Date::today()->addDay());
    }

    public function incrementBetAmountPerMatch(int $amount, int $matchId): void
    {
        $key = self::KEY_TOTAL_BET_PER_MATCH.$this->id.':'.$matchId;
        Cache::put($key, $this->getTotalBetAmountOfMatch($matchId) + $amount, now()->addMinutes(10));
    }

    public function getTotalBetAmountOfMatch(int $matchId): int
    {

        $key = self::KEY_TOTAL_BET_PER_MATCH.$this->id.':'.$matchId;

        return Cache::get($key, 0);
    }

    public function forgetTotalBetAmountOfMatch(int $matchId): void
    {
        $key = self::KEY_TOTAL_BET_PER_MATCH.$this->id.':'.$matchId;
        Cache::forget($key);
    }

    public function isOverMatchLimitAmount(int $amount, int $matchId, int $groupId, string $currency): bool
    {
        $group = Group::select('meta')->find($groupId);
        $groupMatchLimit = $group->meta["{$currency}_match_limit"] ?? 0;
        $memberMatchLimit = (int) $this->condition['match_limit'] ?? 0;

        if (($groupMatchLimit > 0) && ($memberMatchLimit > $groupMatchLimit)) {
            $memberMatchLimit = $groupMatchLimit;
        }

        $totalBet = $this->getTotalBetAmountOfMatch($matchId) + $this->toKHR($amount);

        return $totalBet >= $this->toKHR($memberMatchLimit);
    }

    public function notifyRefreshBalance($balance): void
    {
        RefreshBalance::dispatch(
            $this->environment_id,
            $this->id,
            $balance
        );
    }

    public function notifyRefreshTotalBet(Matches $match): void
    {
        $totalBet = $match->totalBetOfMember($this->id);

        RefreshTotalBet::dispatch(
            $this->environment_id,
            $this->id,
            $totalBet['wala'],
            $totalBet['meron']
        );
    }

    public function canBetThisTable($matchId): bool
    {
        $mathLive = Matches::query()
            ->activeMatch()
            ->where('id', '!=', $matchId)
            ->pluck('id')
            ->toArray();

        if (empty($mathLive)) {
            return true;
        }

        $count = $this
            ->bets()
            ->whereIn('match_id', $mathLive)
            ->count();

        return ! $count;
    }

    public function underSuperSenior(): BelongsTo
    {
        return $this->belongsTo(self::class, 'super_senior');
    }

    public function underSenior(): BelongsTo
    {
        return $this->belongsTo(self::class, 'senior');
    }

    public function underMasterAgent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'master_agent');
    }

    public function underAgent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'agent');
    }

    public function markAsOffline(): void
    {
        $this->online = 0;
        $this->saveQuietly();
    }

    public function setCanBetWhenDisable(): void
    {
        Cache::put("can:bet:when:disable:{$this->id}", $this->can_bet_when_disable);
    }

    public function canBetWhenDisable(): bool
    {
        if (! $this->can_bet_when_disable) {
            return Cache::get("can:bet:when:disable:{$this->agent}", false);
        }

        return true;
    }

    public function computePayoutDeduction($rate): float
    {
        return $rate - number_format($this->getPayoutDeduction() / 100, 2);
    }

    public function getPayoutDeduction(): int
    {
        if (! is_null($this->spread_id)) {
            $spread = $this->spread;
        } else {
            $agent = User::select(['id', 'spread_id'])->find($this->agent);
            $spread = $agent->spread_id === null ? null : $agent->spread;
        }

        if (is_null($spread)) {
            return 0;
        }

        //spread disable
        if (! $spread?->active) {
            return 0;
        }

        return $spread->payout_deduction;
    }

    public function getMemberTypeId(): ?int
    {

        if (! is_null($this->current_team_id)) {
            return $this->current_team_id;
        }

        if ($this->isMember()) {
            $agent = User::select(['id', 'current_team_id'])->find($this->agent);

            return $agent->current_team_id;
        }

        return null;

    }

    /**
     * @return object{minBetPerTicket: int,maxBetPerTicket: int,matchLimit: int, winLimitPerDay: int, force: bool}
     */
    public function betCondition(): object
    {
        return app(GetBetConditionAction::class)(
            groupId: $this->group_id,
            memberId: $this->id,
            parentId: $this->agent
        );
    }

    public function hasAllowT88Game(): bool
    {
        if ($this->isRoot() || $this->isCompany() || $this->isCompanySubAccount()) {
            return true;
        }

        $user = $this;

        if ($user->isSubAccount()) {
            $user = self::find($user->getEnsureId());
        }

        if ($user->isSuperSenior()) {
            return $user->allow_t88_game;
        }

        return $user->underSuperSenior->allow_t88_game;
    }

    public function hasAllowAF88Game(): bool
    {
        if ($this->isRoot() || $this->isCompany() || $this->isCompanySubAccount()) {
            return true;
        }

        $user = $this;

        if ($user->isSubAccount()) {
            $user = self::find($user->getEnsureId());
        }

        if ($this->isSuperSenior()) {
            return $this->allow_af88_game;
        }

        return $this->underSuperSenior->allow_af88_game;
    }

    public function getGameTableId(): int
    {
        return $this->group_id;
    }
}
