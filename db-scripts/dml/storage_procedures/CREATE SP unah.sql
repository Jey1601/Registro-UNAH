USE unah_registration;

DELIMITER $$
	CREATE PROCEDURE SP_APPLICANTS_ADMITTED_DATA()
	BEGIN
		SELECT CONCAT(COALESCE(first_name_applicant, ''), ' ',COALESCE(second_name_applicant, ''), ' ',COALESCE(third_name_applicant, ''), ' ',COALESCE(first_lastname_applicant, ''), ' ',COALESCE(second_lastname_applicant, '')) AS nombre_completo_apirante_admitido, ApplicantAcceptance.id_applicant,address_applicant, phone_number_applicant ,email_applicant,  intended_undergraduate_applicant, idregional_center 
		FROM Applicants
			INNER JOIN ApplicantAcceptance ON Applicants.id_applicant = ApplicantAcceptance.id_applicant
			INNER JOIN NotificationsApplicationsResolution ON NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant = ApplicantAcceptance.id_notification_application_resolution
			INNER JOIN ResolutionIntendedUndergraduateApplicant ON ResolutionIntendedUndergraduateApplicant.id_resolution_intended_undergraduate_applicant = NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant
			INNER JOIN Applications ON Applications.id_applicant = ApplicantAcceptance.id_applicant
 		WHERE status_applicant_acceptance = 0 AND applicant_acceptance = 1 AND ResolutionIntendedUndergraduateApplicant.resolution_intended = 1;
	END$$		

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
    ORDER BY Applications.id_admission_application_number;
END$$

CREATE PROCEDURE ACTIVE_ACCEPTANCE(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_acceptance_admission_process FROM AcceptanceAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$



CREATE PROCEDURE START_DATE_ACCEPTANCE(IN idAcceptanceAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_ACCEPTANCE(IN idAcceptanceAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$

CREATE PROCEDURE ACTIVE_ADMISSION_PROCESS(IN Year INT)
BEGIN
   	SELECT id_admission_process FROM AdmissionProcess WHERE current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year ;
END$$


CREATE PROCEDURE START_DATE_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT start_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$

CREATE PROCEDURE END_DATE_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT end_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$


CREATE PROCEDURE NAME_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT name_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$
CREATE PROCEDURE ACTIVE_ADMISSION_TEST(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_admission_test_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1;
END$$

CREATE PROCEDURE DATE_ADMISSION_TEST(IN idAdmissionTestAdmissionProcess INT)
BEGIN
   	SELECT dateof_admission_test_admission_process FROM AdmissionTestAdmissionProcess WHERE id_admission_test_admission_process = idAdmissionTestAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1;
END$$

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
CREATE PROCEDURE ACTIVE_DOCUMENT_VALIDATION(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_DOCUMENT_VALIDATION(IN idDocumentValidationAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_DOCUMENT_VALIDATION(IN idDocumentValidationAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$
CREATE PROCEDURE ACTIVE_DOWNLOAD_ADMITTED(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_DOWNLOAD_ADMITTED(IN idDownloadAdmitted INT)
BEGIN
   	SELECT start_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_DOWNLOAD_ADMITTED(IN idDownloadAdmitted INT)
BEGIN
   	SELECT end_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$

CREATE PROCEDURE SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID(IN idUser INT)
BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN
        RolesUsersAdmissionsAdministrator
        ON AccessControlRoles.id_role = RolesUsersAdmissionsAdministrator.id_role_admissions_administrator
    INNER JOIN 
        UsersAdmissionsAdministrator 
        ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
    WHERE 
        UsersAdmissionsAdministrator.id_user_admissions_administrator = idUser;
END $$
CREATE PROCEDURE SP_GET_ACCESS_CONTROL_USER_APPLICANT()
BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN 
        Roles 
        ON Roles.id_role = AccessControlRoles.id_role
    WHERE 
        Roles.id_role = 7;
END $$
CREATE PROCEDURE ACTIVE_INSCRIPTION_PROCESS(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_inscription_admission_process = 0 AND status_inscription_admission_processs =1;
END$$

CREATE PROCEDURE START_DATE_INSCRIPTION_PROCESS(IN idInscriptionAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess;
END$$

CREATE PROCEDURE END_DATE_INSCRIPTION_PROCESS(IN idInscriptionAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess;
END$$

CREATE PROCEDURE ACTIVE_RECTIFICATION_PERIOD(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_RECTIFICATION_PERIOD(IN idRectificationPeriodAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_RECTIFICATION_PERIOD(IN idRectificationPeriodAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$

CREATE PROCEDURE ACTIVE_REGISTRATION_RATING(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_admission_process = idAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_REGISTRATION_RATING(IN idRegistrationRatingAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_REGISTRATION_RATING(IN idRegistrationRatingAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$
CREATE PROCEDURE ACTIVE_SENDING_NOTIFICATIONS(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$

CREATE PROCEDURE START_DATE_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE START_TIME_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT star_timeof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE END_TIME_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT end_timeof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$

CREATE PROCEDURE GET_APPLICATIONS_WITH_MULTIPLE_RATINGS(IN applicant_id VARCHAR(20),OUT application_count INT)
BEGIN
    SELECT 
        COUNT(DISTINCT app.id_admission_application_number) AS total_applications_with_multiple_ratings
    INTO 
        application_count
    FROM 
        Applications app
    JOIN 
        RatingApplicantsTest rat ON app.id_admission_application_number = rat.id_admission_application_number
    WHERE 
        app.id_applicant = applicant_id
    GROUP BY 
        app.id_applicant
    HAVING 
        COUNT(rat.id_rating_applicant_test) > 1;
END$$

CREATE PROCEDURE GetUndergraduatesByProfessorAndRegionalCenter(
    IN id_professor_param INT,
    IN id_regionalcenter_param INT
)
BEGIN
    SELECT 
        Undergraduates.id_undergraduate,
        Undergraduates.name_undergraduate
    FROM 
        Undergraduates
    INNER JOIN 
        UndergraduatesRegionalCenters 
        ON Undergraduates.id_undergraduate = UndergraduatesRegionalCenters.id_undergraduate
    INNER JOIN 
        DepartmentHead 
        ON Undergraduates.id_department = DepartmentHead.id_department
    WHERE 
        UndergraduatesRegionalCenters.id_regionalcenter = id_regionalcenter_param
        AND DepartmentHead.id_professor = id_professor_param
        AND Undergraduates.status_undergraduate = TRUE
        AND UndergraduatesRegionalCenters.status_undergraduate_Regional_Center = TRUE
        AND DepartmentHead.status_department_head = TRUE;
END $$


CREATE PROCEDURE GetAcademicPeriodicityByPlanningProcess(
    IN p_id_academic_planning_process INT,
    OUT p_id_academic_periodicity INT
)
BEGIN
    SELECT DatesAcademicPeriodicityYear.id_academic_periodicity
    INTO p_id_academic_periodicity
    FROM AcademicPlanningProcess
    INNER JOIN DatesAcademicPeriodicityYear
        ON AcademicPlanningProcess.date_academic_periodicity_academic_planning_process = DatesAcademicPeriodicityYear.id_dates_academic_periodicity_year
    WHERE AcademicPlanningProcess.id_academic_planning_process = p_id_academic_planning_process;
END$$

CREATE PROCEDURE GetRegionalCentersByProfessor(
    IN p_id_professor INT
)
BEGIN
    -- Obtener id_regionalcenter y name_regional_center directamente si existe un id_department válido
    SELECT 
        DepartmentsRegionalCenters.id_regionalcenter, 
        RegionalCenters.name_regional_center
    FROM 
        DepartmentsRegionalCenters
    INNER JOIN 
        RegionalCenters
        ON DepartmentsRegionalCenters.id_regionalcenter = RegionalCenters.id_regional_center
    WHERE 
        DepartmentsRegionalCenters.id_department = (
            SELECT id_department
            FROM DepartmentHead
            WHERE id_professor = p_id_professor
              AND status_department_head = TRUE
        )
        AND DepartmentsRegionalCenters.status_department_regional_center = 1;
END$$

CREATE PROCEDURE ACTIVE_ACADEMIC_PLANNING()
BEGIN
   	SELECT id_academic_planning_process FROM AcademicPlanningProcess WHERE status_academic_planning_process = 1;
END$$

CREATE PROCEDURE START_DATE_ACADEMIC_PLANNING(IN IdAcademicPlanning INT)
BEGIN
   	SELECT start_dateof_academic_planning_process FROM AcademicPlanningProcess WHERE id_academic_planning_process = IdAcademicPlanning AND status_academic_planning_process = 1;
END$$

CREATE PROCEDURE END_DATE_ACADEMIC_PLANNING(IN IdAcademicPlanning INT)
BEGIN
   	SELECT end_dateof_academic_planning_process FROM AcademicPlanningProcess WHERE id_academic_planning_process = IdAcademicPlanning AND status_academic_planning_process = 1;
END$$

CREATE PROCEDURE GetNonServiceClassesByCareer(
    IN p_id_undergraduate INT,
    IN p_academic_periodicity INT
)
BEGIN
    SELECT classes.id_class, 
           classes.name_class
    FROM UndergraduateClass
    INNER JOIN classes ON UndergraduateClass.id_class = classes.id_class
    WHERE UndergraduateClass.id_undergraduate = p_id_undergraduate
      AND classes.class_service = FALSE
      AND classes.status_class = TRUE
      AND UndergraduateClass.status_undergraduate_class = TRUE
      AND classes.academic_periodicity_class = p_academic_periodicity;
END$$

CREATE PROCEDURE GetBuildingsByProfessor(
    IN p_id_regional_center INT,
    IN p_id_professor INT
)
BEGIN
    SELECT 
        Building.id_building AS BuildingID,
        Building.name_building AS BuildingName
    FROM 
        Building
    JOIN 
        BuildingsDepartmentsRegionalsCenters ON Building.id_building = BuildingsDepartmentsRegionalsCenters.building_department_regionalcenter
    JOIN 
        DepartmentsRegionalCenters ON BuildingsDepartmentsRegionalsCenters.department_regional_center = DepartmentsRegionalCenters.id_department_Regional_Center
    JOIN 
        DepartmentHead ON DepartmentsRegionalCenters.id_department = DepartmentHead.id_department
    WHERE 
        DepartmentHead.id_professor = p_id_professor
        AND DepartmentsRegionalCenters.id_regionalcenter = p_id_regional_center
        AND Building.status_building = TRUE
        AND DepartmentsRegionalCenters.status_department_regional_center = TRUE
        AND BuildingsDepartmentsRegionalsCenters.status_building_department_regionalcenter = TRUE
        AND DepartmentHead.status_department_head = TRUE;
END$$


CREATE PROCEDURE GetBuildingsAndClassroomsByProfessor(
    IN p_id_regional_center INT,
    IN p_id_professor INT
)
BEGIN
    SELECT 
        Building.id_building AS BuildingID,
        Building.name_building AS BuildingName,
        Classrooms.id_classroom AS ClassroomID,
        Classrooms.name_classroom AS ClassroomName
    FROM 
        Building
    JOIN 
        BuildingsDepartmentsRegionalsCenters ON Building.id_building = BuildingsDepartmentsRegionalsCenters.building_department_regionalcenter
    JOIN 
        DepartmentsRegionalCenters ON BuildingsDepartmentsRegionalsCenters.department_regional_center = DepartmentsRegionalCenters.id_department_Regional_Center
    JOIN 
        DepartmentHead ON DepartmentsRegionalCenters.id_department = DepartmentHead.id_department
    JOIN 
        ClassroomsBuildingsDepartmentsRegionalCenters ON BuildingsDepartmentsRegionalsCenters.id_building_department_regionalcenter = ClassroomsBuildingsDepartmentsRegionalCenters.building_department_regional_center
    JOIN 
        Classrooms ON ClassroomsBuildingsDepartmentsRegionalCenters.id_classroom = Classrooms.id_classroom
    WHERE 
        DepartmentHead.id_professor = p_id_professor
        AND DepartmentsRegionalCenters.id_regionalcenter = p_id_regional_center
        AND Building.status_building = TRUE
        AND DepartmentsRegionalCenters.status_department_regional_center = TRUE
        AND BuildingsDepartmentsRegionalsCenters.status_building_department_regionalcenter = TRUE
        AND DepartmentHead.status_department_head = TRUE
        AND Classrooms.status_classroom = TRUE
        AND ClassroomsBuildingsDepartmentsRegionalCenters.status_classroom_building_department_regionalcenter = TRUE;
END$$

CREATE PROCEDURE GetActiveSchedules()
BEGIN
    SELECT * 
    FROM AcademicSchedules
    WHERE status_academic_schedules = TRUE;
END $$


CREATE PROCEDURE GetProfessorsAssignedToRegionalCenter(
    IN id_regional_center INT,
    IN id_professor INT
)
BEGIN
    SELECT 
        Professors.id_professor,
        Professors.first_name_professor,
        Professors.second_name_professor,
        Professors.third_name_professor,
        Professors.first_lastname_professor,
        Professors.second_lastname_professor,
        Professors.email_professor,
        Professors.status_professor
    FROM Professors
    JOIN ProfessorsDepartments ON Professors.id_professor = ProfessorsDepartments.id_professor
    JOIN DepartmentHead ON ProfessorsDepartments.id_department = DepartmentHead.id_department
    WHERE Professors.id_regional_center = id_regional_center
    AND DepartmentHead.id_professor = id_professor
    AND Professors.status_professor = TRUE
    AND ProfessorsDepartments.status_professor_department = 'active';
END$$

CREATE PROCEDURE GetWorkingHoursActive()
BEGIN
    SELECT id_working_hour,
           name_working_hour,
           day_week_working_hour,
           check_in_time_working_hour,
           check_out_time_working_hour,
           status_working_hour
    FROM WorkingHours
    WHERE status_working_hour = TRUE;
END $$

CREATE PROCEDURE GetProfessorWorkingHours(IN professor_id INT)
BEGIN
    SELECT ProfessorsDepartmentsWorkingHours.id_working_hour
    FROM ProfessorsDepartments
    JOIN ProfessorsDepartmentsWorkingHours ON ProfessorsDepartments.id_professor_department = ProfessorsDepartmentsWorkingHours.id_professor_department
    WHERE ProfessorsDepartments.id_professor = professor_id 
    AND ProfessorsDepartmentsWorkingHours.status_working_hour = TRUE;
END $$


CREATE PROCEDURE GetProfessorWorkingHoursByDay(IN professor_id INT, IN working_day VARCHAR(20))
BEGIN
    SELECT ProfessorsDepartmentsWorkingHours.id_working_hour
    FROM ProfessorsDepartments
    JOIN ProfessorsDepartmentsWorkingHours ON ProfessorsDepartments.id_professor_department = ProfessorsDepartmentsWorkingHours.id_professor_department
    JOIN WorkingHours ON ProfessorsDepartmentsWorkingHours.id_working_hour = WorkingHours.id_working_hour
    WHERE ProfessorsDepartments.id_professor = professor_id 
    AND ProfessorsDepartmentsWorkingHours.status_working_hour = TRUE
    AND WorkingHours.day_week_working_hour = working_day
    AND WorkingHours.status_working_hour = TRUE;
END $$

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE GET_CLASS_SECTION_ID(
    IN p_id_dates_academic_periodicity_year INT,
    IN p_id_classroom_class_section INT,
    IN p_id_academic_schedules INT,
    IN p_id_class INT
)
BEGIN
    SELECT id_class_section
    FROM ClassSections
    WHERE id_dates_academic_periodicity_year = p_id_dates_academic_periodicity_year
      AND id_classroom_class_section = p_id_classroom_class_section
      AND id_academic_schedules = p_id_academic_schedules
      AND id_class = p_id_class;
END $$

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE GET_CLASS_SECTION_BY_PROFESSOR_AND_SCHEDULE(
    IN p_id_professor_class_section INT,
    IN p_id_academic_schedules INT
)
BEGIN
    SELECT id_class_section
    FROM ClassSections
    WHERE id_professor_class_section = p_id_professor_class_section
      AND id_academic_schedules = p_id_academic_schedules;
END $$

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE INSERT_CLASS_SECTION(
    IN p_id_class INT,
    IN p_id_dates_academic_periodicity_year INT,
    IN p_id_classroom_class_section INT,
    IN p_id_academic_schedules INT,
    IN p_id_professor_class_section INT,
    IN p_numberof_spots_available_class_section INT,
    IN p_status_class_section BOOLEAN
)
BEGIN
    INSERT INTO ClassSections (
        id_class,
        id_dates_academic_periodicity_year,
        id_classroom_class_section,
        id_academic_schedules,
        id_professor_class_section,
        numberof_spots_available_class_section,
        status_class_section
    )
    VALUES (
        p_id_class,
        p_id_dates_academic_periodicity_year,
        p_id_classroom_class_section,
        p_id_academic_schedules,
        p_id_professor_class_section,
        p_numberof_spots_available_class_section,
        p_status_class_section
    );
END $$

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE GET_CLASS_SECTION_BY_DEPARTMENT_AND_REGIONAL_CENTER(
    IN p_department_id INT,
    IN p_regional_center_id INT
)
BEGIN
    SELECT ClassSections.id_class_section,
           ClassSections.id_class,
           classes.name_class,
           ClassSections.id_dates_academic_periodicity_year,
           Classrooms.name_classroom AS classroom_name,
           ClassSections.numberof_spots_available_class_section,
           CONCAT(Professors.first_name_professor, ' ', Professors.first_lastname_professor) AS professor_name,
           AcademicSchedules.start_timeof_classes,
           AcademicSchedules.end_timeof_classes
    FROM ClassSections
    JOIN classes ON ClassSections.id_class = classes.id_class
    JOIN Departments ON classes.department_class = Departments.id_department
    JOIN DepartmentsRegionalCenters ON Departments.id_department = DepartmentsRegionalCenters.id_department
    JOIN Professors ON ClassSections.id_professor_class_section = Professors.id_professor
    JOIN AcademicSchedules ON ClassSections.id_academic_schedules = AcademicSchedules.id_academic_schedules
    JOIN Classrooms ON ClassSections.id_classroom_class_section = Classrooms.id_classroom
    WHERE Departments.id_department = p_department_id
    AND DepartmentsRegionalCenters.id_regionalcenter = p_regional_center_id
    AND ClassSections.status_class_section = TRUE
    AND Professors.status_professor = TRUE  -- Solo obtener profesores activos
    AND AcademicSchedules.status_academic_schedules = TRUE;  -- Solo obtener horarios activos
END $$

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE UPDATE_SPOTS_AVAILABLE(
    IN p_id_class_section INT,
    IN p_new_numberof_spots INT
)
BEGIN
    UPDATE ClassSections
    SET numberof_spots_available_class_section = p_new_numberof_spots
    WHERE id_class_section = p_id_class_section;
END $$


-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 07/12/2024
CREATE PROCEDURE INSERT_CLASS_SECTION_DAY(
    IN p_id_class_section INT,
    IN p_id_day ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'),
    IN p_status_class_sections_days BOOLEAN
)
BEGIN
    INSERT INTO ClassSectionsDays (id_class_section, id_day, status_class_sections_days)
    VALUES (p_id_class_section, p_id_day, p_status_class_sections_days);
END $$

DELIMITER ;


