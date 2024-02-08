<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class GroupDisabled extends DomainException
{
    protected $message = 'group_disabled';
}
