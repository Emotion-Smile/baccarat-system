<?php

namespace App\Kravanh\Domain\Integration\Nova\Actions;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\Supports\Nova\AF88GameConditionNovaActionFields;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\App;

class AF88GameConditionNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'AF88 Game Condition';
    
    public $confirmButtonText = 'Save';

    public function __construct(
        protected User $user
    ) {}

    public function handle(
        ActionFields $fields, 
        Collection $models
    )
    {
        foreach ($models as $user) {
            try {
                App::make(AF88Contract::class)->save($user, $fields);

                return Action::message('Save successfully.');
            } catch (Exception $exception) {
                return Action::danger($exception->getMessage());
            }
        }
    }

    public function fields(): array
    {
        try {
            return (new AF88GameConditionNovaActionFields($this->user))();
        } catch (Exception $exception) {
            return [];
        }
    }
}