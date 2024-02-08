<?php

namespace Kravanh\BetCondition;

use App\Models\User;
use Laravel\Nova\Fields\Field;

class BetCondition extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'bet-condition';


    /**
     * @param $user // user object
     * @return $this
     */
    public function user($user): static
    {
        if ($user?->type?->value) {
            $parentId = match ($user->type->value) {
                'member' => $user->agent,
                'agent' => $user->master_agent,
                'master_agent' => $user->senior
            };
            /**
             * @var User $user
             */
            $this->meta['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'type' => $user->type,
                'currency' => $user->currency,
                'agent' => $user->agent,
                'masterAgent' => $user->master_agent,
                'senior' => $user->senior,
                'superSenior' => $user->super_senior,
                'condition' => $user->condition,
                'parentId' => $parentId
            ];
        }


        return $this;
    }

    public function actionUser($actionUser): static
    {
        $this->meta['actionUser'] = [
            'id' => $actionUser->id,
            'name' => $actionUser->name,
            'type' => $actionUser->type,
            'currency' => $actionUser->currency,
            'agent' => $actionUser->agent,
            'masterAgent' => $actionUser->master_agent,
            'senior' => $actionUser->senior,
            'superSenior' => $actionUser->super_senior,
            'condition' => $actionUser->condition
        ];

        return $this;
    }


}
