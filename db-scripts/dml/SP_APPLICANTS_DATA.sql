DELIMITER $$

CREATE PROCEDURE SP_APPLICANTS_DATA()
BEGIN
   SELECT A.id_applicant, A.id_admission_application_number,E.id_type_admission_tests, E.name_type_admission_tests, C.rating_applicant,
        CONCAT(
            COALESCE(B.first_name_applicant, ''),
            ' ',
            COALESCE(B.second_name_applicant, ''),
            ' ',
            COALESCE(B.third_name_applicant, ''),
            ' ',
            COALESCE(B.first_lastname_applicant, ''),
            ' ',
            COALESCE(B.second_lastname_applicant, '')
        ) AS name, D.name_regional_center 
    FROM Applications A
    LEFT JOIN Applicants B on A.id_applicant = B.id_applicant
    LEFT JOIN RatingApplicantsTest C on A.id_admission_application_number = C.id_admission_application_number
    LEFT JOIN RegionalCenters D on A.idregional_center = D.id_regional_center
    LEFT JOIN TypesAdmissionTests E on C.id_type_admission_tests = E.id_type_admission_tests
    WHERE A.status_application=TRUE;
END $$

DELIMITER;