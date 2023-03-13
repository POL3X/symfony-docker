<?php

namespace App\User\Infrastructure\Database\ORM\Doctrine\Entity;


use App\User\Domain\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    public function __construct(
        private ?string $uuid = null,  
        private ?string $email = null, 
        private ?array $roles = null,
        private ?string $password = null)
    {
    }

    public static function createFromDomainUser(User $user): self
    {
        return new self($user->getUuid(),$user->getEmail(),$user->getRoles(),$user->getPassword());
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
        return array_unique($this->roles);
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
}
