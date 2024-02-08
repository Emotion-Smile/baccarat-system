<?php

namespace Database\Factories;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class BetRecordFactory extends Factory
{
    protected $model = BetRecord::class;

    public function definition(): array
    {
        $match = Matches::first();

        return [
            'user_id' => 1,
            'environment_id' => 1,
            'super_senior' => 1,
            'senior' => 2,
            'master_agent' => 3,
            'agent' => 4,
            'match_id' => $match->id,
            'fight_number' => $match->fight_number,
            'type' => BetType::AUTO_ACCEPT, //@TODO need check with user
            'status' => BetStatus::ACCEPTED, //@TODO  need check with user
            'bet_on' => BetOn::WALA,
            'payout_rate' => function (array $attribute) use ($match) {
                return $match->payoutRate($attribute['bet_on']);
            },
            'amount' => 100,
            'payout' => function (array $attribute) {
                return (int)($attribute['amount'] * $attribute['payout_rate']);
            },
            'benefit' => function (array $attribute) {
                return (int)($attribute['amount'] - $attribute['payout']);
            },
            'bet_date' => Date::today()->format('Y-m-d'),
            'ip' => $this->faker->ipv4
        ];
    }

    /*
    public function definition(): array
    {
        $user = Member::find(User::whereType(UserType::MEMBER)->first()->id);
        $match = Matches::first();

        $betOn = BetOn::WALA;

        return [
            'user_id' => $user->id,
            'environment_id' => $user->environment_id,
            'super_senior' => $user->super_senior,
            'senior' => $user->senior,
            'master_agent' => $user->master_agent,
            'agent' => $user->agent,
            'match_id' => $match->id,
            'fight_number' => $match->fight_number,
            'type' => BetType::AUTO_ACCEPT, //@TODO need check with user
            'status' => BetStatus::ACCEPTED, //@TODO  need check with user
            'bet_on' => $betOn,
            'payout_rate' => function (array $attribute) use ($match) {
                return $match->payoutRate($attribute['bet_on']);
            },
            'amount' => function (array $attribute) use ($user) {

                $amount = $this->faker->randomElement(['1000', '2000', '3000', '5000', '4000', '6000', '7000', '8000', '9000', '10000']);

                $user->withdraw($amount, [
                    'type' => 'bet',
                    'fight_number' => $attribute['fight_number'],
                    'amount' => $amount,
                    'bet_on' => $attribute['bet_on'],
                    'payout_rate' => $attribute['payout_rate'],
                    'payout' => (int)($amount * $attribute['payout_rate'])
                ]);

                return $amount;
            },
            'payout' => function (array $attribute) {
                return (int)($attribute['amount'] * $attribute['payout_rate']);
            },
            'benefit' => function (array $attribute) {
                return (int)($attribute['amount'] - $attribute['payout']);
            },
            'bet_date' => Date::today()->format('Y-m-d'),
            'ip' => $this->faker->ipv4
        ];
    }

    public function configure(): BetRecordFactory
    {
        return $this->afterCreating(function ($betRecord) {


            $match = Matches::find($betRecord->match_id);

            $match->total_ticket += 1;

            if ($betRecord->bet_on == BetOn::MERON) {
                $match->meron_total_bet += $betRecord->amount;
                $match->meron_total_payout += $betRecord->payout;
            }

            if ($betRecord->bet_on == BetOn::WALA) {
                $match->wala_total_bet += $betRecord->amount;
                $match->wala_total_payout += $betRecord->payout;
            }

            $match->saveQuietly();

        });
    }*/
}
