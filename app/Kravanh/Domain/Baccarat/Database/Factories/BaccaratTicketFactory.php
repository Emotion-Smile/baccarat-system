<?php

namespace App\Kravanh\Domain\Baccarat\Database\Factories;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\User\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaccaratTicketFactory extends Factory
{
    protected $model = BaccaratTicket::class;

    public function definition(): array
    {
        $member = BaccaratFactoryHelper::make()->member();
        return [
            'game_table_id' => BaccaratFactoryHelper::make()->gameTableId(),
            'baccarat_game_id' => BaccaratFactoryHelper::make()->baccaratGameId(),
            'user_id' => $member->id,
            'super_senior' => $member->super_senior,
            'senior' => $member->senior,
            'master_agent' => $member->master_agent,
            'agent' => $member->agent,
            'amount' => $this->makeAmount(),
            'bet_on' => $this->faker->randomElement(['player', 'banker', 'tie', 'big', 'small', 'player_pair', 'banker_pair']),
//            'bet_type' => $this->makeBetType(...),
            'payout_rate' => $this->makePayoutRate(...),
            'payout' => $this->makePayout(...),// Closure call
            'status' => 'accepted',//$this->faker->randomElement(['accepted', 'pending', 'cancelled']),
            // 'share' => json_encode([
            //     'member' => 0,
            //     'agent' => 50,
            //     'master_agent' => 10,
            //     'senior' => 20,
            //     'super_senior' => 10,
            //     'company' => 10]),
            // 'commission' => json_encode([
            //     'member' => 0.001,
            //     'agent' => 0.001,
            //     'master_agent' => 0.001,
            //     'senior' => 0.001,
            //     'super_senior' => 0.001,
            //     'company' => 0
            // ]),
            'share' => [
                'member' => 0,
                'agent' => 50,
                'master_agent' => 10,
                'senior' => 20,
                'super_senior' => 10,
                'company' => 10
            ],
            'commission' => [
                'member' => 0.001,
                'agent' => 0.001,
                'master_agent' => 0.001,
                'senior' => 0.001,
                'super_senior' => 0.001,
                'company' => 0
            ],
            'in_year' => Carbon::now()->year,
            'in_month' => Carbon::now()->month,
            'in_day' => Carbon::now()->format('Ymd'),
            'ip' => $this->faker->ipv4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

//    private function makePayoutRate(array $attribute): int
//    {
//        if ($attribute['bet_on'] === 'tie' && $attribute['bet_type'] === 'tie') {
//            return 7;
//        }
//
//        return 1;
//    }
    private function makePayoutRate(array $attribute): int
    {
        if ($attribute['bet_on'] === 'tie') {
            return 8;
        }
//        if ($attribute['bet_on'] === 'banker')
//        {
//            return 0.95;
//        }
        return 1;
    }

    private function makeAmount()
    {
        return $this->faker->randomElement([4000, 8000, 12_000, 20_000, 40_000]);
    }

    private function makePayout(array $attribute): int
    {
        return $attribute['amount'] * $attribute['payout_rate'];
    }

//    private function makeBetType(array $attribute): string
//    {
//
//        if ($attribute['bet_on'] === 'dragon') {
//            return $this->faker->randomElement(['dragon', 'red', 'black', 'small', 'big']);
//        }
//
//        if ($attribute['bet_on'] === 'tiger') {
//            return $this->faker->randomElement(['tiger', 'red', 'black', 'small', 'big']);
//        }
//
//        return 'tie';
//    }

//    public function forMember(Member $member, BaccaratGame $dragonTigerGame): BaccaratTicketFactory
//    {
//        return $this->state(function (array $attributes) use ($member, $dragonTigerGame) {
//            return [
//                'dragon_tiger_game_id' => $dragonTigerGame->id,
//                'game_table_id' => $dragonTigerGame->game_table_id,
//                'super_senior' => $member->super_senior,
//                'senior' => $member->senior,
//                'master_agent' => $member->master_agent,
//                'agent' => $member->agent,
//                'user_id' => $member->id
//            ];
//        });
//
//    }
}
