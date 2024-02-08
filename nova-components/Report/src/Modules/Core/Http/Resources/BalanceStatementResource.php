<?php

namespace KravanhEco\Report\Modules\Core\Http\Resources;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use KravanhEco\Report\Modules\Support\TransformAmount;

class BalanceStatementResource extends JsonResource
{
    public function toArray($request)
    {
        $meta = $this->meta; 
        $user = $this->payable;

        $type = $meta['type'] ?? '';
        $game = $this->getGame($type);

        list($group, $match) = $this->getMatchWithGroupByGame($game);

        $transformAmount = TransformAmount::make($user?->currency);

        return [
            'username' => $user?->name,
            'beforeBalance' => $transformAmount($meta['before_balance'] ?? 0),
            'amount' => $this->amount,
            'currentBalance' => $transformAmount($meta['current_balance'] ?? 0),
            'date' => $this->created_at->format('d-m-Y'),
            'time' => $this->created_at->format(config('kravanh.time_format')),
            'status' => $this->getStatus($type),
            'fightNumber' => $this->getFightNumber($match),
            'group' => $this->getGroupName($group),
            'game' => Str::of($game)->headline(),
            'betId' => $meta['bet_id'] ?? '',
            'amount' => $transformAmount($this->amount),
            'note' => $meta['note'] ?? '',
            'meta' => $meta ?? [
                'type' => '-', 
                'fight_number' => '-',
                'match_status' => '-'
            ]
        ];
    }

    protected function getMatchWithGroupByGame(?string $game): array
    {
        $group = $match = null;
        $matchId = $meta['match_id'] ?? null;

        if ($game === 'cock_fight' && $matchId) {
            $match = Cache::remember(
                key: 'match:bl:' . $matchId,
                ttl: Date::now()->addSeconds(10),
                callback: fn () => Matches::select(['id', 'group_id', 'fight_number'])->find($matchId)
            );

            $group = Cache::remember(
                key: 'match:group:' . $match->group_id,
                ttl: Date::now()->addSeconds(10),
                callback: fn () => Group::select(['id', 'name'])->find($match->group_id)
            );
        }

        return [$group, $match];
    }

    protected function getStatus(string $type): string 
    {
        return match ($type) {
            'bet' => 'bet',
            'payout' => 'payout',
            'refund' => 'refund',
            default => $this->type
        };
    }

    protected function getGame(?string $type = null): ?string
    {
        if (in_array($type, ['deposit', 'withdraw'])) {
            return null;
        }

        return $this->meta['game'] ?? 'cock_fight';
    }

    protected function getFightNumber(?Matches $match): string
    {
        $gameNo = $match?->fight_number ?? $this->meta['fight_number'] ?? '';
        
        if($gameNo && $this->getGame() === 'dragon_tiger') {
            return Str::replace('/', '_', $gameNo);
        }

        return $gameNo;
    }

    protected function getGroupName(?Group $group): string
    {
        return $group?->name ?? '';
    }
}