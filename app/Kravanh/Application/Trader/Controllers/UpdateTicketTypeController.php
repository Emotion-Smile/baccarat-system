<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateTicketTypeController
{
    public function __invoke(Request $request): RedirectResponse
    {

        $request->validate([
            'id' => ['required', 'numeric'],
            'betType' => ['required', new EnumValue(BetType::class)]
        ]);

        $record = BetRecord::find($request->get('id'));
        
        $record->user->bet_type = $request->get('betType');
        $record->user->saveQuietly();

        $record->type = $request->get('betType');
        $record->saveQuietly();

        return redirectWith([
            'message' => "Ticket type updated"
        ]);
    }
}
