<?php

namespace App\Kravanh\Domain\User\Supports\Traits;

use App\Kravanh\Domain\BetCondition\Actions\GetBetConditionAction;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;

/**
 * @mixin User
 */
trait HasAttribute
{
    public function getMaximumBetPerTicketAttribute(): string
    {
        if (!$this->type) {
            return '';
        }

        if (!$this->type->is(UserType::MEMBER)) {
            return '';
        }

        $condition = app(GetBetConditionAction::class)(
            groupId: $this->group_id ?? 0,
            memberId: $this->id,
            parentId: $this->agent ?? 0);


        return priceFormat($condition->maxBetPerTicket, '');
    }

    public function getMinimumBetPerTicketAttribute(): string
    {

        if (!$this->type) {
            return '';
        }

        if (!$this->type->is(UserType::MEMBER)) {
            return '';
        }

        $condition = app(GetBetConditionAction::class)(
            groupId: $this->group_id ?? 0,
            memberId: $this->id,
            parentId: $this->agent ?? 0);

        return priceFormat($condition->minBetPerTicket, '');

    }


    public function getNormalMemberAttribute(): bool
    {
        return $this->isNormalMember();
    }

}
