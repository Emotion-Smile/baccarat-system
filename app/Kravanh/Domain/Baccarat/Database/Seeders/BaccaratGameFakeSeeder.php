<?php

namespace App\Kravanh\Domain\Baccarat\Database\Seeders;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameMemberBettingWithdrawBalanceAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Database\Factories\BaccaratFactoryHelper;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Environment\Models\Environment;
use App\Kravanh\Domain\Game\Actions\GameBaccaratGetAction;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Bavix\Wallet\Models\Transaction;
use Hash;
use Illuminate\Database\Seeder;

final class BaccaratGameFakeSeeder extends Seeder
{
    // php artisan db:seed --class=\\App\\Kravanh\\Domain\\Baccarat\\Database\\Seeders\\BaccaratGameFakeSeeder
    public function run(): void
    {
        $this->call(GameSeeder::class);

        BaccaratGame::truncate();

        BaccaratTicket::truncate();

        $member = BaccaratFactoryHelper::make()->member();

        $txIds = BaccaratPayoutDeposited::pluck('transaction_id')->toArray();

        $betTxIds = Transaction::query()
            ->where('payable_id', $member->id)
            ->where('type', 'withdraw')
            ->get()
            ->filter(function ($tx) {
                $meta = $tx->meta['game'] ?? '';
                if ($meta === 'baccarat') {
                    return $tx;
                }
            })
            ->map(fn ($tx) => $tx->id)
            ->toArray();

        if ($txIds) {
            Transaction::whereIn('id', $txIds)->delete();
            BaccaratPayoutDeposited::truncate();
        }

        if ($betTxIds) {
            Transaction::whereIn('id', $betTxIds)->delete();
        }

        $tableId = app(GameBaccaratGetAction::class)()->firstTableId();

        $trader = $this->makeTrader($tableId);

//        BaccaratGame::factory([
//            'user_id' => $trader->id,
//            'game_table_id' => $tableId,
//            'result_submitted_user_id' => $trader->id,
//        ])->count(20)->create();

        BaccaratGame::factory()->count(10)->create([
            'user_id' => $trader->id,
            'game_table_id' => $tableId,
            'result_submitted_user_id' => $trader->id,
        ]);

//        $this->makeLiveTieGame();
//        $this->makeLiveCancelGame();
//        $this->makeLiveGame();

//        foreach (BaccaratGame::all() as $game) {
//
//            $tickets = BaccaratTicket::factory(['game_table_id' => $tableId, 'baccarat_game_id' => $game->id])->count(10)->create();
//
//            foreach ($tickets as $ticket) {
//                BaccaratGameMemberBettingWithdrawBalanceAction::from(
//                    member: $member,
//                    ticket: $ticket,
//                    game: $game
//                );
//            }
//
//            app(BaccaratPayoutProcessingManagerAction::class)(baccaratGameId: $game->id);
//        }

    }

    public function makeLiveCancelGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();
        $beforeLastGame = BaccaratGame::find($lastGame->id - 1);

        $beforeLastGame->player_first_card_value = null;
        $beforeLastGame->player_first_card_type = null;
        $beforeLastGame->player_first_card_color = null;
        $beforeLastGame->player_first_card_points = null;

        $beforeLastGame->player_second_card_value = null;
        $beforeLastGame->player_second_card_type = null;
        $beforeLastGame->player_second_card_color = null;
        $beforeLastGame->player_second_card_points = null;

        $beforeLastGame->player_third_card_value = null;
        $beforeLastGame->player_third_card_type = null;
        $beforeLastGame->player_third_card_color = null;
        $beforeLastGame->player_third_card_points = null;

        $beforeLastGame->player_total_points = null;
        $beforeLastGame->player_points = null;

        $beforeLastGame->banker_first_card_value = null;
        $beforeLastGame->banker_first_card_type = null;
        $beforeLastGame->banker_first_card_color = null;
        $beforeLastGame->banker_first_card_points = null;

        $beforeLastGame->banker_second_card_value = null;
        $beforeLastGame->banker_second_card_type = null;
        $beforeLastGame->banker_second_card_color = null;
        $beforeLastGame->banker_second_card_points = null;

        $beforeLastGame->banker_third_card_value = null;
        $beforeLastGame->banker_third_card_type = null;
        $beforeLastGame->banker_third_card_color = null;
        $beforeLastGame->banker_third_card_points = null;

        $beforeLastGame->banker_total_points = null;
        $beforeLastGame->banker_points = null;

        $beforeLastGame->winner = 'cancel';

        $beforeLastGame->saveQuietly();
    }

    public function makeLiveTieGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();


        $beforeLastGame = BaccaratGame::find($lastGame->id - 2);
        dd($beforeLastGame);
//        $beforeLastGame->winner = 'tie';

        $beforeLastGame->winner[] = 'tie';

        $beforeLastGame->saveQuietly();
    }

    public function makeLiveGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();

        $lastGame->result_submitted_user_id = null;

        $lastGame->player_first_card_value = null;
        $lastGame->player_first_card_type = null;
        $lastGame->player_first_card_color = null;
        $lastGame->player_first_card_points = null;

        $lastGame->player_second_card_value = null;
        $lastGame->player_second_card_type = null;
        $lastGame->player_second_card_color = null;
        $lastGame->player_second_card_points = null;

        $lastGame->player_third_card_value = null;
        $lastGame->player_third_card_type = null;
        $lastGame->player_third_card_color = null;
        $lastGame->player_third_card_points = null;

        $lastGame->player_total_points = null;
        $lastGame->player_points = null;

        $lastGame->banker_first_card_value = null;
        $lastGame->banker_first_card_type = null;
        $lastGame->banker_first_card_color = null;
        $lastGame->banker_first_card_points = null;

        $lastGame->banker_second_card_value = null;
        $lastGame->banker_second_card_type = null;
        $lastGame->banker_second_card_color = null;
        $lastGame->banker_second_card_points = null;

        $lastGame->banker_third_card_value = null;
        $lastGame->banker_third_card_type = null;
        $lastGame->banker_third_card_color = null;
        $lastGame->banker_third_card_points = null;

        $lastGame->banker_total_points = null;
        $lastGame->banker_points = null;

        $lastGame->winner = null;
        $lastGame->result_submitted_at = null;

        $lastGame->saveQuietly();
    }

    public function makeTrader(int $tableId): Trader
    {
        return Trader::updateOrCreate(
            ['name' => 'dt_seeder'],
            [
                'environment_id' => Environment::first()->id,
                'type' => UserType::TRADER,
                'password' => Hash::make('password'),
                'group_id' => $tableId,
                'two_factor_secret' => 'baccarat',
            ]
        );

    }
}
