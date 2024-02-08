<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Actions\BlockMemberWithdrawBalanceAction;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlockMemberWithdrawBalanceController
{
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {

        $match = Matches::live($request->user());

        $matchBlock = Matches::query()
            ->when($match, fn($query) => $query->where('id', '<', $match->id))
            ->where('group_id', $request->user()->group_id)
            ->orderByDesc('id')
            ->first();

        try {

            (new BlockMemberWithdrawBalanceAction())($matchBlock);

            return redirectSucceed("
            Fight number #{$matchBlock->fight_number} Block member withdraw balance success (it will be expired after 10 minute)
            ");

        } catch (\Exception $e) {
            return redirectError($e->getMessage());
        }
    }
}
