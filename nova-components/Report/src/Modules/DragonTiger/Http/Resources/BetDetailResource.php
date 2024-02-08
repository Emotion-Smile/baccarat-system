<?php

namespace KravanhEco\Report\Modules\DragonTiger\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BetDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $ticketFormat = $this->format();

        return [
            'id' => $this->id,
            'table' => $ticketFormat->table(),
            'game' => $ticketFormat->gameNumber(),
            'member' => $ticketFormat->member(),
            'date' => $this->created_at->format('Y-m-d H:i:s A'),
            'betAmount' => $ticketFormat->betAmountAsHtml(),
            'bet' => $ticketFormat->betAsHtml(),
            'result' => $this->format()->resultAsHtml(),
            'gameResult' => [
                'dragon' => $this->game->format()->dragonResultAsHtml(),
                'tiger' => $this->game->format()->tigerResultAsHtml()
            ],
            'winLose' => $ticketFormat->winLoseAsHtml(),
            'ip' => $ticketFormat->ipAsHtml(),
        ];
    }
}