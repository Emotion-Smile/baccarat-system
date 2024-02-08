<?php

namespace App\Kravanh\Domain\DragonTiger\App\Member\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketManagerAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastBetOfTheGameAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class DragonTigerGameRebettingController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {

            $user = $request->user();

            $bets = app(DragonTigerGameGetLastBetOfTheGameAction::class)(
                userId: $user->id,
                currency: $user->currency->value
            );

            $balance = Balance::format(
                amount: $user->balanceInt,
                currency: $user->currency
            );

            foreach ($bets as $bet) {
                $balance = DragonTigerGameCreateTicketManagerAction::withBalanceFormat(
                    data: DragonTigerGameMemberBetData::make(
                        member: $user,
                        amount: $bet['amount'],
                        betOn: $bet['betOn'],
                        betType: $bet['betType'],
                        ip: $request->ip()
                    )
                );
            }

            return ApiResponse::ok(
                message: $balance
            );

        } catch (Exception|Throwable $exception) {
            return ApiResponse::failed(
                message: __($exception->getMessage())
            );
        }
    }
}
