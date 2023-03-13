<?php

namespace App\User\Domain\Exceptions;

class InvalidPasswordFormatException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            //sprintf('"%s" is not a valid object for collection. Expected "%s"', get_class($actual), $expected)
            'Invalid format, password must have at least:
                - One upper case
                - One lowe case
                - One number
                - > 7 characteres '
        );
    }
}