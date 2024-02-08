<?php

namespace App\Kravanh\Domain\Match\Supports;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Supports\Enums\GroupEnum;

final class GroupManager
{
    public Group $group;

    public function __construct(int $groupId)
    {
        $this->group = Group::findOrFail($groupId);
    }

    public function getRedLabel(): string
    {
        return $this->group->meron;
    }

    public function getBlueLabel(): string
    {
        return $this->group->wala;
    }

    public function getType(): string
    {
        if ($this->group->id === 3) {
            return GroupEnum::BOXING;
        }

        return GroupEnum::COCK_FIGHT;
    }


}
