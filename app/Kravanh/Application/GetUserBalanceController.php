<?php

namespace App\Kravanh\Application;

use App\Kravanh\Domain\User\Supports\Enums\UserType;

final class GetUserBalanceController
{
    public function __invoke(int $id, string $type): array
    {
        if ($type === UserType::SUB_ACCOUNT) {
            return [
                'balance' => 0
            ];
        }

        $model = getModelByUserType($type);
        $userCast = $model::find($id);

        return [
            'balance' => priceFormat($userCast->getCurrentBalance(), $userCast->currency)
        ];
    }
}
