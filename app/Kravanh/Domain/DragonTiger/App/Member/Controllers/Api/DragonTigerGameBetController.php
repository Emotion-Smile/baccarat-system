<?php

namespace App\Kravanh\Domain\DragonTiger\App\Member\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameCreateTicketRequest;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

final class DragonTigerGameBetController
{
    public function __invoke(DragonTigerGameCreateTicketRequest $request): JsonResponse
    {
        try {
            return ApiResponse::ok(
                message: DragonTigerGameCreateTicketManagerAction::withBalanceFormat(
                    data: DragonTigerGameMemberBetData::fromRequest($request)
                )
            );
        } catch (Exception|Throwable $exception) {
            return ApiResponse::failed(
                message: __($exception->getMessage())
            );
        }
    }

}
