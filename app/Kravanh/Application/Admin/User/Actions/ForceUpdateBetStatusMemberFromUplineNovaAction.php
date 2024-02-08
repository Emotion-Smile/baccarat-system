<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Application\Admin\Support\UseNovaAction;
use App\Kravanh\Domain\User\Actions\ForceUpdateBetStatusMemberFromUplineAction;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use SimpleSquid\Nova\Fields\Enum\Enum;

class ForceUpdateBetStatusMemberFromUplineNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use UseNovaAction;

    public $name = 'Force Update Bet Status Member From Upline';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            (new ForceUpdateBetStatusMemberFromUplineAction)($user, $fields->status);
        }
    }

    public function fields(): array
    {
        return [
            Enum::make(__('Status'))
                ->stacked()
                ->attach(Status::class)
                ->default(Status::OPEN),
        ];
    }

    public static function build()
    {
        return static::make()
            ->canRun(fn() => user()->isCompany())
            ->onlyOnTableRow();
    }
}
