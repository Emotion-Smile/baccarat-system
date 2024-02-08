<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Actions;

use App\Kravanh\Application\Admin\Support\UseNovaAction;
use App\Kravanh\Domain\DragonTiger\App\Nova\Forms\ConditionForm;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\IGameService;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class DragonTigerBetConditionNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use UseNovaAction;

    public $name = 'Dragon& Tiger Bet Condition';

    public $confirmButtonText = 'Save';

    public function __construct(public User $user)
    {
        $this->standalone();
        $this->onlyOnTableRow(); // Need to call it to prevent return empty resource
    }

    public function handle(
        ActionFields $fields,
        Collection $models
    ): array {

        try {

            /**
             * @var User $user
             */
            $user = $models->first();

            $this->dragonTigerSetTableConditionForUser(
                user: $user,
                fields: $fields->toArray()
            );

        } catch (Exception $exception) {
            return Action::message($exception->getMessage());
        }

        return Action::message("The table condition for user $user->name create successfully");
    }

    public function fields(): array
    {

        return ConditionForm::make($this->user)
            ->buildFields();
    }

    public function dragonTigerSetTableConditionForUser(
        User $user,
        array $fields
    ): void {

        $game = app(GameDragonTigerGetAction::class)();

        app(IGameService::class)->setTableConditionForUser(
            data: GameTableConditionData::fromBuild(
                gameId: $game->id,
                gameTableId: $game->firstTableId(),
                userId: $user->id,
                userType: $user->type->value,
                build: GameTableConditionData::build($fields)
            )
        );

    }

    public function inline(string $label, bool $canSee = true)
    {
        return static::toInlineButton(
            label: $label,
            resourceId: $this->user->id,
            resource: $this->user,
            canSee: $canSee
        );
    }
}
