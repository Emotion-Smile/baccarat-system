<?php

use App\Kravanh\Domain\Environment\Actions\GroupFindGroupIdAvailableAction;
use App\Kravanh\Domain\Environment\Actions\GroupGetAction;
use App\Kravanh\Domain\Environment\Actions\GroupGetGroupIdsAvailableAction;
use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Domain\UserOption\Actions\UserOptionCreateAction;
use App\Kravanh\Domain\UserOption\Support\Enum\Option;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

test('it can get group correctly', function () {
    $superSenior = User::factory(['type' => UserType::SUPER_SENIOR])->create();
    //set super senior use second trader
    app(UserOptionCreateAction::class)(userId: $superSenior->id, option: Option::USE_SECOND_TRADER);

    $userUseSecondTrader = User::factory(['super_senior' => $superSenior->id])->create();
    $userNormal = User::factory(['super_senior' => 1111])->create();

    Group::factory()->count(2)->create();
    Group::factory(['use_second_trader' => true])->count(3)->create();
    Group::factory(['use_second_trader' => true, 'active' => false])->count(1)->create();

    $userGroupUseSecondTrader = app(GroupGetAction::class)([], $userUseSecondTrader);
    $userGroupNormal = app(GroupGetAction::class)([], $userNormal);
    $userNormalNotIn = app(GroupGetAction::class)([Group::first()->id], $userNormal);
    $groupIdAvailable = app(GroupFindGroupIdAvailableAction::class)([Group::first()->id], $userNormal);
    $groupIdsAvailable = app(GroupGetGroupIdsAvailableAction::class)([], $userNormal);

    assertDatabaseCount('groups', 6);
    expect($userGroupUseSecondTrader->count())->toBe(3)
        ->and($userGroupNormal->count())->toBe(2)
        ->and($userNormalNotIn->count())->toBe(1)
        ->and($groupIdAvailable)->toBe(2)
        ->and(count($groupIdsAvailable))->toBe(2);


});
