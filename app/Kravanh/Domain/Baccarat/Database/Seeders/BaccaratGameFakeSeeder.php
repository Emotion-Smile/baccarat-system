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
                if ($meta === 'dragon_tiger') {
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

        BaccaratGame::factory([
            'user_id' => $trader->id,
            'game_table_id' => $tableId,
            'result_submitted_user_id' => $trader->id,
        ])->count(20)->create();

        $this->makeLiveTieGame();
        $this->makeLiveCancelGame();
        $this->makeLiveGame();

        foreach (BaccaratGame::all() as $game) {

            $tickets = BaccaratTicket::factory(['game_table_id' => $tableId, 'dragon_tiger_game_id' => $game->id])->count(10)->create();

            foreach ($tickets as $ticket) {
                BaccaratGameMemberBettingWithdrawBalanceAction::from(
                    member: $member,
                    ticket: $ticket,
                    game: $game
                );
            }

            app(BaccaratPayoutProcessingManagerAction::class)(dragonTigerGameId: $game->id);
        }

    }

    public function makeLiveCancelGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();
        $beforeLastGame = BaccaratGame::find($lastGame->id - 1);

        $beforeLastGame->dragon_result = null;
        $beforeLastGame->dragon_type = null;
        $beforeLastGame->dragon_range = null;
        $beforeLastGame->dragon_color = null;

        $beforeLastGame->tiger_result = null;
        $beforeLastGame->tiger_type = null;
        $beforeLastGame->tiger_range = null;
        $beforeLastGame->tiger_color = null;

        $beforeLastGame->winner = 'cancel';

        $beforeLastGame->saveQuietly();
    }

    public function makeLiveTieGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();
        $beforeLastGame = BaccaratGame::find($lastGame->id - 2);

        $beforeLastGame->winner = 'tie';

        $beforeLastGame->saveQuietly();
    }

    public function makeLiveGame(): void
    {
        $lastGame = BaccaratGame::query()->orderByDesc('id')->first();

        $lastGame->result_submitted_user_id = null;
        $lastGame->dragon_result = null;
        $lastGame->dragon_type = null;

        $lastGame->tiger_result = null;
        $lastGame->tiger_type = null;

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
                'two_factor_secret' => 'dragon_tiger',
            ]
        );

    }
}
