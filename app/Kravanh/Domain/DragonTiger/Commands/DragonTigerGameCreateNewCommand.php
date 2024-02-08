<?php

namespace App\Kravanh\Domain\DragonTiger\Commands;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameHasLiveGameException;
use App\Kravanh\Domain\User\Models\Trader;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class DragonTigerGameCreateNewCommand extends Command
{
    protected $signature = 'local:dt:create-new-game';
    protected $description = 'create new dragon tiger game';

    /**
     * @throws DragonTigerGameHasLiveGameException|Throwable
     */
    public function handle(): void
    {
        try {
            sleep(10);
            $this->call('local:dt:submit-result');
            sleep(10);
        } catch (Exception $exception) {

        }

        (new DragonTigerGameCreateManagerAction())(
            data: DragonTigerGameCreateData::from(
                user: Trader::whereName('dt_seeder')->first()
            )
        );

    }
}
