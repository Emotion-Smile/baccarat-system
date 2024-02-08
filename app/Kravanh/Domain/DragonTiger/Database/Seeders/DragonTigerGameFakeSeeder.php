<?php

namespace App\Kravanh\Domain\DragonTiger\Database\Seeders;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameMemberBettingWithdrawBalanceAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Database\Factories\DragonTigerFactoryHelper;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\Environment\Models\Environment;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Bavix\Wallet\Models\Transaction;
use Hash;
use Illuminate\Database\Seeder;

final class DragonTigerGameFakeSeeder extends Seeder
{
    // php artisan db:seed --class=\\App\\Kravanh\\Domain\\DragonTiger\\Database\\Seeders\\DragonTigerGameFakeSeeder
    public function run(): void
    {

        $this->call(GameSeeder::class);

        DragonTigerGame::truncate();
        DragonTigerTicket::truncate();

        $member = DragonTigerFactoryHelper::make()->member();

        $txIds = DragonTigerPayoutDeposited::pluck('transaction_id')->toArray();

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
            DragonTigerPayoutDeposited::truncate();
        }

        if ($betTxIds) {
            Transaction::whereIn('id', $betTxIds)->delete();
        }

        $tableId = app(GameDragonTigerGetAction::class)()->firstTableId();
        $trader = $this->makeTrader($tableId);

        DragonTigerGame::factory([
            'user_id' => $trader->id,
            'game_table_id' => $tableId,
            'result_submitted_user_id' => $trader->id,
        ])->count(20)->create();

        $this->makeLiveTieGame();
        $this->makeLiveCancelGame();
        $this->makeLiveGame();

        foreach (DragonTigerGame::all() as $game) {

            $tickets = DragonTigerTicket::factory(['game_table_id' => $tableId, 'dragon_tiger_game_id' => $game->id])->count(10)->create();

            foreach ($tickets as $ticket) {
                DragonTigerGameMemberBettingWithdrawBalanceAction::from(
                    member: $member,
                    ticket: $ticket,
                    game: $game
                );
            }

            app(DragonTigerPayoutProcessingManagerAction::class)(dragonTigerGameId: $game->id);
        }

    }

    public function makeLiveCancelGame(): void
    {
        $lastGame = DragonTigerGame::query()->orderByDesc('id')->first();
        $beforeLastGame = DragonTigerGame::find($lastGame->id - 1);

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
        $lastGame = DragonTigerGame::query()->orderByDesc('id')->first();
        $beforeLastGame = DragonTigerGame::find($lastGame->id - 2);

        $beforeLastGame->winner = 'tie';

        $beforeLastGame->saveQuietly();
    }

    public function makeLiveGame(): void
    {
        $lastGame = DragonTigerGame::query()->orderByDesc('id')->first();

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
