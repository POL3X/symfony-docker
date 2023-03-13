<?php

namespace App\User\Application\Service;

use App\User\Domain\Exceptions\InvalidPasswordFormatException;

class PasswordFormatChecker
{
    public function __construct(){

    }

    public  function __invoke(string $password): void
    {   
        $passwordValidator = $this->validatePassoword($password);

        if(false == $passwordValidator){
            throw new InvalidPasswordFormatException();
        }

    }

    function validatePassoword($contrasena) {
        // Verificar si la contraseña tiene al menos 7 caracteres
        if (strlen($contrasena) < 7) {
          return false;
        }
      
        // Verificar si la contraseña tiene al menos una mayúscula, una minúscula y un número
        if (!preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
          return false;
        }
      
        // Si se llega aquí, la contraseña cumple con los requisitos
        return true;
      }
}