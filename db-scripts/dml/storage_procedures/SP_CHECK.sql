DELIMITER $$

CREATE PROCEDURE USER_ADMIN_BY_USERNAME(IN userNameAdmin VARCHAR(50))
BEGIN
   	SELECT id_user_admissions_administrator FROM UsersAdmissionsAdministrator
	WHERE username_user_admissions_administrator = userNameAdmin;
END$$


CREATE PROCEDURE GET_CHECK_BY_IDAPPLICANT_IDAPLICATION(IN idApplicant VARCHAR(20), IN idAplication INT)
BEGIN
   	SELECT id_check_applicant_applications  FROM CheckApplicantApplications
	WHERE id_applicant = idApplicant AND id_admission_application_number =  idAplication  AND revision_status_check_applicant_applications = 0 AND verification_status_data_applicant =0;
END$$


CREATE PROCEDURE INSERT_CHECK_ERROR(IN idCheckApplicant INT, IN wrongData VARCHAR(100), IN description VARCHAR(255))
BEGIN
	INSERT INTO CheckErrorsApplicantApplications (id_check_applicant_applications, incorrect_data, description_incorrect_data) 
	VALUES (idCheckApplicant, wrongData, description);
END$$


CREATE PROCEDURE UPDATE_CHECK_APPLICANT_APPLICATIONS(IN p_id_check_applicant_applications INT,IN p_verification_status_data_applicant BOOLEAN,
						     IN p_date_check_applicant_applications DATE,IN p_revision_status_check_applicant_applications BOOLEAN,
						     IN p_description_general_check_applicant_applications VARCHAR(255))
BEGIN
    UPDATE CheckApplicantApplications
    SET 
        verification_status_data_applicant = p_verification_status_data_applicant,
        date_check_applicant_applications = p_date_check_applicant_applications,
        revision_status_check_applicant_applications = p_revision_status_check_applicant_applications,
	description_general_check_applicant_applications = p_description_general_check_applicant_applications
    WHERE id_check_applicant_applications = p_id_check_applicant_applications;
END$$

CREATE PROCEDURE DELETE_CHECK_ERRORS_BY_APPLICANT(IN p_id_check_applicant_applications INT)
BEGIN
    DELETE FROM CheckErrorsApplicantApplications
    WHERE id_check_applicant_applications = p_id_check_applicant_applications;
END$$


CREATE PROCEDURE CHECK_PENDING_BY_USER_ADMINISTRADOR(IN userID INT)
BEGIN
   	SELECT id_check_applicant_applications, id_applicant, id_admission_application_number FROM CheckApplicantApplications
	WHERE admissions_administrator_check_applicant_applications = userID AND revision_status_check_applicant_applications = 0;
END$$


CREATE PROCEDURE APPLICANT_DATA_VIEW(IN idApplicant VARCHAR(20))
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
        image_id_applicant,
        AdmissionProcess.name_admission_process,
        RegionalCenters.name_regional_center,
        first.name_undergraduate as firstC,
        second.name_undergraduate as secondC,
        Applications.secondary_certificate_applicant as certificate,
        CheckApplicantApplications.id_check_applicant_applications
    FROM Applicants
    INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
    AND `Applications`.status_application=1
    INNER JOIN AdmissionProcess ON Applications.id_admission_process = AdmissionProcess.id_admission_process
    INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
    INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
    INNER JOIN Undergraduates  second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
    INNER JOIN `CheckApplicantApplications` ON `CheckApplicantApplications`.id_admission_application_number = `Applications`.id_admission_application_number
    WHERE Applicants.id_applicant = idApplicant
    ORDER BY Applications.id_admission_application_number;
END$$


CREATE PROCEDURE GET_DATA_APPLICANT_APPLICATIONS_MAIL(IN idCheckApplicant INT)
BEGIN
    SELECT CONCAT_WS(' ',
            COALESCE(Applicants.first_name_applicant, ''),
            COALESCE(Applicants.second_name_applicant, ''),
            COALESCE(Applicants.third_name_applicant, '')
        ) AS nameApplicant,
	email_applicant
	FROM Applicants
	INNER JOIN CheckApplicantApplications ON CheckApplicantApplications.id_applicant = Applicants.id_applicant;
	
END$$

DELIMITER ;

