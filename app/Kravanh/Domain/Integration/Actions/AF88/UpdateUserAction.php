<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\DataTransferObject\AF88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Exception;

class UpdateUserAction
{
    use HasApi;

    public function __invoke(
        string $token,
        User $user,
        UpdateGameConditionData $updateGameConditionData
    ): void
    {
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->put(
                url: $this->requestUrl("/update/user/{$user->name}"),
                data: $this->requestBody(
                    user: $user, 
                    updateGameConditionData: $updateGameConditionData
                )   
            );

        if($response->failed()) {
            throw new Exception($response->object()?->message);
        }
    }

    protected function requestBody( 
        User $user,
        UpdateGameConditionData $updateGameConditionData
    ): array
    {
        $condition = $updateGameConditionData->condition;

        $requestBody = [
            'minimum_single_bet' => $condition['minimum_single_bet'],
            'maximum_single_bet' => $condition['maximum_single_bet'],
            'match_limited_single_bet' => $condition['match_limited_single_bet'],
            'minimum_mix_parlay_bet' => $condition['minimum_mix_parlay_bet'],
            'maximum_mix_parlay_bet' => $condition['maximum_mix_parlay_bet'],
            'maximum_payout_mix_parlay_bet' => $condition['maximum_payout_mix_parlay_bet'],
            'suspend' => $condition['suspend'],
            'lock' => $condition['lock'],
        ];

        if($user->type->isNot(UserType::MEMBER)) {
            $requestBody = [
                ...$requestBody,
                'commission_hdp_ou_oe_group_4' => $condition['commission_hdp_ou_oe_group_4'],
                'commission_hdp_ou_oe_group_a' => $condition['commission_hdp_ou_oe_group_a'],
                'commission_hdp_ou_oe_group_b' => $condition['commission_hdp_ou_oe_group_b'],
                'commission_hdp_ou_oe_group_c' => $condition['commission_hdp_ou_oe_group_c'],
                'commission_par_cs_tg_group_4' => $condition['commission_par_cs_tg_group_4'],
                'commission_par_cs_tg_group_a' => $condition['commission_par_cs_tg_group_a'],
                'commission_par_cs_tg_group_b' => $condition['commission_par_cs_tg_group_b'],
                'commission_par_cs_tg_group_c' => $condition['commission_par_cs_tg_group_c']
            ];
        }

        if($user->type->is(UserType::MEMBER)) {
            $requestBody = [
                ...$requestBody,
                'win_limited_per_day' => $condition['win_limited_per_day'],
                'commission_hdp_ou_oe_selected_group' => $condition['commission_hdp_ou_oe_selected_group'],
                'commission_par_cs_tg_selected_group' => $condition['commission_par_cs_tg_selected_group'],
            ];
        }

        return $requestBody;
    }
}