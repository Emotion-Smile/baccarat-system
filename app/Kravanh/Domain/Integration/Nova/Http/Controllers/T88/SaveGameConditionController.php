<?php

namespace App\Kravanh\Domain\Integration\Nova\Http\Controllers\T88;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use Exception;
use App\Kravanh\Domain\Integration\Nova\Http\Requests\T88\SaveGameConditionRequest;
use Laravel\Nova\Actions\Action;

class SaveGameConditionController
{
    public function __invoke(SaveGameConditionRequest $request): array
    {
        try {
            app(T88Contract::class)->createOrUpdateUser($request);
            
            return Action::message('Save successfully.');
        } catch (Exception $exception) {
            throw $exception;
            // return Action::danger($exception->getMessage());
        }
    }
}