<?php

namespace App\User\Domain\Exceptions;

class UserNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            //sprintf('"%s" is not a valid object for collection. Expected "%s"', get_class($actual), $expected)
            'User not found'
        );
    }
}