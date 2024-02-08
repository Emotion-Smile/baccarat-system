<?php

namespace App\Kravanh\Domain\Environment\Actions;

class GroupFindGroupIdAvailableAction
{
    public function __invoke(array $disableGroupId, $user)
    {
        return app(GroupGetAction::class)($disableGroupId, $user)->first()?->id;
    }
}
