<?php

namespace App\Kravanh\Domain\Match\Collections;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class MatchCollection extends Collection
{

    public function toSymbolResultV3()
    {
        $symbols = [];
        $matches = $this;
        $minFightNumber = $matches->min('fight_number');
        $stopSkip = false;

        $matches = $matches->filter(function (Matches $match) use ($minFightNumber, &$stopSkip) {

            if ($match->fight_number === $minFightNumber) {
                $stopSkip = true;
            }

            if ($stopSkip) {
                return $match;
            }

        })->values();


        $matchFilter = []; // 290 , 1, 2 ,3 remove bigger fight number
        foreach ($matches as $index => $match) {

            if ($index === 0) {
                $matchFilter[] = $match;
                continue;
            }

            $prevFightNumber = $matches[$index - 1]['fight_number'];

            if ($prevFightNumber > $match['fight_number']) {
                $matchFilter = [];
            }

            $matchFilter[] = $match;
        }

        $block = 0;

        foreach ($matchFilter as $match) {

            $maxBlock = 5;

            $totalSymbol = count($symbols);

            $result = Str::lower($match->result->description);
            $fightNumber = $match->fight_number;

            $data = [
                'result' => $result,
                'fightNumber' => $fightNumber
            ];

            $null = 'null';

            if (empty($symbols)) {
                $symbols[] = $data;
                $block++;
                continue;
            }

            $lastIndex = $totalSymbol - 1;

            if ($symbols[$lastIndex]['result'] === $result) {
                $symbols[] = $data;
                $block++;
                continue;
            }

            if (($symbols[$lastIndex]['result'] != $result)) {

                if (in_array($result, ['draw', 'cancel', 'pending'])) {
                    $symbols[] = $data;
                    $block++;
                    continue;

                } else {

                    $needNullBox = 0;

                    if ($block <= $maxBlock) {
                        $needNullBox = $maxBlock - $block;
                    }

                    if ($block > $maxBlock) {
                        $maxBlock = $maxBlock * ceil($block / $maxBlock);
                        $needNullBox = $maxBlock - $block;
                    }

                    for ($i = 0; $i < $needNullBox; $i++) {
                        $symbols[] = [
                            'result' => $null,
                            'fightNumber' => ''
                        ];
                    }

                    $block = 0;
                }
            }

            $symbols[] = $data;
            $block++;
        }

        return $symbols;

    }

    public function toSymbolResultV2(): array
    {
        $symbols = [];
        $matches = $this;
        $minFightNumber = $matches->min('fight_number');
        $stopSkip = false;

        $matches = $matches->filter(function (Matches $match) use ($minFightNumber, &$stopSkip) {

            if ($match->fight_number === $minFightNumber) {
                $stopSkip = true;
            }

            if ($stopSkip) {
                return $match;
            }

        })->values();


        $matchFilter = []; // 290 , 1, 2 ,3 remove bigger fight number
        foreach ($matches as $index => $match) {

            if ($index === 0) {
                $matchFilter[] = $match;
                continue;
            }

            $prevFightNumber = $matches[$index - 1]['fight_number'];

            if ($prevFightNumber > $match['fight_number']) {
                $matchFilter = [];
            }

            $matchFilter[] = $match;
        }

        $block = 0;

        foreach ($matchFilter as $match) {

            $maxBlock = 5;

            $totalSymbol = count($symbols);

            $result = Str::lower($match->result->description);
            $fightNumber = $match->fight_number;

            $data = [
                'result' => $result,
                'fightNumber' => $fightNumber
            ];

            $null = 'null';

            if (empty($symbols)) {
                $symbols[] = $data;
                $block++;
                continue;
            }

            $lastIndex = $totalSymbol - 1;

            if ($symbols[$lastIndex]['result'] === $result) {
                $symbols[] = $data;
                $block++;
                continue;
            }

            if (($symbols[$lastIndex]['result'] != $result)) {

                if (in_array($result, ['draw', 'cancel', 'pending'])) {
                    $symbols[] = $data;
                    $block++;
                    continue;

                } else {

                    $needNullBox = 0;

                    if ($block <= $maxBlock) {
                        $needNullBox = $maxBlock - $block;
                    }

                    if ($block > $maxBlock) {
                        $maxBlock = $maxBlock * ceil($block / $maxBlock);
                        $needNullBox = $maxBlock - $block;
                    }

                    for ($i = 0; $i < $needNullBox; $i++) {
                        $symbols[] = [
                            'result' => $null,
                            'fightNumber' => ''
                        ];
                    }

                    $block = 0;
                }
            }

            $symbols[] = $data;
            $block++;
        }

        return $symbols;
    }

    public function toResultCount(): array
    {
        $result = collect($this->toArray())->countBy('result')->toArray();
        return [
            'meron' => $result[MatchResult::MERON] ?? 0,
            'wala' => $result[MatchResult::WALA] ?? 0,
            'draw' => $result[MatchResult::DRAW] ?? 0,
            'cancel' => $result[MatchResult::CANCEL] ?? 0,
            'pending' => $result[MatchResult::PENDING] ?? 0
        ];
    }
}
