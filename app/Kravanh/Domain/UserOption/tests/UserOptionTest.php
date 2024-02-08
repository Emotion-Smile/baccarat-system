<?php

use App\Kravanh\Domain\UserOption\Actions\UserOptionCreateAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionDeleteAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionFindAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionHasUseSecondTraderAction;
use App\Kravanh\Domain\UserOption\Models\UserOption;
use App\Kravanh\Domain\UserOption\Support\Enum\Option;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

test('it can create option', function () {
    $user = User::factory()->create();
    $option = resolve(UserOptionCreateAction::class)($user->id, Option::USE_SECOND_TRADER);
    expect($option)->toBeInstanceOf(UserOption::class);
    assertDatabaseCount('user_options', 1);
});

test('it return null if no option belong to user', function () {
    $user = User::factory()->create();

    $option = resolve(UserOptionFindAction::class)(
        userId: $user->id,
        option: Option::USE_SECOND_TRADER
    );

    expect($option)->toBeNull();

});

test('it can find option belong to user', closure: function () {
    $user = User::factory()->create();
    resolve(UserOptionCreateAction::class)($user->id, Option::USE_SECOND_TRADER);
    $option = resolve(UserOptionFindAction::class)($user->id, Option::USE_SECOND_TRADER);
    expect($option)->toBeInstanceOf(UserOption::class)
        ->and($option->value)->toBeNull()
        ->and(resolve(UserOptionHasUseSecondTraderAction::class)($user->id))->toBeTrue();
});

test('it can delete user option', function () {
    $user = User::factory()->create();
    resolve(UserOptionCreateAction::class)($user->id, Option::USE_SECOND_TRADER);
    $isDeleted = resolve(UserOptionDeleteAction::class)($user->id, Option::USE_SECOND_TRADER);
    expect($isDeleted)->toBeTrue();
    assertDatabaseCount('user_options', 0);
});
