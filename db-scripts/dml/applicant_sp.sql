CREATE PROCEDURE SP_APPLICANTS_DATA()
BEGIN
    SELECT A.id_applicant, A.id_admission_application_number, E.name_type_admission_tests, C.rating_applicant,
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

DELIMITER $$

CREATE PROCEDURE SP_ASPIRANTS_DATA_VIEW()
BEGIN

    SELECT 
        Applications.id_admission_application_number,
        Applicants.id_applicant,
         CONCAT_WS(' ',
            COALESCE(Applicants.first_name_applicant, ''),
            COALESCE(Applicants.second_name_applicant, ''),
            COALESCE(Applicants.third_name_applicant, '')
        ) AS name,
        CONCAT_WS(' ',
            COALESCE(Applicants.first_lastname_applicant, ''),
            COALESCE(Applicants.second_lastname_applicant, '')
        ) AS lastname,
        email_applicant, 
        phone_number_applicant, 
        address_applicant, 
        AdmissionProcess.name_admission_process,
        RegionalCenters.name_regional_center,
        first.name_undergraduate as firstC,
        second.name_undergraduate as secondC,
        Applications.secondary_certificate_applicant as certificate
    FROM Applicants
    INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
    AND `Applications`.status_application=1
    INNER JOIN AdmissionProcess ON Applications.id_admission_process = AdmissionProcess.id_admission_process
    INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
    INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
    INNER JOIN Undergraduates  second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
    ORDER BY Applications.id_admission_application_number;
END$$

DELIMITER;





