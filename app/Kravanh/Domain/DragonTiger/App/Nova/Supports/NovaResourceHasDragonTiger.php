<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Supports;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\DragonTiger\App\Nova\Actions\DragonTigerBetConditionNovaAction;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetParentTableConditionAction;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetSuperSeniorTableConditionAction;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetTableConditionAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\ActionRequest;

/**
 * @mixin Resource
 */
trait NovaResourceHasDragonTiger
{

    public ?GameTableConditionData $dragonTigerSuperSeniorCondition = null;
    public ?GameTableConditionData $dragonTigerParentCondition = null;
    public ?User $selectedUser = null;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->dragonTigerSuperSeniorCondition = app(GameDragonTigerGetSuperSeniorTableConditionAction::class)(userId: $resource->id ?? 0);
        $this->dragonTigerParentCondition = app(GameDragonTigerGetParentTableConditionAction::class)(userId: $resource->id ?? 0);

    }

    public function allowDragonTiger(bool $canSee = false): bool
    {

//        if ($this->isEmptyUser()) {
//            return true;
//        }
        if ($this->isCompany()) {
            return true;
        }

        if (!$canSee) {
            return false;
        }

        if (is_null($this->dragonTigerParentCondition)) {
            return false;
        }

        if ($this->dragonTigerParentCondition->isNotYetSetCondition()) {
            return false;
        }


        return $this->dragonTigerSuperSeniorCondition?->isAllowed ?? false;
    }

    public function isEmptyUser(): bool
    {
        return is_null($this->model()->id);
    }

    public function getSelectedUser(Request $request): User
    {
        $this->selectedUser = $this->model();

        if ($request instanceof ActionRequest) {
            $this->selectedUser = \App\Models\User::find($request->get('resources'));
        }

        return $this->selectedUser;
    }


    public function dragonTigerInlineButton(string $label = 'D&T')
    {
        return DragonTigerBetConditionNovaAction::make($this->model())
            ->inline(
                label: $label,
                canSee: $this->allowDragonTiger()
            )
            ->buttonColor($this->isUserHasDragonTigerCondition() ? '' : 'black');
    }

    public function dragonTigerActionButton(Request $request)
    {
        return DragonTigerBetConditionNovaAction::make(
            $this->getSelectedUser($request)
        )
            ->allowToRun(fn() => $this->allowDragonTiger());
    }

    public function isUserHasDragonTigerCondition(): bool
    {
        if ($this->isEmptyUser()) {
            return false;
        }

        if (!($this->dragonTigerSuperSeniorCondition?->isAllowed ?? false)) {
            return false;
        }

        $condition = app(GameDragonTigerGetTableConditionAction::class)(userId: $this->model()->id);

        if ($condition->isDefault()) {
            return false;
        }

        return true;
    }
}
