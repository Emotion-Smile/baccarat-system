<?php

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameCreateTicketRequest;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\User\Models\Member;

test('it cannot create ticket data if live game is not available', function () {

    DragonTigerGameMemberBetData::make(
        member: Member::factory()->create(),
        amount: 0,
        betOn: DragonTigerCard::Tiger,
        betType: DragonTigerCard::Tiger,
        ip: ''
    );

})->expectException(DragonTigerGameNoLiveGameException::class);


test('it create object from request', function () {

    $game = DragonTigerGame::factory()->liveGame()->create();
    $member = Member::factory(['group_id' => $game->game_table_id])->create();

    $request = mock(DragonTigerGameCreateTicketRequest::class);
    $request->shouldReceive('user')->andReturn($member);
    $request->shouldReceive('get')->with('amount')->andReturn(100);
    $request->shouldReceive('get')->with('betOn')->andReturn('tiger');
    $request->shouldReceive('get')->with('betType')->andReturn('tiger');
    $request->shouldReceive('ip')->andReturn('');

    $data = DragonTigerGameMemberBetData::fromRequest($request);

    expect($data->game->id)->toBe($game->id)
        ->and($data->member)->toBe($member)
        ->and($data->amount)->toBe(100)
        ->and($data->betOn)->toBe('tiger')
        ->and($data->betType)->toBe('tiger')
        ->and($data->ip)->toBe('');

});
