<?php

class StudentFunctions {
    static function generateAccountNumber (int $idRegionalCenter) {
        $year = date("Y"); //Obtener ano actual

        // Obtener el mes actual
        $month = date('n'); // 'n' devuelve el mes sin ceros iniciales (1 a 12)

        // Determinar el cuatrimestre
        if ($month >= 1 && $month <= 4) {
            $fourMonth = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $fourMonth = 2;
        } else {
            $fourMonth = 3;
        }

        if($idRegionalCenter < 10) {
            $codeRC = '0'.$idRegionalCenter;
        } else {
            $codeRC = $idRegionalCenter;
        }

        $incrementalNumber = StudentFunctions::countStudents();

        $accountNumber = $year.$codeRC.$fourMonth.$incrementalNumber;

        return $accountNumber;
    }

    static function generateEmail (string $email) {
        // Verificar si el correo tiene un formato válido
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Dividir el correo en dos partes: antes y después del arroba
            $partes = explode('@', $email);
            // Sustituir el dominio
            $institutionalEmail = $partes[0] . '@unah.hn';
            return $institutionalEmail;
        } else {
            // Si el correo no es válido, retornar un mensaje de error
            return "Correo inválido";
        }
    }

    static function countStudents () {
        $conexion = new mysqli('localhost', 'root', '12345', 'unah_registration');

        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Consulta para contar registros
        $sql = "SELECT COUNT(*) AS total FROM Students";
        $result = $conexion->query($sql);

        // Verificar y mostrar el resultado
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if ($row['total'] < 10) {
                return '000'.$row['total'];
            } elseif ($row['total'] < 100) {
                return '00'.$row['total'];
            } elseif ($row['total'] < 1000) {
                return '0'.$row['total'];
            } elseif ($row['total'] >= 10000) {
                return '0000';
            } else {
                return $row['total'];
            }
        } else {
            return '0000';
        }
    }
}
?>