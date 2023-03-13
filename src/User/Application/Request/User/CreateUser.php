<?php

namespace App\User\Application\Request\User;

use App\User\Application\Service\PasswordFormatChecker;
use App\User\Domain\Interfaces\DoctrineUserRepositoryInterface;
use App\User\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUser
{
    public function __construct(private readonly DoctrineUserRepositoryInterface $userRepository, private UserPasswordHasherInterface $passwordHasher){

    }

    public  function __invoke(User $user): void
    {      
        (new PasswordFormatChecker())->__invoke($user->getPassword());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
        $this->userRepository->save($user, true);
    }

}