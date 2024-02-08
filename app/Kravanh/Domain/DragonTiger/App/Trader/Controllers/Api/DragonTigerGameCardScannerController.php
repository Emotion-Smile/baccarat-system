<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api;

use App\Kravanh\Domain\Card\ICardService;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerCardScanned;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;

final class DragonTigerGameCardScannerController
{
    public function __invoke(Request $request, ICardService $cardService): JsonResponse
    {
        $request->validate([
            'code' => 'required|integer|digits:4',
            'card' => ['required', 'string', function ($attribute, $value, $fail) {
                if (! in_array($value, ['dragon', 'tiger'])) {
                    $fail('The '.$attribute.' is invalid.');
                }
            }],
        ]);

        $user = $request->user();

        if (! $user->isDragonTigerDealer()) {
            return response()->json([
                'message' => 'You are not allowed to scan cards.',
            ], 403); // forbidden
        }

        try {

            $card = $cardService->get((int) $request->get('code'));

            DragonTigerCardScanned::dispatch(
                $user->getGameTableId(),
                $request->get('card'),
                $card['value'],
                $card['type'],
            );

            return response()->json([
                'cardName' => $card['name'],
            ]);

        } catch (ItemNotFoundException $e) {
            return response()->json([
                'cardName' => 'blank',
            ]);
        }
    }
}
