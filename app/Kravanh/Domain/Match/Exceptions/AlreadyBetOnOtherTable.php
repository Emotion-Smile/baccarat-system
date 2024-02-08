<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class AlreadyBetOnOtherTable extends DomainException
{
    protected $message = 'betting.already_bet_on_other_table';
}
