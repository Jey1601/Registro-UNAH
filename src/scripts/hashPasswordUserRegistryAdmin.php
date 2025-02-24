<?php
/**
 * Script que cifra las contrasenas de los usuarios administradores de registro.
 * 
 * @author @AngelNolasco
 */

$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'unah_registration';

$connection = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

echo "Conexión exitosa.\n";

// Consulta para obtener los usuarios y sus contraseñas
$querySelect = "SELECT id_user_registry_administrator, password_user_registry_administrator FROM `UsersRegistryAdministrator`";
$result = $connection->query($querySelect);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_user_registry_administrator'];
        $password = $row['password_user_registry_administrator'];

        // Verificar si la contraseña ya está cifrada
        if (password_get_info($password)['algo'] == 0) { // Si 'algo' es 0, no está cifrada
            // Cifrar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Actualizar la contraseña cifrada en la base de datos
            $queryUpdate = "UPDATE UsersRegistryAdministrator SET password_user_registry_administrator = ? WHERE id_user_registry_administrator = ?";
            $stmt = $connection->prepare($queryUpdate);
            $stmt->bind_param('si', $hashedPassword, $id);

            if ($stmt->execute()) {
                echo "Contraseña del usuario con ID $id cifrada y actualizada.\n";
            } else {
                echo "Error al actualizar la contraseña del usuario con ID $id: " . $stmt->error . "\n";
                continue;
            }

            $stmt->close();
        } else {
            echo "La contraseña del usuario con ID $id ya está cifrada.\n";
        }
    }
} else {
    echo "No se encontraron usuarios en la tabla.\n";
}

$connection->close();