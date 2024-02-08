<?php

namespace App\Kravanh\Domain\Integration\DataTransferObject\AF88;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Laravel\Nova\Fields\ActionFields;

class UpdateGameConditionData
{
    public function __construct(
        public readonly int $userId,
        public readonly array $condition
    )
    {}

    public static function fromNovaAction(
        User $user,
        ActionFields $fields
    ): UpdateGameConditionData
    {
        $condition = [
            'minimum_single_bet' => $fields->minimum_single_bet,
            'maximum_single_bet' => $fields->maximum_single_bet,
            'match_limited_single_bet' => $fields->match_limited_single_bet,
            'minimum_mix_parlay_bet' => $fields->minimum_mix_parlay_bet,
            'maximum_mix_parlay_bet' => $fields->maximum_mix_parlay_bet,
            'maximum_payout_mix_parlay_bet' => $fields->maximum_payout_mix_parlay_bet,
            'suspend' => $fields->suspend,
            'lock' => $fields->lock
        ];

        if($user->type->isNot(UserType::MEMBER)) {
            $condition = [
                ...$condition,
                'commission_hdp_ou_oe_group_4' => $fields->commission_hdp_ou_oe_group_4,
                'commission_hdp_ou_oe_group_a' => $fields->commission_hdp_ou_oe_group_a,
                'commission_hdp_ou_oe_group_b' => $fields->commission_hdp_ou_oe_group_b,
                'commission_hdp_ou_oe_group_c' => $fields->commission_hdp_ou_oe_group_c,
                'commission_par_cs_tg_group_4' => $fields->commission_par_cs_tg_group_4,
                'commission_par_cs_tg_group_a' => $fields->commission_par_cs_tg_group_a,
                'commission_par_cs_tg_group_b' => $fields->commission_par_cs_tg_group_b,
                'commission_par_cs_tg_group_c' => $fields->commission_par_cs_tg_group_c,
            ];
        }

        if($user->type->is(UserType::MEMBER)) {
            $condition = [
                ...$condition,
                'win_limited_per_day' => $fields->win_limited_per_day,
                'commission_hdp_ou_oe_selected_group' => $fields->commission_hdp_ou_oe_selected_group,
                'commission_par_cs_tg_selected_group' => $fields->commission_par_cs_tg_selected_group,
            ];
        }

        return new static(
            userId: $user->id,
            condition: $condition
        );
    }
}