<?php

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;

test('it can build dragon tiger game result submitted correctly', function () {

    $game = DragonTigerGame::factory([
        'winner' => 'dragon',
        'dragon_color' => 'red',
        'dragon_range' => 'small',
        'dragon_result' => 6,
        'dragon_type' => DragonTigerCard::Diamond,
        'tiger_color' => 'black',
        'tiger_range' => 'small',
        'tiger_result' => 5,
        'tiger_type' => DragonTigerCard::Club
    ])->create();

    $data = DragonTigerGameStateData::from(game: $game);

    expect($data->mainResult)->toBe('dragon')
        ->and($data->gameNumber)->toBe($game->gameNumber())
        ->and($data->bettingInterval)->toBe($game->bettingInterval())
        ->and($data->betStatus)->toBe('close')
        ->and($data->subResult)->toBe('dragon_red,dragon_small,tiger_black,tiger_small')
        ->and($data->dragonResult)->toBe(6)
        ->and($data->dragonType)->toBe(DragonTigerCard::Diamond)
        ->and($data->tigerResult)->toBe(5)
        ->and($data->tigerType)->toBe(DragonTigerCard::Club);

});

test('it can build dragon tiger game result on live game', function () {

    $game = DragonTigerGame::factory()->liveGame()->create();

    $data = DragonTigerGameStateData::from(game: $game);

    expect($data->mainResult)->toBeEmpty()
        ->and($data->gameNumber)->toBe($game->gameNumber())
        ->and($data->betStatus)->toBe('open')
        ->and($data->bettingInterval)->toBe($game->bettingInterval())
        ->and($data->subResult)->toBeEmpty()
        ->and($data->dragonResult)->toBe(0)
        ->and($data->dragonType)->toBeEmpty()
        ->and($data->tigerResult)->toBe(0)
        ->and($data->tigerType)->toBeEmpty();
});

test('ensure default value', function () {
    $data = DragonTigerGameStateData::default(1);
    expect($data->tableId)->toBe(1)
        ->and($data->gameNumber)->toBe('#')
        ->and($data->betStatus)->toBe('close')
        ->and($data->bettingInterval)->toBe(0)
        ->and($data->mainResult)->toBeEmpty()
        ->and($data->subResult)->toBeEmpty()
        ->and($data->dragonResult)->toBe(0)
        ->and($data->dragonType)->toBeEmpty()
        ->and($data->tigerResult)->toBe(0)
        ->and($data->tigerType)->toBeEmpty();

});
