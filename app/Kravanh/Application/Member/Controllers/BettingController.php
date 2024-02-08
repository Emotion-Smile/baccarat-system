<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\Match\Actions\CreateNewBetAction;
use App\Kravanh\Domain\Match\DataTransferObject\NewBetData;
use App\Kravanh\Domain\Match\Exceptions\AccountNotAllow;
use App\Kravanh\Domain\Match\Exceptions\AlreadyBetOnOtherTable;
use App\Kravanh\Domain\Match\Exceptions\BalanceBlocked;
use App\Kravanh\Domain\Match\Exceptions\BetAmountHigherThanMaximumPricePerTicketAllowed;
use App\Kravanh\Domain\Match\Exceptions\BetAmountLowerThanMinimumPricePerTicketAllowed;
use App\Kravanh\Domain\Match\Exceptions\GroupDisabled;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingClosed;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingFailed;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingNotYetOpen;
use App\Kravanh\Domain\Match\Exceptions\NoMatchFound;
use App\Kravanh\Domain\Match\Exceptions\NotEnoughCredit;
use App\Kravanh\Domain\Match\Exceptions\NotMemberAccount;
use App\Kravanh\Domain\Match\Exceptions\OverMatchLimit;
use App\Kravanh\Domain\Match\Exceptions\OverWinLimitPerDay;
use App\Kravanh\Domain\Match\Exceptions\TransactionNotAllowed;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BettingController
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'betAmount' => 'required|numeric',
            'betOn' => ['required', new EnumValue(BetOn::class)]
        ]);

        try {

            $user = $request->user();
            $disableGroupId = $this->getGroupIdDisabled($user);

            if (in_array($user->group_id, $disableGroupId)) {
                throw new GroupDisabled();
            }

            (new CreateNewBetAction)(NewBetData::fromRequest($request));
            return redirectSucceed(__('betting.succeed'));

        } catch (
        AccountNotAllow|
        NotMemberAccount|
        NoMatchFound|
        MatchBettingNotYetOpen|
        MatchBettingClosed|
        BetAmountHigherThanMaximumPricePerTicketAllowed|
        BetAmountLowerThanMinimumPricePerTicketAllowed|
        NotEnoughCredit|
        BalanceBlocked|
        OverMatchLimit|
        AlreadyBetOnOtherTable|
        OverWinLimitPerDay|
        TransactionNotAllowed|
        MatchBettingFailed|
        GroupDisabled
        $exception) {
            return redirectError(__($exception->getMessage()));
        }
    }

    private function getGroupIdDisabled($user): array
    {
        return DB::table('group_user')
            ->select('group_id')
            ->whereIn('user_id', [$user->id, $user->agent])
            ->pluck('group_id')
            ->toArray();
    }
}
