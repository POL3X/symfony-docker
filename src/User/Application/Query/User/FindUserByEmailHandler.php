<?php

namespace App\User\Application\Query\User;

use App\User\Domain\Interfaces\DoctrineUserRepositoryInterface;
use App\User\Domain\Entity\User;

class FindUserByEmailHandler
{
    public function __construct(private readonly DoctrineUserRepositoryInterface $userRepository){

    }

    public  function __invoke(string $email): User
    {
       return $this->userRepository->findOneByEmail($email);
    }
}