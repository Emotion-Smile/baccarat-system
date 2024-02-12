<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;

test('it can build baccarat game result submitted correctly', function () {

    $game = BaccaratGame::factory([
        'winner' => ['player', 'big'],
        'player_first_card_value' => 2,
        'player_first_card_type' => BaccaratCard::Heart,
        'player_first_card_color' => 'red',
        'player_first_card_points' => 2,
        'player_second_card_value' => 2,
        'player_second_card_type' => BaccaratCard::Diamond,
        'player_second_card_color' => 'red',
        'player_second_card_points' => 2,
        'player_third_card_value' => 3,
        'player_third_card_type' => BaccaratCard::Diamond,
        'player_third_card_color' => 'red',
        'player_third_card_points' => 3,
        'player_total_points' => 7,
        'player_points' => 7,
        'banker_first_card_value' => 1,
        'banker_first_card_type' => BaccaratCard::Club,
        'banker_first_card_color' => 'black',
        'banker_first_card_points' => 1,
        'banker_second_card_value' => 2,
        'banker_second_card_type' => BaccaratCard::Spade,
        'banker_second_card_color' => 'black',
        'banker_second_card_points' => 2,
        'banker_third_card_value' => 2,
        'banker_third_card_type' => BaccaratCard::Diamond,
        'banker_third_card_color' => 'red',
        'banker_third_card_points' => 2,
        'banker_total_points' => 5,
        'banker_points' => 5,
    ])->create();

    $data = BaccaratGameStateData::from(game: $game);

    expect($data->mainResult)->toBe('player')
        ->and($data->gameNumber)->toBe($game->gameNumber())
        ->and($data->bettingInterval)->toBe($game->bettingInterval())
        ->and($data->betStatus)->toBe('close')
        ->and($data->subResult)->toBe('player,big')
//        ->and($data->dragonResult)->toBe(6)
//        ->and($data->dragonType)->toBe(BaccaratCard::Diamond)
//        ->and($data->tigerResult)->toBe(5)
//        ->and($data->tigerType)->toBe(BaccaratCard::Club);
        ->and($data->playerPoints)->toBe(7)
        ->and($data->bankerPoints)->toBe(5);

});

//test('it can build dragon tiger game result on live game', function () {
//
//    $game = BaccaratGame::factory()->liveGame()->create();
//
//    $data = BaccaratGameStateData::from(game: $game);
//
//    expect($data->mainResult)->toBeEmpty()
//        ->and($data->gameNumber)->toBe($game->gameNumber())
//        ->and($data->betStatus)->toBe('open')
//        ->and($data->bettingInterval)->toBe($game->bettingInterval())
//        ->and($data->subResult)->toBeEmpty()
//        ->and($data->dragonResult)->toBe(0)
//        ->and($data->dragonType)->toBeEmpty()
//        ->and($data->tigerResult)->toBe(0)
//        ->and($data->tigerType)->toBeEmpty();
//});
//
//test('ensure default value', function () {
//    $data = BaccaratGameStateData::default(1);
//    expect($data->tableId)->toBe(1)
//        ->and($data->gameNumber)->toBe('#')
//        ->and($data->betStatus)->toBe('close')
//        ->and($data->bettingInterval)->toBe(0)
//        ->and($data->mainResult)->toBeEmpty()
//        ->and($data->subResult)->toBeEmpty()
//        ->and($data->dragonResult)->toBe(0)
//        ->and($data->dragonType)->toBeEmpty()
//        ->and($data->tigerResult)->toBe(0)
//        ->and($data->tigerType)->toBeEmpty();
//
//});
