<?php
include_once 'util/jwt.php';

/**
 * Objeto de acceso a datos y controlador de maestros.
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 */

class ProfessorsDAO {
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
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }   
    }

    /**
     * Metodo para la autenticacion de usuarios docentes.
     * 
     * @param string $username Username de usuario docente.
     * @param string $password Contrasena de usuario docente.
     * 
     * @return array $response Arreglo asociativo que indica si la autenticacion fallo o no junto con un mensaje de retroalimentacion y el valor del token (nulo en caso de fallo de autenticacion).
     */
    public function authProfessor(string $username, string $password) {
        if (isset($username) && isset($password)) {
            //Busqueda del usuario en la base de datos
            $query = "SELECT id_user_professor, password_user_professor FROM `UsersProfessors` WHERE username_user_professor = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { //Se encontro un usuario con ese username
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $auxID = intval($row['id_user_professor']);
                $hashPassword = $row['password_user_professor'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);

                if ($coincidence) { //Si la contrasena ingresada coincide con la de la BD
                    //Consulta para obtener los accesos del usuario
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control FROM `AccessControl`
                    INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control
                    INNER JOIN `Roles` ON `AccessControlRoles`.id_role = `Roles`.id_role
                    INNER JOIN `RolesUsersProfessor` ON `Roles`.id_role = `RolesUsersProfessor`.id_role_professor
                    INNER JOIN `UsersProfessors` ON `RolesUsersProfessor`.id_user_professor = `UsersProfessors`.id_user_professor
                    WHERE `UsersProfessors`.id_user_professor = ?;";
                    $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                    $stmtAccessArray->bind_param('i', $auxID);
                    $stmtAccessArray->execute();
                    $resultAccessArray = $stmtAccessArray->get_result();

                    $accessArray = [];
                    while ($rowAccess = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $rowAccess['id_access_control'];
                    }
                    $resultAccessArray->free();
                    $stmtAccessArray->close();

                    //Liberacion del resultado de la consulta
                    while ($this->connection->more_results() && $this->connection->next_result()) {
                        $extraResult = $this->connection->store_result();
                        if ($extraResult) {
                            $extraResult->free();
                        }
                    }

                    //Creacion del payload con el username y el arreglo de accesos del usuario administrador de admisiones
                    $payload = [
                        'userAdmissionAdmin' => $username,
                        'accessArray' => $accessArray
                    ];
                    $newToken = JWT::generateToken($payload); //Generacion del token a partir del payload
                    
                    //Consulta de busqueda del usuario en la tabla relacional de tokens respectiva
                    $queryCheck = "SELECT id_user_professor FROM TokenUserProfessor WHERE id_user_professor = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $auxID);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) { //El usuario existe, se actualiza el token
                        $queryUpdate = "UPDATE `TokenUserProfessor` SET token_professor = ? WHERE id_user_professor = ?;";
                        $stmtUpdate = $this->connection->prepare($queryUpdate);
                        $stmtUpdate->bind_param('si', $newToken, $auxID);
                        $resultUpdate = $stmtUpdate->execute();
                        
                        if ($resultUpdate === false) { //Si la actualizacion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no actualizado.',
                                'token' => null
                            ];
                        }
                        $stmtUpdate->close();
                    } else { //El usuario no existe en la tabla asi que se inserta
                        $queryInsert = "INSERT INTO `TokenUserProfessor` (id_user_professor, token_professor) VALUES (?, ?);";
                        $stmtInsert = $this->connection->prepare($queryInsert);
                        $stmtInsert->bind_param('is', $auxID, $newToken);
                        $resultInsert = $stmtInsert->execute();

                        if ($resultInsert === false) { //Si la insercion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no insertado.',
                                'token' => null
                            ];
                        }
                        $stmtInsert->close();
                    }

                    return $response = [ //Si todo funciona se retorna un arreglo asociativo donde va el token
                        'success' => true,
                        'message' => 'Validacion de credenciales exitosa.',
                        'token' => $newToken,
                        'typeUser' => 'facultyAdmin'
                    ];

                } else { //La contrasena no coincide
                    return $response = [
                        'success' => false,
                        'message' => 'Credenciales invalidas.',
                        'token' => null
                    ];
                }

            } else { //No existe usuario con el username ingresado
                return $response = [
                    'success' => false,
                    'message' => 'Usuario y/o contrasena incorrectos.',
                    'token' => null
                ];
            }

        } else { //Credenciales nulas
            return $response = [
                'success' => false,
                'message' => 'Credenciales invalidas.',
                'token' => null
            ];
        }
    }

}
?>