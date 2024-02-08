<?php

namespace App\Kravanh\Domain\Match\Models;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Observers\BetRecordObserver;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Models\SuperSenior;
use Bavix\Wallet\Models\Transaction;
use BenSampo\Enum\Traits\CastsEnums;
use Database\Factories\BetRecordFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class BetRecord extends Model
{
    use CastsEnums;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'group_id' => 'integer',
        'fight_number' => 'integer',
        'bet_on' => BetOn::class,
        'status' => BetStatus::class,
        'type' => BetType::class,
        'benefit' => 'integer',
        'payout' => 'integer',
        'payout_rate' => 'decimal:2',
        'amount' => 'integer',
        'bet_time' => 'datetime:H:i:s',
        'bet_date' => 'date'
    ];

    protected static function booted(): void
    {
        static::observe(BetRecordObserver::class);
    }

    protected static function newFactory(): BetRecordFactory
    {
        return BetRecordFactory::new();
    }

    private function tableIndexExists(string $index): bool
    {
        $table = $this->getTable();
        $index = strtolower($index);
        $indices = \Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableIndexes($table);

        return array_key_exists($index, $indices);
    }

    public function scopeUseIndex(Builder $query, string $index): Builder
    {
        $table = $this->getTable();

        return $this->tableIndexExists($index)
            ? $query->from(DB::raw("`$table` USE INDEX(`$index`)"))
            : $query;
    }

    public function scopeForceIndex(Builder $query, string $index): Builder
    {
        $table = $this->getTable();

        return $this->tableIndexExists($index)
            ? $query->from(DB::raw("`$table` FORCE INDEX(`$index`)"))
            : $query;
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(Matches::class, 'match_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }


    public function scopeToday(Builder $query)
    {
        $query
            ->where('bet_date', Date::today()->format('Y-m-d'));
    }

    public function scopeWithoutLiveMatch(Builder $query)
    {
        $matchLive = Matches::activeMatch()->pluck('id')->toArray();

        if ($matchLive) {
            $query->whereNotIn('match_id', $matchLive);
        }
    }

    public function scopeOnlyLiveMatch(Builder $query)
    {
        $matchLive = Matches::activeMatch()->pluck('id')->toArray();
        $query->whereIn('match_id', $matchLive);
    }

    public function scopeByDate(Builder $query, $date)
    {

        if ($date === 'today') {
            $query->whereDate('bet_date', Date::today()->format('Y-m-d'));
        }

        if ($date === 'yesterday') {
            $query->whereDate('bet_date', Date::today()->subDay()->format('Y-m-d'));
        }

        if ($date === 'this-week') {

            $query->whereBetween('bet_date', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]);
        }

        if ($date === 'last-week') {

            $query->whereBetween('bet_date', [
                now()->subWeek()->startOfWeek()->format('Y-m-d'),
                now()->subWeek()->endOfWeek()->format('Y-m-d')
            ]);
        }

        if ($date === 'current-month') {

            $query->whereMonth('bet_date', date('m'))
                ->whereYear('bet_date', date('Y'));
        }

        if ($date === 'last-month') {

            $query->whereBetween('bet_date', [
                now()->subMonth()->startOfMonth()->format('Y-m-d'),
                now()->subMonth()->endOfMonth()->format('Y-m-d')
            ]);
        }
    }

    public function scopeByStatus(Builder $query, $status)
    {
        if ($status === 'cancel') {
            $query->whereHas('match', function ($query) {
                $query->whereResult(MatchResult::CANCEL);
            });
        } else if ($status === 'draw') {
            $query->whereHas('match', function ($query) {
                $query->whereResult(MatchResult::DRAW);
            });

        } else if ($status === 'pending') {
            $query->whereHas('match', function ($query) {
                $query->whereResult(MatchResult::PENDING);
            });

        } else if ($status === 'win') {
            $query->whereHas('match', function ($query) {
                $query->whereColumn('result', 'bet_records.bet_on');
            });
        } else if ($status === 'loss') {
            $query->whereHas('match', function ($query) {
                $query
                    ->whereNotIn('result', [
                            MatchResult::PENDING,
                            MatchResult::CANCEL,
                            MatchResult::DRAW]
                    )
                    ->whereColumn('result', '<>', 'bet_records.bet_on');
            });
        }
    }

    public function scopeExceptSomeUserType(Builder $query, $user)
    {
        $query->when(!in_array($user->type, exceptUserType()), function (Builder $query) use ($user) {
            $query->where($user->type, $user->id);
        });
    }

    public function amountDisplay(): string
    {
        return priceFormat($this->amount, '') . " x {$this->payout_rate} = " . priceFormat($this->amount * $this->payout_rate, '');
    }

    public function amountFormat($prefix = ''): string
    {
        return priceFormat($this->amount, $prefix);
    }

    public function getBetOnLabel()
    {
        $betOn = strtolower($this->bet_on->description);

        if ($this->group_id === 3) {
            $betOn = $betOn === 'wala' ? 'blue' : 'red';
        }

        return __('frontend.' . $betOn);
    }

    public function broadcastToTicketMonitor(): array
    {

        return [
            'id' => $this->id,
            'environment_id' => $this->environment_id,
            'group_id' => $this->group_id,
            'member_type_id' => $this->member_type_id,
            'fight_number' => $this->fight_number,
            'ip' => $this->ip,
            'country_code' => Cache::get($this->ip, ''),
            'member' => $this->user->name,
            'member_id' => $this->user_id,
            'bet_time' => $this->bet_time->format(config('kravanh.time_format')),
            'bet_on' => strtolower(BetOn::fromValue($this->bet_on)->description),
            'amount' => $this->amountDisplay(),
            'type' => $this->type->key
        ];

    }

    public function _super_senior(): BelongsTo
    {
        return $this->belongsTo(SuperSenior::class, 'super_senior');
    }

    public function _senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class, 'senior');
    }

    public function _master_agent(): BelongsTo
    {
        return $this->belongsTo(MasterAgent::class, 'master_agent');
    }

    public function _agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent');
    }
}
