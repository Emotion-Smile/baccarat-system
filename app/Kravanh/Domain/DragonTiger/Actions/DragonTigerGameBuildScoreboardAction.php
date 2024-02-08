<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use Illuminate\Database\Eloquent\Collection;

final class DragonTigerGameBuildScoreboardAction
{
    public function __construct(public Collection $gameResultHistory)
    {

    }

    public static function from(Collection $gameResultHistory): self
    {
        return new self($gameResultHistory);
    }

    public function toScoreboard(): array
    {
        $board = [];

        $column = 0;
        $lastGame = $this->gameResultHistory->last();

        foreach ($this->gameResultHistory as $game) {

            $game = $this->buildScoreItem($game->toArray());

            if (empty($board[$column])) {
                $board[$column][] = $game;

                continue;
            }

            $lastGameResultInColumn = $this->getWinnerOfLastGameInColumn($board[$column]);

            if ($lastGameResultInColumn !== $game['result']) {
                $board = $this->fillBlankResult($board, $column);
                //The current column full and move to next column
                $column++;
            }

            if (isset($board[$column]) && count($board[$column]) === 6) {
                $column++;
            }

            $board[$column][] = $game;
            //if it is the last result
            if ($lastGame['id'] === $game['id']) {
                $board = $this->fillBlankResult($board, $column);
            }

        }

        return $board;
    }

    public function toCount(): array
    {
        return $this->gameResultHistory->countBy('winner')->toArray();
    }

    private function buildScoreItem(array $game): array
    {
        return [
            'id' => $game['id'],
            'gameNumber' => $game['round'].'_'.$game['number'],
            'result' => $game['winner'],
        ];
    }

    private function getWinnerOfLastGameInColumn(array $data): string
    {
        return collect($data)->last()['result'];
    }

    private function fillBlankResult(array $board, int $column): array
    {
        $needRowsToFillInColumn = 6 - count($board[$column]);

        for ($i = 0; $i < $needRowsToFillInColumn; $i++) {
            $board[$column][] = [];
        }

        return $board;
    }
}
