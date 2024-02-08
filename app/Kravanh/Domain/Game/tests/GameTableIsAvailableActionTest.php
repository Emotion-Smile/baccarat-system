<?php

use App\Kravanh\Domain\Game\Actions\GameTableIsAvailableAction;
use App\Kravanh\Domain\Game\Models\GameTable;

it('checks if game table is available when active', function () {
    // Given an active game table
    $gameTable = GameTable::factory()->create(['active' => true]);

    // When we check if it's available
    $isAvailable = app(GameTableIsAvailableAction::class)($gameTable->id);

    // Then it should be available
    expect($isAvailable)->toBeTrue();
});

it('checks if game table is not available when inactive', function () {
    // Given an inactive game table
    $gameTable = GameTable::factory()->create(['active' => false]);

    // When we check if it's available
    $isAvailable = app(GameTableIsAvailableAction::class)($gameTable->id);

    // Then it should not be available
    expect($isAvailable)->toBeFalse();
});

it('checks if game table is not available when non existent', function () {
    // Given a non-existent game table id
    $nonExistentId = 9999;

    // When we check if it's available
    $isAvailable = app(GameTableIsAvailableAction::class)($nonExistentId);

    // Then it should not be available
    expect($isAvailable)->toBeFalse();
});
