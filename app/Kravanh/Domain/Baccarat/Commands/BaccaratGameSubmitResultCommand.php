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
                            {--playerResult=8 : The result of card range from 0 to 9}
                            {--playerType=club : The card type club,heart,diamond,spade}
                            {--bankerResult=5 : The result of card range from 0 to 9}
                            {--bankerType=heart : The card type club,heart,diamond,spade}
                            ';
    protected $description = 'php artisan local:dt:submit-result --playerResult=1 --playerType=heart --bankerResult=5 --bankerType=club';


    /**
     * @throws BaccaratGameNoLiveGameException
     * @throws Throwable
     */
    public function handle(): void
    {
        $result = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $type = ['heart', 'diamond', 'spade', 'club'];

        $playerResult = Arr::random($result);
        $playerType = Arr::random($type);

        $bankerResult = Arr::random($result);
        $bankerType = Arr::random($type);

        $trader = Trader::whereName('dt_seeder')->first();

        $baccaratGameId = (new BaccaratGameGetLiveGameIdByTableAction())(
            gameTableId: $trader->getGameTableId()
        );

        (new BaccaratGameSubmitResultManagerAction())(
            data: BaccaratGameSubmitResultData::make(
                user: $trader,
                baccaratGameId: $baccaratGameId,
                playerResult: $playerResult,//$this->option('playerResult'),
                playerType: $playerType,//$this->option('playerType'),
                bankerResult: $bankerResult,//$this->option('bankerResult'),
                bankerType: $bankerType//$this->option('bankerType'))
            ));

        app(BaccaratPayoutProcessingManagerAction::class)($baccaratGameId);

    }
}
