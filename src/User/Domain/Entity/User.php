<?php

namespace App\User\Domain\Entity;

use App\User\Domain\Entity\ValueObjects\UserEmail;
use App\User\Infrastructure\Database\ORM\Doctrine\Entity\DoctrineUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?string $uuid = null;

    private ?UserEmail $email = null;

    private array $roles = [];

    private ?string $password = null;

    public function __construct(string $uuid, UserEmail $email,array $role,?string $password = null )
    {
        $this->uuid = $uuid;
        $this->email = $email;
        $this->roles = $role;
        $this->password = $password;
    }

    public static function createFromDoctrineUser(DoctrineUser $user){
        return new self(
            $user->getUuid(),
            new UserEmail($user->getEmail()),
            $user->getRoles(),
            $user->getPassword()
        );
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email->toString();
    }

    public function setEmail(UserEmail $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function toArray() : array {
        return [
            'id' => $this->getUuid(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            'password' => $this->getPassword()
        ];
    }
}
