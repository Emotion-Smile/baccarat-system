<?php

namespace App\Kravanh\Application\Admin\Environment\Actions;

use App\Kravanh\Domain\Environment\Events\ReloadStreaming;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ReloadVideoStreaming extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Reload Video Streaming';

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $env) {
            
            ReloadStreaming::dispatch($env->id);
        }
    }

    public function authorizedToSee(Request $request): bool
    {
        return true;
    }
}
