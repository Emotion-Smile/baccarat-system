<?php

namespace App\Kravanh\Domain\DragonTiger\Commands;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Throwable;

class DragonTigerGameSubmitResultCommand extends Command
{
    protected $signature = 'local:dt:submit-result
                            {--dragonResult=10 : The result of card range from 1 to 13}
                            {--dragonType=club : The card type club,heart,diamond,spade}
                            {--tigerResult=12 : The result of card range from 1 to 13}
                            {--tigerType=heart : The card type club,heart,diamond,spade}
                            ';
    protected $description = 'php artisan local:dt:submit-result --dragonResult=1 --dragonType=heart --tigerResult=5 --tigerType=club';


    /**
     * @throws DragonTigerGameNoLiveGameException
     * @throws Throwable
     */
    public function handle(): void
    {
        $result = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
        $type = ['heart', 'diamond', 'spade', 'club'];

        $dragonResult = Arr::random($result);
        $dragonType = Arr::random($type);

        $tigerResult = Arr::random($result);
        $tigerType = Arr::random($type);

        $trader = Trader::whereName('dt_seeder')->first();

        $dragonTigerGameId = (new DragonTigerGameGetLiveGameIdByTableAction())(
            gameTableId: $trader->getGameTableId()
        );

        (new DragonTigerGameSubmitResultManagerAction())(
            data: DragonTigerGameSubmitResultData::make(
                user: $trader,
                dragonTigerGameId: $dragonTigerGameId,
                dragonResult: $dragonResult,//$this->option('dragonResult'),
                dragonType: $dragonType,//$this->option('dragonType'),
                tigerResult: $tigerResult,//$this->option('tigerResult'),
                tigerType: $tigerType//$this->option('tigerType'))
            ));

        app(DragonTigerPayoutProcessingManagerAction::class)($dragonTigerGameId);

    }
}
