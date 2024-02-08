<?php

namespace App\Kravanh\Domain\Environment\Actions;

use Illuminate\Support\Collection;

class GroupGetGroupIdsAvailableAction
{
    public function __invoke(array $disableGroupId, $user): array
    {
        /**
         * @var Collection $groups
         */
        $groups = app(GroupGetAction::class)($disableGroupId, $user);

        if ($groups->isEmpty()) {
            return [];
        }

        return $groups->pluck('id')->toArray();
    }
}
