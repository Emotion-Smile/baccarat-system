<?php

namespace App\Kravanh\Domain\DragonTiger\Requests;

class DragonTigerGameResubmitResultRequest extends DragonTigerGameSubmitResultRequest
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
