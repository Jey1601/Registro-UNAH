<?php
/**
 * Clase para la verificacion del token con la base de datos.
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 * */

include_once '../util/jwt.php';

class TokenVerification {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;

    /**
     * Constructor de clase donde se hace la conexion con la base de datos.
     * 
     * @return mysqli $connection Objeto mysqli que contiene la conexion con la base de datos, o valor null en caso de fallo.
     */
    public function __construct () {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

        return $this->connection;
    }

    /**
     * Metodo para verificar un token con la base de datos.
     * 
     * @param string $token El token que se quiere verificar.
     * @return array $response Arreglo asociativo con el resultado de la verificacion, un mensaje de retroalimentacion, y el tiempo de expiracion si la verificacion es exitosa o un token nulo si la verificacion fall.
     */
    public function tokenVerification(string $token) {
        if(isset($token)) { //Verificando que el token no sea nulo
            $validToken = JWT::validateToken($token); 
            if ($validToken !== false) { //Verificando que la estructura del token sea valida 
                $query = "SELECT id_user_admissions_administrator FROM TokenUserAdmissionAdmin WHERE token = ?;";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('s', $token);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) { //Verificando que el token exista en la base de datos
                    $response = [
                        'success' => true,
                        'message' => 'Verificacion del token satisfactoria.',
                        'tokenExpiration' => $validToken['exp']
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Token no encontrado.',
                        'token' => null
                    ];
                }
            } else { //Estructura de token invalida
                $response = [
                    'success' => false,
                    'message' => 'Token invalido.',
                    'token' => null
                ];
            }
            
        } else { //Si el token no esta declarado o es nulo
            $response = [
                'success' => false,
                'message' => 'Token nulo.',
                'token' => null
            ];
        }

        return $response;
    }
}

?>