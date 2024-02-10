<?php

namespace App\Kravanh\Domain\Baccarat\Commands;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultManagerAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Throwable;

class BaccaratGameSubmitResultCommand extends Command
{
    protected $signature = 'local:dt:submit-result
                            {--playerFirstCardValue=1 : The result of card range from 0 to 9}
                            {--playerSecondCardValue=2 : The result of card range from 0 to 9}
                            {--playerThirdCardValue=5 : The result of card range from 0 to 9}
                            {--playerFirstCardType=club : The card type club,heart,diamond,spade}
                            {--playerSecondCardType=heart : The card type club,heart,diamond,spade}
                            {--playerThirdCardType=spade : The card type club,heart,diamond,spade}
                            {--playerPoints=8 : The result of card range from 0 to 9}
                            {--bankerFirstCardValue=3 : The result of card range from 0 to 9}
                            {--bankerSecondCardValue=2 : The result of card range from 0 to 9}
                            {--bankerThirdCardValue=0 : The result of card range from 0 to 9}
                            {--bankerFirstCardType=heart : The card type club,heart,diamond,spade}
                            {--bankerSecondCardType=diamond : The card type club,heart,diamond,spade}
                            {--bankerThirdCardType=club : The card type club,heart,diamond,spade}
                            {--bankerPoints=5 : The result of card range from 0 to 9}
                            ';
    protected $description =
        'php artisan local:dt:submit-result --playerFirstCardValue=1 --playerSecondCardValue=2 --playerThirdCardValue=5 --playerFirstCardType=club --playerSecondCardType=heart --playerThirdCardType=spade --playerPoints=8 --bankerFirstCardValue=3 --bankerSecondCardValue=2 --bankerThirdCardValue=0 --bankerFirstCardType=heart --bankerSecondCardType=diamond --bankerThirdCardType=club --bankerPoints=5';


    /**
     * @throws BaccaratGameNoLiveGameException
     * @throws Throwable
     */
    public function handle(): void
    {
        $result = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $type = ['heart', 'diamond', 'spade', 'club'];

        $playerFirstCardValue = Arr::random($result);
        $playerFirstCardType = Arr::random($type);
        $playerSecondCardValue = Arr::random($result);
        $playerSecondCardType = Arr::random($type);
        $playerThirdCardValue = Arr::random($result);
        $playerThirdCardType = Arr::random($type);
        $playerPoints = $playerFirstCardValue + $playerSecondCardValue + $playerThirdCardValue;

        $bankerFirstCardValue = Arr::random($result);
        $bankerFirstCardType = Arr::random($type);
        $bankerSecondCardValue = Arr::random($result);
        $bankerSecondCardType = Arr::random($type);
        $bankerThirdCardValue = Arr::random($result);
        $bankerThirdCardType = Arr::random($type);
        $bankerPoints = $bankerFirstCardValue + $bankerSecondCardValue + $bankerThirdCardValue;

        $trader = Trader::whereName('dt_seeder')->first();

        $baccaratGameId = (new BaccaratGameGetLiveGameIdByTableAction())(
            gameTableId: $trader->getGameTableId()
        );

        (new BaccaratGameSubmitResultManagerAction())(
            data: BaccaratGameSubmitResultData::make(
                user: $trader,
                baccaratGameId: $baccaratGameId,
//                playerResult: $playerResult,//$this->option('playerResult'),
//                playerType: $playerType,//$this->option('playerType'),
//                bankerResult: $bankerResult,//$this->option('bankerResult'),
//                bankerType: $bankerType//$this->option('bankerType'))
                playerFirstCardValue: $playerFirstCardValue,
                playerFirstCardType: $playerFirstCardType,
                playerSecondCardValue: $playerSecondCardValue,
                playerSecondCardType: $playerSecondCardType,
                playerThirdCardValue: $playerThirdCardValue,
                playerThirdCardType: $playerThirdCardType,
                playerPoints: $playerPoints,
                bankerFirstCardValue: $bankerFirstCardValue,
                bankerFirstCardType: $bankerFirstCardType,
                bankerSecondCardValue: $bankerSecondCardValue,
                bankerSecondCardType: $bankerSecondCardType,
                bankerThirdCardValue: $bankerThirdCardValue,
                bankerThirdCardType: $bankerThirdCardType,
                bankerPoints: $bankerPoints
            ));

        app(BaccaratPayoutProcessingManagerAction::class)($baccaratGameId);

    }
}
