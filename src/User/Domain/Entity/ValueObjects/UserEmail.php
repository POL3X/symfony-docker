<?php

namespace App\User\Domain\Entity\ValueObjects;

use App\User\Domain\Exceptions\InvalidMailFormatException;

class UserEmail
{
    private string $email;

    public function __construct(string $email)
    {
        $this->validateEmailformat($email);
        $this->email = $email;
    }

    public function validateEmailformat(string $email): void{
        $mailValidator = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(false == $mailValidator){
            throw new InvalidMailFormatException();
        }
    }

    public function toString(): string {
        return $this->email;
    }
}
