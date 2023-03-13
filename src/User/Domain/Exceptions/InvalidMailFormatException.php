<?php

namespace App\User\Domain\Exceptions;

class InvalidMailFormatException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            //sprintf('"%s" is not a valid object for collection. Expected "%s"', get_class($actual), $expected)
            'Invalid Email format'
        );
    }
}