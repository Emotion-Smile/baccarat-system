<?php

namespace App\Kravanh\Application\Trader\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchRequest extends FormRequest
{
    public function rules(): array
    {
        $request = $this;
        $maxPayout = 150;
        $minPayout = 65;

        return [
            'totalPayout' => 'required|numeric',
            'meronPayout' => [
                'required',
                'numeric',
                function ($attribute, $meronPayout, $fail) use ($request, $maxPayout, $minPayout) {
                    $walaPayout = ($request->totalPayout - $meronPayout);
                    if (
                        ($meronPayout > $maxPayout) || ($walaPayout > $maxPayout)
                    ) {
                        $fail("Invalid payout value meron: {$meronPayout} vs wala: {$walaPayout}");
                    }

                    if (($meronPayout < $minPayout) || ($walaPayout < $minPayout)) {
                        $fail("Min payout value cannot be lower than 65: {$meronPayout} vs wala: {$walaPayout}");
                    }

                }
            ],
            'fightNumber' => 'nullable|numeric'
        ];
    }
}
