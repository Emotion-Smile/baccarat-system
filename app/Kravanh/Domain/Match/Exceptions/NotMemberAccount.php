<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class NotMemberAccount extends DomainException
{
    protected $message = 'betting.account_not_allow';
}
