<?php

function getApplicantsWithResults($connection) {
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
            Applicants.status_applicant = 1 AND status_rating_applicant_test =1;";
    return $connection->query($sql);
}

/*Esta función recupera a los aspirantes para enviarles un correo que les informará el exito del envio de su solicitud*/
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


/*Esta función permite recuperar a los aspirantes que ya escogieron una carrera y fueron aprobados y registrados en la misma*/
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
?>
