<?php

namespace App\User\Infrastructure\Services;

use App\User\Application\Query\User\FindUserByEmailHandler;
use App\User\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class JwtAuthService
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher, 
        private JWTEncoderInterface $jwtEncoder,
        private FindUserByEmailHandler $findUserByEmail)
    {

    }

    public function login(string $email, string $password): array
    {
        $user = $this->findUserByEmail->__invoke($email);

        if (!$user) {
            throw new \InvalidArgumentException('Invalid email or password');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new \InvalidArgumentException('Invalid email or password');
        }

        return $this->createToken($user);
    }

    public function getUserFromToken(Request $request): User
    {
        $preToken = $request->headers->get("authorization");
        $token = str_replace("Bearer ","",$preToken);
        try {
            $payload = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            throw new \InvalidArgumentException('Invalid token');
        }

        $user = $this->findUserByEmail->__invoke($payload['email']);

        if (!$user) {
            throw new \InvalidArgumentException('Invalid token');
        }

        return $user;
    }

    private function createToken(User $user): array
    {
        try {
            $token = $this->jwtEncoder->encode([
                'email' => $user->getEmail(),
                'exp' => time() + 3600, // 1 hour expiration
            ]);
        } catch (JWTEncodeFailureException $e) {
            throw new \RuntimeException('Error while encoding token');
        }

        return [
            'token' => $token,
            'email' => $user->getEmail(),
        ];
    }
}