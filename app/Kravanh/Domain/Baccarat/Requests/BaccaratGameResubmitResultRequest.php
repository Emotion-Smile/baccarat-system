<?php

namespace App\Kravanh\Domain\Baccarat\Requests;

class BaccaratGameResubmitResultRequest extends BaccaratGameSubmitResultRequest
{
    public function rules(): array
    {

        return array_merge(
            parent::rules(),
            [
                'gameId' => 'required',
            ]
        );
    }

    public function getGameId()
    {
        return $this->get('gameId');
    }
}
