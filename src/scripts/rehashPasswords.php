<?php
$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'unah_registration';

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

echo "Conexión exitosa.\n";

// Consulta para obtener los usuarios y sus contraseñas
$querySelect = "SELECT id_user_admissions_administrator, password_user_admissions_administrator FROM `UsersAdmissionsAdministrator`";;
$result = $connection->query($querySelect);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_user_admissions_administrator'];
        $hashedPassword = $row['password_user_admissions_administrator'];

        // Verificar si la contraseña está cifrada y si necesita ser actualizada
        if (password_get_info($hashedPassword)['algo'] !== 0 && password_needs_rehash($hashedPassword, PASSWORD_BCRYPT)) {
            echo "El hash de la contraseña para el usuario con ID $id necesita ser actualizado.\n";

            // Rehash de la contraseña
            $newHash = password_hash($hashedPassword, PASSWORD_BCRYPT);

            // Actualizar el hash de la contraseña en la base de datos
            $queryUpdate = "UPDATE UsersAdmissionsAdministrator SET password_user_admissions_administrator = ? WHERE id_user_admissions_administrator = ?";
            $stmt = $connection->prepare($queryUpdate);
            $stmt->bind_param('si', $newHash, $id);

            if ($stmt->execute()) {
                echo "Contraseña del usuario con ID $id actualizada correctamente.\n";
            } else {
                echo "Error al actualizar la contraseña del usuario con ID $id: " . $stmt->error . "\n";
                continue;
            }

            $stmt->close();
        } else {
            echo "La contraseña del usuario con ID $id no necesita ser actualizada.\n";
        }
    }
} else {
    echo "No se encontraron usuarios en la tabla.\n";
}

$connection->close();
