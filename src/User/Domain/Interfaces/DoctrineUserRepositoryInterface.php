<?php
declare(strict_types=1);

namespace App\User\Domain\Interfaces;

use App\User\Domain\Entity\User;

interface DoctrineUserRepositoryInterface {

    public function save(User $user, bool $flush): void;

    public function findOneByEmail(string $email): ?User;
}