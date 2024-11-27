<?php
class Code {
    public static function generateAlphanumericCode($length) {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz123456789';
        $code = '';
        $max = strlen($characters) - 1;
    
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $max)];
        }
    
        return $code;
    }

   
}


class Password {
    // Expresión regular para validar contraseñas
    const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';

    // Función para generar contraseñas seguras
    public static function generatePassword($long = 8) {
  

        $characters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789+*-_';
        $numOfCharacters = strlen($characters);

        do {
            $password = '';
            for ($i = 0; $i < $long; $i++) {
                $index = random_int(0, $numOfCharacters - 1);
                $password .= $characters[$index];
            }
        } while (!preg_match(self::PASSWORD_REGEX, $password)); // Reintenta si no cumple con el patrón

        return $password;
    }
}


?>

