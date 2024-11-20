CREATE PROCEDURE SP_ASPIRANTS_DATA()
BEGIN
    SELECT id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_last_name_applicant, second_last_name_applicant, email_applicant, phone_number_applicant, status_applicant FROM Aspirants;
END$$


DELIMITER $$

CREATE PROCEDURE SP_ASPIRANTS_DATA_VIEW()
BEGIN

    SELECT 
        Applications.id_admission_application_number,
        Applicants.id_applicant,
        CONCAT(
            COALESCE(Applicants.first_name_applicant, ''),
            ' ',
            COALESCE(Applicants.second_name_applicant, ''),
            ' ',
            COALESCE(Applicants.third_name_applicant, '')
        ) AS name,
        CONCAT(
            COALESCE(Applicants.first_lastname_applicant, ''),
            ' ',
            COALESCE(Applicants.second_lastname_applicant, '')
        ) AS lastname,
        email_applicant, 
        phone_number_applicant, 
        address_applicant, 
        id_admission_application_number,  
        AdmissionProcess.name_admission_process,
        RegionalCenters.name_regional_center,
        first.name_undergraduate as firstC,
        second.name_undergraduate as secondC,
        Applications.secondary_certificate_applicant as certificate
    FROM Applicants
    INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
    INNER JOIN AdmissionProcess ON Applications.id_admission_process = AdmissionProcess.id_admission_process
    INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
    INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
    INNER JOIN Undergraduates  second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
    ORDER BY Applications.id_admission_application_number;
END$$

DELIMITER;





