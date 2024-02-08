<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;

class AllowVideoStreamingAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Allow Video Streaming';

    public $showOnTableRow = true;

    public function __construct(public bool $allowStream)
    {
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            $user->allow_stream = $fields['allow_stream'];
            $user->save();
        }
    }

    public function authorizedToSee(Request $request): bool
    {
        return allowIf('Member:allow-stream');
    }

    public function fields(): array
    {

        return [
            Boolean::make('Allow Stream')->default($this->allowStream)
        ];
    }

}
