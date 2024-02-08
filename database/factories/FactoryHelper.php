<?php

namespace Database\Factories;

use App\Kravanh\Domain\Environment\Models\Domain;
use App\Kravanh\Domain\Environment\Models\Environment;
use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Facades\Hash;

trait FactoryHelper
{
    public function getEnvId()
    {
        return $this->getEnvironment()->id;
    }

    public function getGroupId()
    {
        return $this->getGroup()->id;
    }

    public function getEnvironment(): Environment
    {
        return Environment::firstOr(fn() => Environment::factory()->createQuietly());
    }

    public function getGroup(): Group
    {
        return Group::firstOr(fn() => Group::factory()->createQuietly());
    }

    public function getMatch(): Matches
    {
        return Matches::firstOr(fn() => Matches::factory()->createQuietly());
    }

    public function getDomain(): Domain
    {
        return Domain::firstOrCreate([
            'domain' => 'localhost.test',
        ]);
    }

    public function generalUserFields(
        string $userType,
        string $currency = Currency::KHR
    ): array
    {
        return [
            'environment_id' => $this->getEnvironment()->id,
            'domain_id' => $this->getDomain()->id,
            'group_id' => $this->getEnvironment()->id,
            'name' => $this->faker->name,
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber,
            'type' => $userType,
            'currency' => $currency,
            'status' => Status::OPEN
        ];
    }
}
