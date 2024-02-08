<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\GroupUser\Actions\GroupGetIdsBelongToUser;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function addUserToGroup($group, $user): void
{
    DB::table('group_user')->insert([
        'group_id' => $group->id,
        'user_id' => $user->id
    ]);
}

test('it can get group ids belong to user', function () {

    $agent = User::factory(['type' => UserType::AGENT])->create();
    $user = User::factory(['agent' => $agent->id])->create();

    $agentGroup = Group::factory()->count(3)->create();
    $memberGroup = Group::factory()->count(3)->create();

    $agentGroup->each(fn($group) => addUserToGroup($group, $agent));
    $memberGroup->each(fn($group) => addUserToGroup($group, $user));

    $disabledGroups = (new GroupGetIdsBelongToUser())($user);

    expect($disabledGroups)->toMatchArray([1, 2, 3, 4, 5, 6]);

});
