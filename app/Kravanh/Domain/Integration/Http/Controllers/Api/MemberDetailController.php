<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use App\Kravanh\Domain\Integration\Http\Resources\Api\MemberDetailResource;
use App\Kravanh\Domain\Match\Exceptions\NotMemberAccount;
use App\Kravanh\Domain\User\Models\Member;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Throwable;

class MemberDetailController
{
    public function __invoke(
        Request $request
    ): JsonResource|JsonResponse
    {
        try {
            $user = $request->user();

            return new MemberDetailResource(
                Member::find($user->id)
            );
        } catch ( 
            NotMemberAccount
            | Throwable
            | Exception $exception
        ) {
            return redirectError(__($exception->getMessage()));
        }
    }
} 