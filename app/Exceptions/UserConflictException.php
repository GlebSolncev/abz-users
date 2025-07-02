<?php

namespace App\Exceptions;

use Exception;

class UserConflictException extends Exception
{
    protected $message = 'User with this phone or email already exist';
}
