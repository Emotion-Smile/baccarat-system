<?php

namespace App\Kravanh\Domain\Match\Supports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MemberTypeHelper
{

    public function __construct(private int $matchId, private int $memberTypeId)
    {

    }

    public static function from(int $matchId, int $memberTypeId): MemberTypeHelper
    {
        return new self($matchId, $memberTypeId);
    }

    public static function fromRequest(Request $request): MemberTypeHelper
    {
        return new self($request->matchId, $request->memberTypeId);
    }

    public function key(): string
    {
        return "member:type:{$this->matchId}:{$this->memberTypeId}";
    }

    public function canBet(): bool
    {
        if ($this->getBetStatus()['bet_status'] === 'close') {
            return false;
        }

        return true;
    }

    /**
     * @param array{status: string, disable_bet_button: boolean, bet_status: boolean } $status
     * @return void
     */
    public function setBetStatus(array $status): void
    {
        Cache::put($this->key(), $status, now()->addMinutes(5));
    }

    /**
     * @return array{status: string, disable_bet_button: boolean, bet_status: boolean }
     */
    public function getBetStatus(): array
    {
        return Cache::get($this->key(), [
            'status' => 'close',
            'disable_bet_button' => true,
            'bet_status' => 'close'
        ]);
    }

}
