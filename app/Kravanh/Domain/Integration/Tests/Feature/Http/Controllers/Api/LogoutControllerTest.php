<?php

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->baseUrl = 'api/integration/logout';
});

it(
    'make sure user unauthenticated if user not login yet.', 
    function () {
        postJson($this->baseUrl)
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
);

it(
    'make sure user current access token has been deleted.', 
    function () {
        
    }
)
    ->skip();