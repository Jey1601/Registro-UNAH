<?php
class JWT {
    private $secret_key = 'is802';

    public static function generateToken(array $payload, int $expTime = 3600):string {
        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);

        $payload['exp'] = time() + $expTime; //Tiempo de expiracion del token

        $base64Header = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', $base64Header.".".$base64Payload, self::$secret_key, true);
        $base64Signature = self::base64UrlEncode($signature);

        return $base64Header.".".$base64Payload.".".$base64Signature;
    }

    public static function validateToken(string $jwt):bool|array {
        $partes = explode('.', $jwt);
        if(count($partes) !== 3) {
            return false; //Formato invalido
        }

        [$base64Header, $base64Payload, $base64Signature] = $partes;
        $payload = json_decode(self::base64UrlDecode($base64Payload), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false; //Payload invalido
        }

        //Verificacion de firma
        $signature = hash_hmac('sha256', $base64Header.".".$base64Payload, self::$secret_key, true);
        $base64SignatureCheck = self::base64UrlEncode($signature);
        if (!hash_equals($base64Signature, $base64SignatureCheck)) {
            return false;
        }

        //Verificacion de expiracion
        if (isset($payload['exp']) && time() >= $payload['exp']) {
            return false; //Token expirado
        }
        
        return $payload; //Token valido
    }

    private static function base64UrlEncode(string $data):string {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode(string $data):string {
        $padding = 4 - (strlen($data) % 4);
        if ($padding !== 4) {
            $data .= str_repeat('=', $padding);
        }

        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}

?>