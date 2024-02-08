<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Collections\MatchCollection;
use App\Kravanh\Domain\Match\Models\Matches;

test("it can generate next fight number even today and yesterday match does not have any match", function () {

    $group = Group::factory()->create();
    $nextFightNumber = Matches::nextFightNumber($group->id);
    expect($nextFightNumber)->toBe(1);

});


test("it can generate next fight number from today", function () {
    $group = Group::factory()->create();
    Matches::factory([
        'group_id' => $group->id,
        'fight_number' => 10
    ])->create();

    $nextFightNumber = Matches::nextFightNumber($group->id);
    expect($nextFightNumber)->toBe(11);
});

test('it can generate next fight number from yesterday', function () {

    $group = Group::factory()->create();

    Matches::factory([
        'group_id' => $group->id,
        'fight_number' => 200,
        'match_date' => \Illuminate\Support\Facades\Date::yesterday()->toDateString()
    ])->create();

    $nextFightNumber = Matches::nextFightNumber($group->id);
    expect($nextFightNumber)->toBe(201);

});

test('it can generate next fight number from today within yesterday match exist', function () {

    $group = Group::factory()->create();

    Matches::factory([
        'group_id' => $group->id,
        'fight_number' => 30,
    ])->create();

    Matches::factory([
        'group_id' => $group->id,
        'fight_number' => 200,
        'match_date' => \Illuminate\Support\Facades\Date::yesterday()->toDateString()
    ])->create();

    $nextFightNumber = Matches::nextFightNumber($group->id);

    expect($nextFightNumber)->toBe(31)
        ->and(Matches::count())->toBe(2);

});


test('it can get match result today', function () {

    $group = Group::factory()->create();
    $matchTodayResults = Matches::todayMatchResult($group->id);

    expect($matchTodayResults->count())->toBe(0)
        ->and($matchTodayResults)->toBeInstanceOf(MatchCollection::class);

});
