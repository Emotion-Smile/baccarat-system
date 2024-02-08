<?php

namespace App\Kravanh\Domain\Baccarat\Commands;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameHasLiveGameException;
use App\Kravanh\Domain\User\Models\Trader;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class BaccaratGameCreateNewCommand extends Command
{
    protected $signature = 'local:dt:create-new-game';
    protected $description = 'create new baccarat game';

    /**
     * @throws BaccaratGameHasLiveGameException|Throwable
     */
    public function handle(): void
    {
        try {
            sleep(10);
            $this->call('local:dt:submit-result');
            sleep(10);
        } catch (Exception $exception) {

        }

        (new BaccaratGameCreateManagerAction())(
            data: BaccaratGameCreateData::from(
                user: Trader::whereName('dt_seeder')->first()
            )
        );

    }
}
