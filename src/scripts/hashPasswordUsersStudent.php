<?php
/**
 * Script que cifra las contrasenas de los usuarios estudiantes. Usado unicamente para agilizar el desarrollo, las contrasenas se encriptan automaticamente al insertar al aspirante desde la plataforma.
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
$querySelect = "SELECT username_user_student, password_user_student FROM `UsersStudents`";
$result = $connection->query($querySelect);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['username_user_student'];
        $password = $row['password_user_student'];

        // Verificar si la contraseña ya está cifrada
        if (password_get_info($password)['algo'] == 0) { // Si 'algo' es 0, no está cifrada
            // Cifrar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Actualizar la contraseña cifrada en la base de datos
            $queryUpdate = "UPDATE UsersStudents SET password_user_student = ? WHERE username_user_student = ?";
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
