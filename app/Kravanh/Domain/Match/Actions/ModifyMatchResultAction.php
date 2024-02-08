<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Events\MatchResultUpdated;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;

final class ModifyMatchResultAction
{
    public function __invoke(
        Matches $match,
        int     $result,
        string  $note
    ): int
    {


        if (!$match->isMatchEnded()) {
            return 0;
        }


        if (
            $this->isPrevResultCancelOrDraw($match) &&
            $this->isModifyResultToCancelOrDraw($result)
        ) {

            $this->updateMatchResult($match, $result);

            return 0;
        }


        $rollbackTransactionCount = app(RollbackPayoutAction::class)(
            matchId: $match->id,
            note: $note
        );


        $this->updateMatchResult($match, $result);

        MatchResultUpdated::dispatch([
            'id' => $match->id
        ]);

        return $rollbackTransactionCount;

    }

    private function isPrevResultCancelOrDraw(Matches $match): bool
    {
        return in_array($match->result->value, [MatchResult::CANCEL, MatchResult::DRAW]);
    }

    private function isModifyResultToCancelOrDraw(string $result): bool
    {
        return in_array($result, [MatchResult::CANCEL, MatchResult::DRAW]);
    }


    private function updateMatchResult(
        Matches $match,
        int     $result
    ): void
    {
        $match->result = $result;
        $match->saveQuietly();
    }
}
