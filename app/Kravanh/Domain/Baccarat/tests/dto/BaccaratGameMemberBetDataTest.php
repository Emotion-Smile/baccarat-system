<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Requests\BaccaratGameCreateTicketRequest;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\User\Models\Member;

test('it cannot create ticket data if live game is not available', function () {

    BaccaratGameMemberBetData::make(
        member: Member::factory()->create(),
        amount: 0,
        betOn: BaccaratCard::Tiger,
        betType: BaccaratCard::Tiger,
        ip: ''
    );

})->expectException(BaccaratGameNoLiveGameException::class);


test('it create object from request', function () {

    $game = BaccaratGame::factory()->liveGame()->create();
    $member = Member::factory(['group_id' => $game->game_table_id])->create();

    $request = mock(BaccaratGameCreateTicketRequest::class);
    $request->shouldReceive('user')->andReturn($member);
    $request->shouldReceive('get')->with('amount')->andReturn(100);
    $request->shouldReceive('get')->with('betOn')->andReturn('tiger');
    $request->shouldReceive('get')->with('betType')->andReturn('tiger');
    $request->shouldReceive('ip')->andReturn('');

    $data = BaccaratGameMemberBetData::fromRequest($request);

    expect($data->game->id)->toBe($game->id)
        ->and($data->member)->toBe($member)
        ->and($data->amount)->toBe(100)
        ->and($data->betOn)->toBe('tiger')
        ->and($data->betType)->toBe('tiger')
        ->and($data->ip)->toBe('');

});
