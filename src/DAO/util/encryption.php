<?php
/**
 * Clase auxiliar para el encriptado y desencriptado de contrasenas
 * 
 * @author @AngelNolasco
 * @created 24/12/2024
 */
class Encryption {
    /**
     * Metodo para cifrar una contrasena utilizando el algoritmo bcrypt.
     * 
     * @param string $password Contrasena a cifrar.
     * @return string $hashedPassword Contrasena cifrada.
     * @throws Exception Si ocurre un error durante el proceso de cifrado.
     */
    public static function hashPassword(string $password): string {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if ($hashedPassword === false) {
            throw new Exception("Error al cifrar la contrasena.");
        }

        return $hashedPassword;
    }

    /**
     * Metodo para verificar si la contrasena coincide con un hash (contrasena cifrada).
     * 
     * @param string $password La contrasena en texto plano.
     * @param string $hash El hash contra el que se verifica.
     * @return bool Verdadero si la contrasena coincide, falso en caso contrario.
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Metodo para revisar si un hash (contrasena cifrada) necesita ser actualizado.
     * 
     * @param string $hash El hash se que se desea verificar.
     * @return bool Verdadero si necesita una actualizacion, falso en caso contrario.
     */
    public static function needsRehash(string $hash): bool {
        return password_needs_rehash($hash, PASSWORD_BCRYPT);
    }
}
?>