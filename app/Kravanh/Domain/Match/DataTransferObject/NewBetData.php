<?php

namespace App\Kravanh\Domain\Match\DataTransferObject;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Models\User;
use Illuminate\Http\Request;

class NewBetData
{
    public function __construct(
        public User     $user,
        public ?Matches $match,
        public int      $amount,
        public int      $betOn,
        public ?string  $ip = null,
    )
    {

    }

    public static function fromRequest(Request $request): NewBetData
    {
        return new NewBetData(
            user: $request->user(),
            match: Matches::live($request->user()),
            amount: $request->betAmount,
            betOn: $request->betOn,
            ip: $request->header('x-vapor-source-ip') ?? $request->ip());
    }
}
