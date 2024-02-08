<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Jobs\CloseBetJob;
use App\Kravanh\Domain\Match\Jobs\EndMatchJob;
use App\Kravanh\Domain\Match\Jobs\OpenBetJob;
use App\Kravanh\Domain\Match\Jobs\StartNewMatchJob;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

function setUpData()
{

    $group = Group::factory()->create();
    $trader = Trader::factory(['group_id' => $group->id])->create();
    $token = $trader->createToken('test_token')->plainTextToken;
    appSetSetting('f88_token_' . $group->id, $token);

    return $group;
}

function fakeHttp(string $route): void
{
    Http::fake([
        $route => Http::response
        (
            ['message' => 'hello'],
            Response::HTTP_OK
        ),
    ]);
}

function httpAssertSend(array $expect): void
{

    Http::assertSent(function (
        ClientRequest  $request,
        ClientResponse $response) use ($expect) {

        expect($expect)->toBe($request->data())
            ->and(Response::HTTP_OK)
            ->toBe($response->status());
        return true;
    });
}

test('it can call start new match', function () {
    $group = setUpData();
    fakeHttp(route('api.match.new'));
    StartNewMatchJob::dispatchIf(true,
        [
            'groupId' => $group->id,
            'totalPayout' => 190,
            'meronPayout' => 90,
            'fightNumber' => 1,
        ]
    );

    httpAssertSend([
        'totalPayout' => 190,
        'meronPayout' => 90,
        'fightNumber' => 1,
    ]);
});


test('it can call match submit result', function () {
    $group = setUpData();
    fakeHttp(route('api.match.submit-result'));

    EndMatchJob::dispatchIf(true,
        [
            'groupId' => $group->id,
            'result' => 1
        ]
    );

    httpAssertSend([
        'result' => 1
    ]);
});

test('it can call match toggle bet', function () {
    $group = setUpData();
    fakeHttp(route('api.match.toggle-bet'));

    OpenBetJob::dispatch(['groupId' => $group->id]);
    httpAssertSend(['betStatus' => true]);

    fakeHttp(route('api.match.toggle-bet'));
    CloseBetJob::dispatch(['groupId' => $group->id]);
    httpAssertSend(['betStatus' => false]);

});
