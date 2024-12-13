<?php

/**
 * Recupera a los aspirantes para enviarles un correo con los resultados de su examen(es) de admisión.
 *
 * Esta función consulta la base de datos y organiza los resultados por aspirante,
 * incluyendo su información personal y las calificaciones obtenidas en los diferentes tipos de exámenes.
 *
 * @param mysqli $connection Conexión activa a la base de datos.
 * 
 * @return array Un arreglo asociativo donde cada clave es el ID del aspirante y su valor contiene:
 *               - 'full_name': Nombre completo del aspirante.
 *               - 'email': Correo electrónico del aspirante.
 *               - 'password': Contraseña del usuario.
 *               - 'exams': Arreglo con los exámenes y sus calificaciones.
 *
 * @author Kenia Romero
 */
function getGroupedResultsPerApplicants($connection) {
    $sql = "
          SELECT 
            Applicants.id_applicant,
            CONCAT(
                Applicants.first_name_applicant, ' ',
                IFNULL(Applicants.second_name_applicant, ''), ' ',
                IFNULL(Applicants.third_name_applicant, ''), ' ',
                Applicants.first_lastname_applicant, ' ',
                IFNULL(Applicants.second_lastname_applicant, '')
            ) AS full_name,
            Applicants.email_applicant,
            UsersApplicants.password_user_applicant,
            `TypesAdmissionTests`.name_type_admission_tests,
            `RatingApplicantsTest`.rating_applicant
        FROM 
            Applicants
        LEFT JOIN 
            Applications ON Applicants.id_applicant = Applications.id_applicant
        LEFT JOIN 
            UsersApplicants ON Applications.id_admission_application_number = UsersApplicants.password_user_applicant
        LEFT JOIN
            RatingApplicantsTest ON Applications.id_admission_application_number = RatingApplicantsTest.id_admission_application_number
        LEFT JOIN `TypesAdmissionTests` ON `RatingApplicantsTest`.id_type_admission_tests = `TypesAdmissionTests`.id_type_admission_tests   
        WHERE 
            Applicants.status_applicant = 1 AND status_rating_applicant_test = 1;
    ";

    $result = $connection->query($sql);
    $groupedResults = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id_applicant'];
            if (!isset($groupedResults[$id])) {
                $groupedResults[$id] = [
                    'full_name' => $row['full_name'],
                    'email' => $row['email_applicant'],
                    'password' => $row['password_user_applicant'],
                    'exams' => []
                ];
            }

            $groupedResults[$id]['exams'][] = [
                'exam_name' => $row['name_type_admission_tests'],
                'rating' => $row['rating_applicant']
            ];
        }
    }

    return $groupedResults;
}

/**
* Genera una tabla HTML con los detalles de los exámenes y calificaciones del aspirante.
*
* @param array $exams Arreglo que contiene los detalles de los exámenes, donde cada elemento incluye:
*                     - 'exam_name': Nombre del examen.
*                     - 'rating': Calificación obtenida.
* 
* @return string Retorna una cadena HTML que representa una tabla con los detalles del examen.
*
* @author Kenia Romero
*/
function generateExamDetails($exams) {
    $details = "<table border='1' style='border-collapse: collapse; width: 100%;'>
                    <thead>
                        <tr>
                            <th>Examen</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody>";
    foreach ($exams as $exam) {
        $details .= "<tr>
                        <td>{$exam['exam_name']}</td>
                        <td>{$exam['rating']}</td>
                    </tr>";
    }
    $details .= "</tbody></table>";

    return $details;
}

/**
* Recupera a los aspirantes que han enviado su solicitud exitosamente.
*
* @param mysqli $connection Conexión activa a la base de datos.
* 
* @return mysqli_result|bool Resultado de la consulta SQL con los siguientes campos:
*                           - 'id_applicant': ID del aspirante.
*                           - 'full_name': Nombre completo del aspirante.
*                           - 'email_applicant': Correo electrónico del aspirante.
*
* @author Kenia Romero
*/
function getApplicantsForConfirmation($connection) {
    $sql = "
        SELECT 
            Applicants.id_applicant,
            CONCAT(
                Applicants.first_name_applicant, ' ',
                IFNULL(Applicants.second_name_applicant, ''), ' ',
                IFNULL(Applicants.third_name_applicant, ''), ' ',
                Applicants.first_lastname_applicant, ' ',
                IFNULL(Applicants.second_lastname_applicant, '')
            ) AS full_name,
            Applicants.email_applicant
        FROM 
            Applicants
        JOIN 
            Applications ON Applicants.id_applicant = Applications.id_applicant
        WHERE 
            Applicants.status_applicant = 1 AND 
            Applications.status_application = 1;
    ";
    return $connection->query($sql);
}


/**
* Recupera a los aspirantes aprobados y registrados en una carrera universitaria.
*
* @param mysqli $connection Conexión activa a la base de datos.
* 
* @return mysqli_result|bool Resultado de la consulta SQL con los siguientes campos:
*                           - 'id_applicant': ID del aspirante.
*                           - 'full_name': Nombre completo del aspirante.
*                           - 'email_applicant': Correo electrónico del aspirante.
*                           - 'name_undergraduate': Nombre de la carrera universitaria.
*
* @author Kenia Romero
*/
function getApprovedApplicants($connection) {
    $sql = "
        SELECT 
            Applicants.id_applicant,
            CONCAT(
                Applicants.first_name_applicant, ' ',
                IFNULL(Applicants.second_name_applicant, ''), ' ',
                IFNULL(Applicants.third_name_applicant, ''), ' ',
                Applicants.first_lastname_applicant, ' ',
                IFNULL(Applicants.second_lastname_applicant, '')
            ) AS full_name,
            Applicants.email_applicant,
            Undergraduates.name_undergraduate AS 
        FROM 
            Applicants
        JOIN 
            Applications ON Applicants.id_applicant = Applications.id_applicant
        JOIN 
            ResolutionIntendedUndergraduateApplicant ON 
                Applications.id_admission_application_number = ResolutionIntendedUndergraduateApplicant.id_admission_application_number
        JOIN 
            Undergraduates ON 
                ResolutionIntendedUndergraduateApplicant.intended_undergraduate_applicant = Undergraduates.id_undergraduate
        WHERE 
            Applicants.status_applicant = 1 AND 
            ResolutionIntendedUndergraduateApplicant.resolution_intended = 1 AND 
            ResolutionIntendedUndergraduateApplicant.status_resolution_intended_undergraduate_applicant = 1;
    ";
    return $connection->query($sql);
}

/**
* Recupera las credenciales (usuario y contraseña) de los estudiantes activos.
*
* @param mysqli $connection Conexión activa a la base de datos.
* 
* @return mysqli_result|bool Resultado de la consulta SQL con los siguientes campos:
*                           - 'id_student': ID del estudiante.
*                           - 'full_name': Nombre completo del estudiante.
*                           - 'usser': Nombre de usuario del estudiante.
*                           - 'password': Contraseña del estudiante.
*                           - 'email': Correo electrónico del estudiante.
*
* @author Kenia Romero
*/
function getStudentsPassword($connection) {
    $sql = "
        SELECT 
            Students.id_student,
            CONCAT(
                Students.first_name_student, ' ',
                IFNULL(Students.second_name_student, ''), ' ',
                IFNULL(Students.third_name_student, ''), ' ',
                Students.first_lastname_student, ' ',
                IFNULL(Students.second_lastname_student, '')
            ) AS full_name, 
			UsersStudents.username_user_student AS usser,
            UsersStudents.password_user_student AS password,
            Students.email_student AS email 
        FROM 
            UsersStudents   
        JOIN 
            Students 
        ON 
            UsersStudents.username_user_student = Students.id_student
        WHERE 
            UsersStudents.status_user_student = TRUE;
    ";
    return $connection->query($sql);
}
?>
