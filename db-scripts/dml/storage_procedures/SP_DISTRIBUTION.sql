DELIMITER $$

CREATE PROCEDURE INSERT_CHECK_APPLICANT_APPLICATIONS(
    IN p_id_applicant VARCHAR(20),
    IN p_id_admission_application_number INT,
    IN p_verification_status_data_applicant BOOLEAN,  
    IN p_date_check_applicant_applications DATE,   
    IN p_revision_status_check_applicant_applications BOOLEAN,
    IN p_admissions_administrator INT
)
BEGIN
    INSERT INTO CheckApplicantApplications (
        id_applicant,
        id_admission_application_number,
        verification_status_data_applicant,
        date_check_applicant_applications,
        revision_status_check_applicant_applications,
        admissions_administrator_check_applicant_applications
    )
    VALUES (
        p_id_applicant,
        p_id_admission_application_number,
        p_verification_status_data_applicant, 
        p_date_check_applicant_applications,
        p_revision_status_check_applicant_applications,
        p_admissions_administrator
    );
END$$


CREATE PROCEDURE GetUsersAdmissionsAdministratorByRol(IN RolAdmissionsAdministrator INT)
BEGIN
   	SELECT UsersAdmissionsAdministrator.id_user_admissions_administrator FROM UsersAdmissionsAdministrator
	INNER JOIN RolesUsersAdmissionsAdministrator ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
	WHERE RolesUsersAdmissionsAdministrator.id_role_admissions_administrator = RolAdmissionsAdministrator AND RolesUsersAdmissionsAdministrator.status_role_admissions_administrator =1;
END$$

CREATE PROCEDURE GET_APPLICATIONS_BY_ADMIN_PROCESS(IN IdAdminProcess INT)
BEGIN
   	SELECT id_applicant,id_admission_application_number FROM Applications
	WHERE Applications.id_admission_process  =IdAdminProcess AND Applications.status_application = 1;
END$$

DELIMITER ;