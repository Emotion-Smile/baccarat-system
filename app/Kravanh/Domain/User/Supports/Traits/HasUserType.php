<?php

namespace App\Kravanh\Domain\User\Supports\Traits;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin User
 */
trait HasUserType
{
    public function isUserPresent(): bool
    {
        return isset($this->id) && $this->id !== 0;
    }

    public function isCompany(): bool
    {
        return (bool) $this->type?->is(UserType::COMPANY);
    }

    public function isSuperSenior(): bool
    {
        return $this->type?->is(UserType::SUPER_SENIOR) ?? false;
    }

    public function isSenior(): bool
    {
        return (bool) $this->type?->is(UserType::SENIOR);
    }

    public function isMasterAgent(): bool
    {
        return (bool) $this->type?->is(UserType::MASTER_AGENT);
    }

    public function isAgent(): bool
    {
        return (bool) $this->type?->is(UserType::AGENT);
    }

    public function isMember(): bool
    {
        return (bool) $this->type?->is(UserType::MEMBER);
    }

    public function isTrader(): bool
    {
        return (bool) $this->type?->is(UserType::TRADER);
    }

    public function isTraderCockfight(): bool
    {
        return $this->isTrader();
    }

    public function isTraderDragonTiger(): bool
    {
        if (is_null($this->type)) {
            return false;
        }

        $isTrader = $this->type->is(UserType::TRADER);

        if (! $isTrader) {
            return false;
        }

        if ($this->two_factor_secret === 'dragon_tiger') {
            return true;
        }

        return false;
    }

    public function isDragonTigerDealer(): bool
    {
        if (! $this->isTraderDragonTiger()) {
            return false;
        }

        return Str::contains($this->name, 'dealer');
    }

    public function isDragonTigerGameTable(): bool
    {
        if (! $this->isMember() && ! $this->isTraderDragonTiger()) {
            return false;
        }

        return $this->two_factor_secret === 'dragon_tiger';
    }

    public function isTraderBaccarat(): bool
    {
        if (is_null($this->type)) {
            return false;
        }

        $isTrader = $this->type->is(UserType::TRADER);

        if (! $isTrader) {
            return false;
        }

        if ($this->two_factor_secret === 'baccarat') {
            return true;
        }

        return false;
    }

    public function isBaccaratDealer(): bool
    {
        if (! $this->isTraderBaccarat()) {
            return false;
        }

        return Str::contains($this->name, 'dealer');
    }

    public function isBaccaratGameTable(): bool
    {
        if (! $this->isMember() && ! $this->isTraderBaccarat()) {
            return false;
        }

        return $this->two_factor_secret === 'baccarat';
    }

    public function isSubAccount(): bool
    {
        return $this->type?->is(UserType::SUB_ACCOUNT);
    }

    public function isCompanySubAccount(): bool
    {
        if (! $this->isSubAccount()) {
            return false;
        }

        return $this->getEnsureType() === UserType::COMPANY;
    }

    public function getEnsureType()
    {
        if (! $this->isSubAccount()) {
            return $this->type;
        }

        $parents = Collection::make([
            UserType::SUPER_SENIOR => $this->super_senior,
            UserType::SENIOR => $this->senior,
            UserType::MASTER_AGENT => $this->master_agent,
            UserType::AGENT => $this->agent,
        ]);

        $parents = $parents->filter();

        if ($parents->isEmpty()) {
            return UserType::COMPANY;
        }

        return $parents->keys()->first();

    }

    public function getEnsureId()
    {
        if (! $this->isSubAccount()) {
            return $this->id;
        }

        return $this->isCompanySubAccount()
            ? 0
            : $this->{$this->getEnsureType()};
    }

    public function getUpLineType(): string
    {
        return match ($this->type?->value) {
            UserType::SUPER_SENIOR => UserType::COMPANY,
            UserType::SENIOR => UserType::SUPER_SENIOR,
            UserType::MASTER_AGENT => UserType::SENIOR,
            UserType::AGENT => UserType::MASTER_AGENT,
            UserType::MEMBER => UserType::AGENT,
            default => ''
        };
    }
}
