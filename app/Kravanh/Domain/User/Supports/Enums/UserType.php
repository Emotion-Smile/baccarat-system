<?php

namespace App\Kravanh\Domain\User\Supports\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const COMPANY = 'company';
    const TRADER = 'trader';

    const SUPER_SENIOR = 'super_senior';
    const SENIOR = 'senior';
    const MASTER_AGENT = 'master_agent';
    const AGENT = 'agent';
    const MEMBER = 'member';

    const REPORTER = 'reporter';
    const SUB_ACCOUNT = 'sub_account';
    const DEVELOPER = 'developer';
}
