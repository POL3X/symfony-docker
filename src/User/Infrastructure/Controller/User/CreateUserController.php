<?php

namespace App\User\Infrastructure\Controller\User;

use App\User\Application\Request\User\CreateUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\ValueObjects\UserEmail;
use App\User\Domain\Exceptions\InvalidMailFormatException;
use App\User\Domain\Exceptions\InvalidPasswordFormatException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Factory\UuidFactory;

class CreateUserController{
    
    public function __construct(private CreateUser $service)
    {
        
    }

    public function __invoke(Request $request){
        $params = $request->request;
        $email = $params->get("email");
        $password = $params->get("password");;
        if(empty($email) || empty($password)){
            return new JsonResponse([
                'code' => 404,
                'message' => 'Need Email and Password'
            ], Response::HTTP_NOT_FOUND);
        }
        $uuid = (new UuidFactory())->create()->__toString();
        try{
            $newUser = new User($uuid,new UserEmail($email),["ROLE_PLAYER"], $password);
            $this->service->__invoke($newUser);
            return new JsonResponse([
                'code' => 200,
                'message' => [
                    'Uuid' => $newUser->getUuid(),
                    'email' => $newUser->getEmail(),
                ]
            ], Response::HTTP_CREATED);
        }catch(InvalidMailFormatException $e){
            return new JsonResponse([
                'code' => 404,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }catch(InvalidPasswordFormatException $e){
            return new JsonResponse([
                'code' => 404,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND); 
        }    
    }
}