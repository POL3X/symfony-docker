<?php

namespace App\User\Infrastructure\Controller\User;

use App\User\Infrastructure\Services\JwtAuthService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserLoginController{
    
    public function __construct(private JwtAuthService $service)
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

        try{
            $response = $this->service->login($email, $password);
        
            return new JsonResponse([
                'code' => 200,
                'message' => $response
            ], Response::HTTP_CREATED);
        }catch (InvalidArgumentException $e){
            return new JsonResponse([
                'code' => 400,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}