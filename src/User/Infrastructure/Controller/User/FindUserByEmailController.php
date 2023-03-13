<?php

namespace App\User\Infrastructure\Controller\User;

use App\User\Application\Query\User\FindUserByEmailHandler;
use App\User\Domain\Exceptions\UserNotFoundException;
use App\User\Infrastructure\Services\JwtAuthService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FindUserByEmailController{
    
    public function __construct(private FindUserByEmailHandler $service,  private JwtAuthService $jwtService )
    {
        
    }

    public function __invoke(Request $request){
        
        
        $params = $request->request;
        $email = $params->get("email");
        if(empty($email)){
            return new JsonResponse([
                'code' => 400,
                'message' => 'Email Needed'
            ], Response::HTTP_BAD_REQUEST);
        }

        try{
            $actualUser = $this->jwtService->getUserFromToken($request);
            $user = $this->service->__invoke($email);
            return new JsonResponse([
                'code' => 200,
                'message' => $user->toArray()
            ], Response::HTTP_OK);

        }catch (UserNotFoundException $e )
        {
            return new JsonResponse([
                'code' => 400,
                'message' => 'User with email / ' . $email . ' / not found'
            ], Response::HTTP_BAD_REQUEST);
        }catch (InvalidArgumentException $e){
            return new JsonResponse([
                'code' => 403,
                'message' => 'Forbbiden'
            ], Response::HTTP_FORBIDDEN);
        }
        
     
    }
}