¿CREATE DATABASE IF NOT EXISTS unah_registration;
USE unah_registration;

-- @author TABLE Roles: Alejandro Moya 20211020462  @created 23/11/2024
CREATE TABLE Roles (
	id_role INT PRIMARY KEY AUTO_INCREMENT,
	role VARCHAR (50) NOT NULL,
	description_role VARCHAR (200) NOT NULL,
	status_role BOOLEAN NOT NULL
);

-- @author TABLE AccessControl: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE AccessControl (
	id_access_control CHAR(8) NOT NULL PRIMARY KEY,
	description_access_control VARCHAR(150) NOT NULL,
	status_access_control BOOLEAN NOT NULL
);

-- @author TABLE AccessControlRoles: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE AccessControlRoles (
    id_role INT NOT NULL,
    id_access_control CHAR(8) NOT NULL,
    status_access_control_roles BOOLEAN NOT NULL,
    CONSTRAINT id_access_control_roles PRIMARY KEY (id_role, id_access_control), 
    FOREIGN KEY (id_role) REFERENCES Roles(id_role),
    FOREIGN KEY (id_access_control) REFERENCES AccessControl(id_access_control)
);

-- @author TABLE UsersAdmissionsAdministrator: Alejandro Moya 20211020462 @created 14/11/2024
CREATE TABLE UsersAdmissionsAdministrator (
	id_user_admissions_administrator INT PRIMARY KEY AUTO_INCREMENT,
	username_user_admissions_administrator VARCHAR(50) UNIQUE NOT NULL,
	password_user_admissions_administrator VARCHAR(100) NOT NULL,
	status_user_admissions_administrator BOOLEAN NOT NULL
);

-- @author TABLE TokenUserAdmissionAdmin: Angel Nolasco 20211021246 @created 24/11/2024
CREATE TABLE TokenUserAdmissionAdmin (
    id_token_user_admission_administrator INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_admissions_administrator INT NOT NULL,
    FOREIGN KEY (id_user_admissions_administrator) REFERENCES UsersAdmissionsAdministrator(id_user_admissions_administrator) ON UPDATE CASCADE
);

-- @author TABLE AcademicYear: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicYear (
    id_academic_year INT PRIMARY KEY AUTO_INCREMENT,
    name_academic_year VARCHAR(100) NOT NULL,
    status_academic_year BOOLEAN NOT NULL
)AUTO_INCREMENT = 2024;

-- @author TABLE AdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE AdmissionProcess (
    id_admission_process INT PRIMARY KEY AUTO_INCREMENT,
    name_admission_process VARCHAR(100) NOT NULL,
    id_academic_year INT NOT NULL,
    start_dateof_admission_process DATE NOT NULL,
    end_dateof_admission_process DATE NOT NULL,
    current_status_admission_process BOOLEAN NOT NULL,
    status_admission_process BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_year) REFERENCES AcademicYear(id_academic_year)
);

-- @author TABLE InscriptionAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE InscriptionAdmissionProcess (
	id_inscription_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_inscription_admission_process DATE NOT NULL,
	end_dateof_inscription_admission_process DATE NOT NULL,
	current_status_inscription_admission_process BOOLEAN NOT NULL,
	status_inscription_admission_processs BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE DocumentValidationAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE DocumentValidationAdmissionProcess (	
	id_document_validation_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_document_validation_admission_process DATE NOT NULL,
	end_dateof_document_validation_admission_process DATE NOT NULL,
	current_status_document_validation_admission_process BOOLEAN NOT NULL,
	status_document_validation_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE AdmissionTestAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE AdmissionTestAdmissionProcess (
	id_admission_test_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	dateof_admission_test_admission_process DATE NOT NULL,
	current_status_admission_test_admission_process BOOLEAN NOT NULL,
	status_admission_test_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE RegistrationRatingAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE RegistrationRatingAdmissionProcess (
	id_registration_rating_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_registration_rating_admission_process DATE NOT NULL,
	end_dateof_registration_rating_admission_process DATE NOT NULL,
	current_status_registration_rating_admission_process BOOLEAN NOT NULL,
	status_sending_registration_rating_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE SendingNotificationsAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE SendingNotificationsAdmissionProcess (
	id_sending_notifications_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_sending_notifications_admission_process DATE NOT NULL,
	end_dateof_sending_notifications_admission_process DATE NOT NULL,
	star_timeof_sending_notifications_admission_process TIME NOT NULL,
	end_timeof_sending_notifications_admission_process TIME NOT NULL,
	current_status_sending_notifications_admission_process BOOLEAN NOT NULL,
	status_sending_notifications_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE AcceptanceAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE AcceptanceAdmissionProcess (
	id_acceptance_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_acceptance_admission_process DATE NOT NULL,
	end_dateof_acceptance_admission_process DATE NOT NULL,
	current_status_acceptance_admission_process BOOLEAN NOT NULL,
	status_acceptance_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE RectificationPeriodAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE RectificationPeriodAdmissionProcess (
	id_rectification_period_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_rectification_period_admission_process DATE NOT NULL,
	end_dateof_rectification_period_admission_process DATE NOT NULL,
	current_status_rectification_period_admission_process BOOLEAN NOT NULL,
	status_rectification_period_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE DownloadApplicantAdmittedInformationAdmissionProcess: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE DownloadApplicantAdmittedInformationAdmissionProcess (
	id_download_applicant_information_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_download_applicant_information_admission_process DATE NOT NULL,
	end_dateof_download_applicant_information_admission_process DATE NOT NULL,
	current_status_download_applicant_information_admission_process BOOLEAN NOT NULL,
	status_download_applicant_information_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);
	
-- @author TABLE RegionalCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE RegionalCenters (
    id_regional_center INT PRIMARY KEY AUTO_INCREMENT,
    name_regional_center VARCHAR(100) NOT NULL,
    acronym_regional_center VARCHAR(20) NOT NULL,
    location_regional_center VARCHAR(100) NOT NULL,
    email_regional_center VARCHAR(100) NOT NULL,
    phone_number_regional_center VARCHAR(20) NOT NULL,
    address_regional_center VARCHAR(255) NOT NULL,
    status_regional_center BOOLEAN NOT NULL
);

-- @author TABLE RolesUsersAdmissionsAdministrator: Alejandro Moya 20211020462 @created 14/11/2024
CREATE TABLE RolesUsersAdmissionsAdministrator (
	id_user_admissions_administrator INT NOT NULL,
	id_role_admissions_administrator INT NOT NULL,
	status_role_admissions_administrator BOOLEAN NOT NULL,
	id_regional_center INT,
	CONSTRAINT id_rol_user_admission_administrator PRIMARY KEY (id_user_admissions_administrator, id_role_admissions_administrator), 
	FOREIGN KEY (id_user_admissions_administrator) REFERENCES  UsersAdmissionsAdministrator(id_user_admissions_administrator),
	FOREIGN KEY (id_role_admissions_administrator) REFERENCES Roles(id_role),
	FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE NumberExtensions: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE NumberExtensions (
    id_number_extension INT PRIMARY KEY AUTO_INCREMENT,
    number_extension VARCHAR(20) NOT NULL,
    status_number_extension BOOLEAN NOT NULL
);

-- @author TABLE NumberExtensionsRegionalCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE NumberExtensionsRegionalCenters (
    id_number_extension_regional_center INT PRIMARY KEY AUTO_INCREMENT,
    id_number_extension INT NOT NULL,
    id_regional_center INT NOT NULL,
    status_number_extension_regional_center BOOLEAN NOT NULL,
    FOREIGN KEY (id_number_extension) REFERENCES NumberExtensions(id_number_extension),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE Faculties: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Faculties (
    id_faculty INT PRIMARY KEY AUTO_INCREMENT,
    name_faculty VARCHAR(100) NOT NULL,
    address_faculty VARCHAR(255) NOT NULL,
    phone_number_faculty VARCHAR(20) NOT NULL,
    email_faculty VARCHAR(100) NOT NULL,
    status_faculty BOOLEAN NOT NULL
);

-- @author TABLE Departments: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Departments (
    id_department INT PRIMARY KEY AUTO_INCREMENT,
    name_departmet VARCHAR(100) NOT NULL,
    id_faculty INT NOT NULL,
    status_department BOOLEAN NOT NULL,
    FOREIGN KEY (id_faculty) REFERENCES Faculties(id_faculty)
);

-- @author TABLE Undergraduates: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Undergraduates (
    id_undergraduate INT PRIMARY KEY AUTO_INCREMENT,
    name_undergraduate VARCHAR(100) NOT NULL,
    id_department INT NOT NULL,
    status_undergraduate BOOLEAN NOT NULL, 
    duration_undergraduate FLOAT(2,1) NOT NULL,
    mode_undergraduate ENUM('Presencial', 'Virtual', 'Distancia') NOT NULL,
    study_plan_undergraduate LONGBLOB,
    CHECK (duration_undergraduate > 0),
    FOREIGN KEY (id_department) REFERENCES Departments(id_department)
);

-- @author TABLE DepartmentsRegionalCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DepartmentsRegionalCenters (
    id_department_Regional_Center INT PRIMARY KEY AUTO_INCREMENT,
    id_department INT NOT NULL,
    id_regionalcenter INT NOT NULL,
    status_department_regional_center BOOLEAN NOT NULL,
    FOREIGN KEY (id_department) REFERENCES Departments(id_department),
    FOREIGN KEY (id_regionalcenter) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE UndergraduatesRegionalCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE UndergraduatesRegionalCenters (
    id_undergraduate_Regional_Center INT PRIMARY KEY AUTO_INCREMENT,
    id_undergraduate INT NOT NULL,
    id_regionalcenter INT NOT NULL,
    status_undergraduate_Regional_Center BOOLEAN NOT NULL,
    FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
    FOREIGN KEY (id_regionalcenter) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE ConfirmationEmailApplicants: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ConfirmationEmailApplicants (
	id_confirmation_email_applicant INT PRIMARY KEY AUTO_INCREMENT,
	applicant_id_email_confirmation VARCHAR(20) NOT NULL,
	email_sent_email_confirmation BOOLEAN NOT NULL,
	date_email_sent_email_confirmation DATETIME DEFAULT CURRENT_TIMESTAMP,
	confirmation_code_email_confirmation VARCHAR(255) NOT NULL,
	experied_email_confirmation DATETIME NOT NULL,
	status_email_confirmation ENUM('pending', 'used', 'expired') DEFAULT 'pending',
	attempts_email_confirmation INT DEFAULT 0
);

-- @author TABLE Applicants: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Applicants (
    id_applicant VARCHAR(20) PRIMARY KEY,
    first_name_applicant VARCHAR(50) NOT NULL,
    second_name_applicant VARCHAR(50),
    third_name_applicant VARCHAR(50),
    first_lastname_applicant VARCHAR(50) NOT NULL,
    second_lastname_applicant VARCHAR(50),
    email_applicant VARCHAR(100) UNIQUE NOT NULL,
    phone_number_applicant VARCHAR(20) NOT NULL,
    address_applicant VARCHAR(255)NOT NULL,
    image_id_applicant MEDIUMBLOB NOT NULL,
    status_applicant BOOLEAN NOT NULL
);

-- @author TABLE ApplicantType: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ApplicantType (
    id_aplicant_type INT PRIMARY KEY AUTO_INCREMENT,
    name_aplicant_type VARCHAR(50) NOT NULL,
    admission_test_aplicant BOOLEAN NOT NULL,
    status_aplicant_type BOOLEAN NOT NULL
);

-- @author TABLE Applications: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Applications (
    id_admission_application_number INT PRIMARY KEY AUTO_INCREMENT,
    id_admission_process INT NOT NULL,
    id_applicant VARCHAR(20) NOT NULL,
    id_aplicant_type INT NOT NULL,
    secondary_certificate_applicant MEDIUMBLOB NOT NULL,
    idregional_center INT NOT NULL,
    regionalcenter_admissiontest_applicant INT NOT NULL,
    intendedprimary_undergraduate_applicant INT NOT NULL,
    intendedsecondary_undergraduate_applicant INT NOT NULL,
    status_application BOOLEAN NOT NULL,
    FOREIGN KEY (id_applicant) REFERENCES Applicants(id_applicant),
    FOREIGN KEY (id_aplicant_type) REFERENCES ApplicantType(id_aplicant_type),
    FOREIGN KEY (idregional_center) REFERENCES RegionalCenters(id_regional_center),
    FOREIGN KEY (regionalcenter_admissiontest_applicant) REFERENCES RegionalCenters(id_regional_center),
    FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process),
    FOREIGN KEY (intendedprimary_undergraduate_applicant) REFERENCES Undergraduates(id_undergraduate),
    FOREIGN KEY (intendedsecondary_undergraduate_applicant) REFERENCES Undergraduates(id_undergraduate)
)AUTO_INCREMENT = 1001;

-- @author TABLE CheckApplicantApplications: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE CheckApplicantApplications (
    id_check_applicant_applications INT PRIMARY KEY AUTO_INCREMENT,
    id_applicant VARCHAR(20) NOT NULL,
    id_admission_application_number INT NOT NULL,
    verification_status_data_applicant BOOLEAN NOT NULL,
    date_check_applicant_applications DATE NOT NULL,
    revision_status_check_applicant_applications BOOLEAN NOT NULL,
    admissions_administrator_check_applicant_applications INT NOT NULL,
    description_general_check_applicant_applications VARCHAR(255),
    FOREIGN KEY (id_applicant) REFERENCES Applicants(id_applicant),
    FOREIGN KEY (id_admission_application_number) REFERENCES Applications(id_admission_application_number),
    FOREIGN KEY (admissions_administrator_check_applicant_applications) REFERENCES UsersAdmissionsAdministrator(id_user_admissions_administrator)
);

-- @author TABLE  CheckErrorsApplicantApplications: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE  CheckErrorsApplicantApplications(
	id_check_errors_applicant_applications INT PRIMARY KEY AUTO_INCREMENT,
	id_check_applicant_applications INT NOT NULL,
	incorrect_data VARCHAR(100) NOT NULL,
	description_incorrect_data VARCHAR(255) NOT NULL,
	FOREIGN KEY (id_check_applicant_applications) REFERENCES CheckApplicantApplications(id_check_applicant_applications)
);

-- @author TABLE UsersApplicants: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE UsersApplicants (
	id_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
	username_user_applicant VARCHAR(50) UNIQUE NOT NULL,
	password_user_applicant VARCHAR(100) NOT NULL,
	status_user_applicant BOOLEAN NOT NULL,
	FOREIGN KEY (username_user_applicant) REFERENCES Applicants(id_applicant)
);

-- @author TABLE TokenUserApplicant: Angel Nolasco 20211021246 @created 24/11/2024
CREATE TABLE TokenUserApplicant (
    id_token_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_applicant INT NOT NULL,
    FOREIGN KEY (id_user_applicant) REFERENCES UsersApplicants(id_user_applicant)
);
-- @author TABLE TypesAdmissionTests: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE TypesAdmissionTests (
    id_type_admission_tests INT PRIMARY KEY AUTO_INCREMENT,
    name_type_admission_tests VARCHAR(100) NOT NULL,
    status_type_admission_tests BOOLEAN NOT NULL
);

-- @author TABLE UndergraduateTypesAdmissionTests: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE UndergraduateTypesAdmissionTests (
    id_undergraduate_type_admission_tests INT PRIMARY KEY AUTO_INCREMENT,
    id_type_admission_tests INT NOT NULL, 
    id_undergraduate INT NOT NULL,
    required_rating DECIMAL(5, 2) NOT NULL,
    status_undergraduate_type_admission_tests BOOLEAN NOT NULL
);

-- @author TABLE RatingApplicantsTest: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE RatingApplicantsTest (
    id_rating_applicant_test INT PRIMARY KEY AUTO_INCREMENT,
    id_admission_application_number INT NOT NULL,
    id_type_admission_tests INT NOT NULL,
    rating_applicant DECIMAL(5, 2) NOT NULL,
    status_rating_applicant_test BOOLEAN NOT NULL,
    FOREIGN KEY (id_admission_application_number) REFERENCES Applications(id_admission_application_number),
    FOREIGN KEY (id_type_admission_tests) REFERENCES TypesAdmissionTests(id_type_admission_tests)
);

-- @author TABLE ResolutionIntendedUndergraduateApplicant: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ResolutionIntendedUndergraduateApplicant (
    id_resolution_intended_undergraduate_applicant INT PRIMARY KEY AUTO_INCREMENT,
    id_admission_application_number INT NOT NULL,
    intended_undergraduate_applicant INT NOT NULL,
    resolution_intended BOOLEAN NOT NULL,
    status_resolution_intended_undergraduate_applicant BOOLEAN NOT NULL,
    FOREIGN KEY (id_admission_application_number) REFERENCES Applications(id_admission_application_number),
    FOREIGN KEY (intended_undergraduate_applicant) REFERENCES Undergraduates(id_undergraduate)
);

-- @author TABLE NotificationsApplicationsResolution: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE NotificationsApplicationsResolution (
    id_notification_application_resolution INT PRIMARY KEY AUTO_INCREMENT,
    id_resolution_intended_undergraduate_applicant INT NOT NULL,
    email_sent_application_resolution BOOLEAN NOT NULL,
    date_email_sent_application_resolution DATE NOT NULL,
    FOREIGN KEY (id_resolution_intended_undergraduate_applicant) REFERENCES ResolutionIntendedUndergraduateApplicant(id_resolution_intended_undergraduate_applicant)
);

-- @author TABLE ApplicantAcceptance: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ApplicantAcceptance (
    id_applicant_acceptance INT PRIMARY KEY AUTO_INCREMENT,
    id_notification_application_resolution INT NOT NULL,
    id_applicant VARCHAR(20) NOT NULL,
    date_applicant_acceptance DATE NOT NULL,
    applicant_acceptance BOOLEAN NOT NULL,
    status_applicant_acceptance BOOLEAN NOT NULL,
    id_admission_process INT NOT NULL,
    status_admission_process BOOLEAN NOT NULL,
    FOREIGN KEY (id_notification_application_resolution) REFERENCES NotificationsApplicationsResolution(id_notification_application_resolution),
    FOREIGN KEY (id_applicant) REFERENCES Applicants(id_applicant),
    FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

-- @author TABLE Holydays: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Holydays (
	id_holyday INT PRIMARY KEY AUTO_INCREMENT,
	holyday_name VARCHAR(50) NOT NULL,
	holyday_date VARCHAR(5) NOT NULL,
	status_holyday	BOOLEAN NOT NULL
);

-- @author TABLE Vacations: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Vacations (
	id_vacation INT PRIMARY KEY AUTO_INCREMENT,
	vacation_date VARCHAR(5) NOT NULL,
	academic_year_vacation INT NOT NULL,
	status_vacation BOOLEAN NOT NULL,
	FOREIGN KEY (academic_year_vacation) REFERENCES AcademicYear(id_academic_year)
);

-- @authorTABLE AcademicPeriodicity : Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicPeriodicity (
	id_academic_periodicity INT PRIMARY KEY AUTO_INCREMENT,
	description_academic_periodicity VARCHAR(50) NOT NULL,
	numberof_months_academic_periodicity TINYINT UNSIGNED NOT NULL CHECK (numberof_months_academic_periodicity BETWEEN 1 AND 12),
	status_academic_periodicity BOOLEAN NOT NULL
);

-- @author TABLE DatesAcademicPeriodicityYear: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DatesAcademicPeriodicityYear (
	id_dates_academic_periodicity_year INT PRIMARY KEY AUTO_INCREMENT,
	id_academic_periodicity INT NOT NULL,
	id_academic_year INT NOT NULL,
	start_dateof_academic_periodicity DATE NOT NULL,
	end_dateof_academic_periodicity DATE NOT NULL,
	start_dateof_class_enrollment DATE NOT NULL,
	end_dateof_class_enrollment DATE NOT NULL,
	start_dateof_classes DATE NOT NULL,
	end_dateof_classes DATE NOT NULL,
	start_dateof_exam_retake_students DATE NOT NULL,
	end_dateof_exam_retake_students DATE NOT NULL,
	start_datefor_recording_grades DATE NOT NULL,
	end_datefor_recording_grades DATE NOT NULL,
	description_dates_academic_periodicity_year VARCHAR(50) NOT NULL,
	status_dates_academic_periodicity_year BOOLEAN NOT NULL,
	FOREIGN KEY (id_academic_periodicity) REFERENCES  AcademicPeriodicity(id_academic_periodicity),
	FOREIGN KEY (id_academic_year) REFERENCES AcademicYear(id_academic_year)
);

-- @author TABLE  StudentRegistrationProcess: Alejandro Moya 20211020462 @created
CREATE TABLE  StudentRegistrationProcess (
	id_students_registration_process INT PRIMARY KEY AUTO_INCREMENT,
	start_dateof_creation_students_registration_process DATE NOT NULL,
	end_dateof_creation_students_registration_process DATE NOT NULL,
	start_date_notification_to_students_registration_process DATE NOT NULL,
	end_dateof_notification_students_registration_process DATE NOT NULL,
	timeof_sending_notifications_students_registration_process TIME NOT NULL,
	current_status_students_registration_process BOOLEAN NOT NULL,
	status_students_registration_process BOOLEAN NOT NULL
);

-- @author TABLE TypesEnrollmentConditions: Alejandro Moya 20211020462 @created
CREATE TABLE TypesEnrollmentConditions (
	id_type_enrollment_conditions INT PRIMARY KEY AUTO_INCREMENT,
	maximum_student_global_average DECIMAL(4,2) UNSIGNED NOT NULL,
	minimum_student_global_average DECIMAL(4,2) UNSIGNED NOT NULL,
	status_student_global_average BOOLEAN NOT NULL,
	maximum_student_period_average DECIMAL(4,2) UNSIGNED NOT NULL,
	minimum_student_period_average DECIMAL(4,2) UNSIGNED NOT NULL,
	status_type_enrollment_conditions BOOLEAN NOT NULL
);

-- @author TABLE EnrollmentProcess: Alejandro Moya 20211020462 @created
CREATE TABLE EnrollmentProcess (
	id_enrollment_process INT PRIMARY KEY AUTO_INCREMENT,
	id_dates_academic_periodicity_year INT NOT NULL,
	status_enrollment_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_dates_academic_periodicity_year) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year)
);

-- @author TABLE DatesEnrollmentProcess: Alejandro Moya 20211020462 @created
CREATE TABLE DatesEnrollmentProcess (
	id_dates_enrollment_process INT PRIMARY KEY AUTO_INCREMENT,
	id_enrollment_process INT NOT NULL,
	id_type_enrollment_conditions INT NOT NULL,
	day_available_enrollment_process DATE NOT NULL,
	start_time_available_enrollment_process TIME NOT NULL,
	end_time_available_enrollment_process TIME NOT NULL,
	status_date_enrollment_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_enrollment_process) REFERENCES  EnrollmentProcess(id_enrollment_process),
	FOREIGN KEY (id_type_enrollment_conditions) REFERENCES  TypesEnrollmentConditions(id_type_enrollment_conditions)
);

-- @author TABLE UndergraduateChangeStudentsProcess: Alejandro Moya 20211020462 @created
CREATE TABLE UndergraduateChangeStudentsProcess (
	id_undergraduate_change_student_process INT PRIMARY KEY AUTO_INCREMENT,
	academic_year_undergraduate_change_student_process INT NOT NULL,
	start_dateof_undergraduate_change_student_process DATE NOT NULL,
	end_dateof_undergraduate_change_student_process DATE NOT NULL,
	status_undergraduate_change_student_process BOOLEAN NOT NULL,
	FOREIGN KEY (academic_year_undergraduate_change_student_process) REFERENCES AcademicYear(id_academic_year)
);

-- @author TABLE CancellationExceptionalClassesProcess: Alejandro Moya 20211020462 @created
CREATE TABLE CancellationExceptionalClassesProcess ( 
	id_cancellation_exceptional_classes_process INT PRIMARY KEY AUTO_INCREMENT,
	academic_periodicity INT NOT NULL,
	id_undergraduate INT NOT NULL,
	start_dateof_cancellation_exceptional_classes_process DATE NOT NULL,
	end_dateof_cancellation_exceptional_classes_process DATE NOT NULL,
	status_cancellation_exceptional_classes_process BOOLEAN NOT NULL,
	FOREIGN KEY (academic_periodicity) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year),
	FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate)
);

-- @author TABLE AcademicPlanningProcess: Alejandro Moya 20211020462 @created 03/11/2024
CREATE TABLE AcademicPlanningProcess(
	id_academic_planning_process INT PRIMARY KEY AUTO_INCREMENT,
	date_academic_periodicity_academic_planning_process INT NOT NULL,
	start_dateof_academic_planning_process DATE NOT NULL,
	end_dateof_academic_planning_process DATE NOT NULL,
	status_academic_planning_process BOOLEAN NOT NULL,
	FOREIGN KEY (date_academic_periodicity_academic_planning_process) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year)
);

-- @author TABLE AcademicSchedules: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicSchedules (
	id_academic_schedules INT PRIMARY KEY AUTO_INCREMENT,
	start_timeof_classes TIME NOT NULL,
	end_timeof_classes TIME NOT NULL,
	status_academic_schedules BOOLEAN NOT NULL
);

-- @author TABLE Building: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Building (
	id_building INT PRIMARY KEY AUTO_INCREMENT,
	name_building VARCHAR(20) NOT NULL,
	regionalcenters_building INT NOT NULL,
	status_building BOOLEAN NOT NULL,
	FOREIGN KEY (regionalcenters_building) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE BuildingLevels: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE BuildingLevels (
	id_building_level INT PRIMARY KEY AUTO_INCREMENT,
	id_building INT NOT NULL,
	description_building_level VARCHAR(100) NOT NULL,
	status_building_level BOOLEAN NOT NULL,
	FOREIGN KEY (id_building) REFERENCES Building(id_building)
);

-- @author TABLE Classrooms: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Classrooms (
	id_classroom INT PRIMARY KEY AUTO_INCREMENT,
	name_classroom VARCHAR(20) NOT NULL,
	description_classroom VARCHAR(255) NOT NULL,
	building_level_classroom  INT NOT NULL,
	status_classroom BOOLEAN NOT NULL,
	FOREIGN KEY (building_level_classroom) REFERENCES  BuildingLevels(id_building_level)
);

-- @author TABLE BuildingsDepartmentsRegionalsCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE BuildingsDepartmentsRegionalsCenters (
	id_building_department_regionalcenter INT PRIMARY KEY AUTO_INCREMENT,
	department_regional_center INT NOT NULL,
	building_department_regionalcenter  INT NOT NULL,
	status_building_department_regionalcenter BOOLEAN NOT NULL,
	FOREIGN KEY (department_regional_center) REFERENCES DepartmentsRegionalCenters(id_department_Regional_Center),
	FOREIGN KEY (building_department_regionalcenter) REFERENCES Building(id_building)
);

-- @author TABLE ClassroomsBuildingsDepartmentsRegionalCenters: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ClassroomsBuildingsDepartmentsRegionalCenters (
	id_classroom_building_department_regionalcenter INT PRIMARY KEY AUTO_INCREMENT,
	building_department_regional_center INT NOT NULL,
	id_classroom INT NOT NULL,
	status_classroom_building_department_regionalcenter BOOLEAN NOT NULL,
	FOREIGN KEY (building_department_regional_center) REFERENCES BuildingsDepartmentsRegionalsCenters(id_building_department_regionalcenter),
	FOREIGN KEY (id_classroom) REFERENCES Classrooms(id_classroom)
);

-- @author TABLE classes: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE classes (
	id_class INT PRIMARY KEY AUTO_INCREMENT,
	name_class VARCHAR(100) NOT NULL,
	description_class VARCHAR(255) NOT NULL,
	credit_units INT UNSIGNED NOT NULL,
	hours_required_week TINYINT UNSIGNED NOT NULL,
	department_class INT NOT NULL,
	academic_periodicity_class INT NOT NULL,
	class_service BOOLEAN NOT NULL,
	status_class BOOLEAN NOT NULL,
	FOREIGN KEY (department_class) REFERENCES Departments(id_department),
	FOREIGN KEY (academic_periodicity_class) REFERENCES AcademicPeriodicity(id_academic_periodicity)
);

-- @author TABLE UndergraduateClass: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE UndergraduateClass (
	id_undergraduate_class INT PRIMARY KEY AUTO_INCREMENT,
	id_undergraduate INT NOT NULL,
	id_class INT NOT NULL,
	status_undergraduate_class BOOLEAN NOT NULL,
	FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
	FOREIGN KEY (id_class) REFERENCES classes(id_class)
);

-- @author TABLE RequirementUndergraduateClass: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE RequirementUndergraduateClass (
	id_requirement_undergraduate_class INT PRIMARY KEY AUTO_INCREMENT,
	id_undergraduate_class INT NOT NULL,
	id_class INT NOT NULL,
	status_requirement_undergraduate_class BOOLEAN NOT NULL,
	FOREIGN KEY (id_undergraduate_class) REFERENCES UndergraduateClass(id_undergraduate_class),
	FOREIGN KEY (id_class) REFERENCES classes(id_class)
);

-- @author TABLE ProfessorsObligations: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ProfessorsObligations (
	id_professor_obligation  INT PRIMARY KEY AUTO_INCREMENT,
	maximum_credit_units_professor_obligation TINYINT UNSIGNED NOT NULL,
	minimum_credit_units_professor_obligation TINYINT UNSIGNED NOT NULL,
	status_professor_oblgation BOOLEAN NOT NULL
);

-- @author: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DepartmentHeadObligations (
	id_department_head_obligations INT PRIMARY KEY AUTO_INCREMENT,
	credit_units_department_head_obligations TINYINT UNSIGNED NOT NULL,
	status_units_department_head_obligations BOOLEAN NOT NULL
);

-- @author TABLE AcademicCoordinatorObligations: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicCoordinatorObligations (
	id_academic_coordinator_obligations INT PRIMARY KEY AUTO_INCREMENT,
	credit_units_academic_coordinator_obligations TINYINT UNSIGNED NOT NULL,
	status_academic_coordinator_obligations BOOLEAN NOT NULL
);

-- @author TABLE WorkingHours: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE WorkingHours (
    id_working_hour INT AUTO_INCREMENT PRIMARY KEY,
    name_working_hour VARCHAR(100) NOT NULL,
    day_week_working_hour VARCHAR(20) NOT NULL,
    check_in_time_working_hour TIME NOT NULL,
    check_out_time_working_hour TIME NOT NULL,
    status_working_hour BOOLEAN NOT NULL
);

-- @author TABLE Professors: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE Professors (
    id_professor INT AUTO_INCREMENT PRIMARY KEY,
    first_name_professor VARCHAR(50) NOT NULL,
    second_name_professor VARCHAR(50),
    third_name_professor VARCHAR(50),
    first_lastname_professor VARCHAR(50) NOT NULL,
    second_lastname_professor VARCHAR(50),
    email_professor VARCHAR (100) NOT NULL,
    picture_professor MEDIUMBLOB,
    id_professors_obligations INT NOT NULL,
    id_regional_center INT NOT NULL,
    status_professor BOOLEAN NOT NULL,
    FOREIGN KEY (id_professors_obligations) REFERENCES ProfessorsObligations(id_professor_obligation),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters( id_regional_center)
);

-- @author TABLE ProfessorsDepartments: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ProfessorsDepartments (
    id_professor_department INT AUTO_INCREMENT PRIMARY KEY,
    id_department INT NOT NULL,
    id_professor INT NOT NULL,
    status_professor_department ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (id_department) REFERENCES Departments(id_department),
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor)
);

-- @author TABLE ProfessorsDepartmentsWorkingHours: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE ProfessorsDepartmentsWorkingHours (
    id_professor_department_working_hour INT AUTO_INCREMENT PRIMARY KEY,
    id_professor_department INT NOT NULL,
    id_working_hour INT NOT NULL,
    id_dates_academic_periodicity_year INT NOT NULL,
    status_working_hour BOOLEAN NOT NULL,
    FOREIGN KEY (id_professor_department) REFERENCES ProfessorsDepartments(id_professor_department),
    FOREIGN KEY (id_working_hour) REFERENCES WorkingHours(id_working_hour),
    FOREIGN KEY (id_dates_academic_periodicity_year) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year)
);

-- @author TABLE DepartmentHead: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DepartmentHead (
    id_department_head INT AUTO_INCREMENT PRIMARY KEY,
    id_professor INT NOT NULL,
    id_department INT NOT NULL,
    id_department_head_obligations INT NOT NULL,
    status_department_head BOOLEAN NOT NULL,
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor),
    FOREIGN KEY (id_department) REFERENCES Departments(id_department),
    FOREIGN KEY (id_department_head_obligations) REFERENCES DepartmentHeadObligations(id_department_head_obligations)
);

-- @author TABLE AcademicCoordinator: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicCoordinator (
    id_academic_coordinator INT AUTO_INCREMENT PRIMARY KEY,
    id_professor INT NOT NULL,
    id_undergraduate INT NOT NULL,
    id_academic_coordinator_obligations INT NOT NULL,
    status_academic_coordinator ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor),
    FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
    FOREIGN KEY (id_academic_coordinator_obligations) REFERENCES AcademicCoordinatorObligations(id_academic_coordinator_obligations)
);

-- @author TABLE AcademicCoordinatorValidityPeriod: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicCoordinatorValidityPeriod (
    id_academic_coordinator_validity_period INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    start_date_academic_coordinator_validity_period DATE NOT NULL,
    end_date_academic_coordinator_validity_period DATE NOT NULL,
    actual_end_date_academic_coordinator_validity_period DATE,
    status_academic_coordinator_validity_period BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator)
);

-- @author TABLE DepartmentHeadValidityPeriod: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DepartmentHeadValidityPeriod (
    id_department_head_validity_period INT AUTO_INCREMENT PRIMARY KEY,
    id_department_head INT NOT NULL,
    start_date_department_head_validity_period DATE NOT NULL,
    end_date_department_head_validity_period DATE NOT NULL,
    actual_end_date_department_head_validity_period DATE,
    status_department_head_validity_period BOOLEAN NOT NULL,
FOREIGN KEY (id_department_head) REFERENCES DepartmentHead(id_department_head)
);

-- @author TABLE AcademicCoordinatorWorkingHours: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE AcademicCoordinatorWorkingHours (
    id_academic_coordinator_working_hours INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_working_hour INT NOT NULL,
    status_academic_coordinator_working_hours BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_working_hour) REFERENCES WorkingHours(id_working_hour)
);

-- @author TABLE DepartmentHeadWorkingHours: Alejandro Moya 20211020462 @created 09/11/2024
CREATE TABLE DepartmentHeadWorkingHours (
    id_department_head_working_hours INT AUTO_INCREMENT PRIMARY KEY,
    id_department_head INT NOT NULL,
    id_working_hour INT NOT NULL,
    status_department_head_working_hours BOOLEAN NOT NULL,
    FOREIGN KEY (id_department_head) REFERENCES DepartmentHead(id_department_head),
    FOREIGN KEY (id_working_hour) REFERENCES WorkingHours(id_working_hour)
);

-- @author TABLE UsersFacultiesAdministrator: Alejandro Moya 20211020462 @created 14/11/2024
CREATE TABLE UsersFacultiesAdministrator (
    id_user_faculties_administrator INT PRIMARY KEY AUTO_INCREMENT,
    username_user_faculties_administrator VARCHAR(50) UNIQUE NOT NULL,
    password_user_faculties_administrator VARCHAR(100) NOT NULL,
    id_faculty INT NOT NULL,
    status_user_faculties_administrator BOOLEAN NOT NULL,
    FOREIGN KEY (id_faculty) REFERENCES Faculties(id_faculty)	
);

-- @author TABLE RolesUsersFacultiesAdministrator: Alejandro Moya 20211020462 @created 14/11/2024
CREATE TABLE RolesUsersFacultiesAdministrator (
    id_user_faculties_administrator INT NOT NULL,
    id_role_faculties_administrator INT NOT NULL,
    status_role_faculties_administrator BOOLEAN NOT NULL,
    CONSTRAINT id_role_user_faculties_administrator PRIMARY KEY (id_user_faculties_administrator, id_role_faculties_administrator),
    FOREIGN KEY (id_user_faculties_administrator) REFERENCES UsersFacultiesAdministrator(id_user_faculties_administrator),
    FOREIGN KEY (id_role_faculties_administrator) REFERENCES Roles(id_role)
);

-- @author TABLE UsersRegistryAdministrator: Alejandro Moya 20211020462 @created 01/12/2024
CREATE TABLE UsersRegistryAdministrator (
    id_user_registry_administrator INT PRIMARY KEY AUTO_INCREMENT,
    username_user_registry_administrator VARCHAR(50) UNIQUE NOT NULL,
    password_user_registry_administrator VARCHAR(100) NOT NULL,
    status_user_registry_administrator BOOLEAN NOT NULL
);

-- @author TABLE RolesUsersRegistryAdministrator: Alejandro Moya 20211020462 @created 01/12/2024
CREATE TABLE RolesUsersRegistryAdministrator (
    id_user_registry_administrator INT NOT NULL,
    id_role_registry_administrator INT NOT NULL,
    status_role_registry_administrator BOOLEAN NOT NULL,
    id_regional_center INT NOT NULL,
    CONSTRAINT id_role_user_registry_administrator PRIMARY KEY (id_user_registry_administrator, id_role_registry_administrator),
    FOREIGN KEY (id_user_registry_administrator) REFERENCES UsersRegistryAdministrator(id_user_registry_administrator),
    FOREIGN KEY (id_role_registry_administrator) REFERENCES Roles(id_role),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE TokenUserFacultiesAdministrator: Alejandro Moya 20211020462 @created 01/12/2024
CREATE TABLE TokenUserFacultiesAdministrator (
    id_token_user_faculties_administrator INT PRIMARY KEY AUTO_INCREMENT,
    token_faculties_administrator VARCHAR(512) UNIQUE,
    id_user_faculties_administrator INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_faculties_administrator) REFERENCES UsersFacultiesAdministrator(id_user_faculties_administrator)
);

-- @author TABLE TokenUserRegistryAdministrator: Alejandro Moya 20211020462 @created 01/12/2024
CREATE TABLE TokenUserRegistryAdministrator (
    id_token_user_registry_administrator INT PRIMARY KEY AUTO_INCREMENT,
    token_registry_administrator VARCHAR(512) UNIQUE,
    id_user_registry_administrator INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_registry_administrator) REFERENCES UsersRegistryAdministrator(id_user_registry_administrator)
);

-- @author TABLE Students: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE Students (
    id_student VARCHAR(13) PRIMARY KEY,
    institutional_email_student VARCHAR(100) UNIQUE NOT NULL,
    id_card_student VARCHAR(50) UNIQUE NOT NULL,
    first_name_student VARCHAR(50) NOT NULL,
    second_name_student VARCHAR(50),
    third_name_student VARCHAR(50),
    first_lastname_student VARCHAR(50) NOT NULL,
    second_lastname_student VARCHAR(50),
    address_student VARCHAR(255) NOT NULL,
    email_student VARCHAR(100) NOT NULL,
    phone_number_student VARCHAR(20) NOT NULL,
    status_student BOOLEAN NOT NULL
);

-- @author TABLE StudentsRegionalCenters: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE StudentsRegionalCenters (
    id_regional_center_student INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_regional_center INT NOT NULL,
    status_regional_center_student BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE StudentsUndergraduates: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE StudentsUndergraduates (
    id_student_undergraduate INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_undergraduate INT NOT NULL,
    status_student_undergraduate BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate)
);
 
-- @author TABLE StudentClassStatus: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE StudentClassStatus (
    id_student_class_status INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_class INT NOT NULL,
    class_status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_class) REFERENCES classes(id_class)
);

-- @author TABLE StudentProfile: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE StudentProfile (
    id_student_profile INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    first_student_profile_picture MEDIUMBLOB,
    second_student_profile_picture MEDIUMBLOB,
    third_student_profile_picture MEDIUMBLOB,
    student_personal_description TEXT,
    status_student_profile BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);

-- @author TABLE UsersStudents: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE UsersStudents (
    id_user_student INT PRIMARY KEY AUTO_INCREMENT,
    username_user_student VARCHAR(13) UNIQUE NOT NULL,
    password_user_student VARCHAR(100) NOT NULL,
    status_user_student BOOLEAN NOT NULL,
    FOREIGN KEY (username_user_student) REFERENCES Students(id_student)
);

-- @author TABLE RolesUsersStudent: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE RolesUsersStudent (
    id_user_student INT NOT NULL,
    id_role_student INT NOT NULL,
    status_role_student BOOLEAN NOT NULL,
    CONSTRAINT id_role_user_student PRIMARY KEY (id_user_student, id_role_student),
    FOREIGN KEY (id_user_student) REFERENCES UsersStudents(id_user_student),
    FOREIGN KEY (id_role_student) REFERENCES Roles(id_role)
);

-- @author TABLE TokenUserStudent: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE TokenUserStudent (
    id_token_user_student INT PRIMARY KEY AUTO_INCREMENT,
    token_student VARCHAR(512) UNIQUE,
    id_user_student INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_student) REFERENCES UsersStudents(id_user_student)
);

-- @author TABLE UsersProfessors: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE UsersProfessors (
    id_user_professor INT PRIMARY KEY AUTO_INCREMENT,
    username_user_professor INT UNIQUE NOT NULL,
    password_user_professor VARCHAR(100) NOT NULL,
    status_user_professor BOOLEAN NOT NULL,
    FOREIGN KEY (username_user_professor) REFERENCES Professors(id_professor)
);

-- @author TABLE RolesUsersProfessor: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE RolesUsersProfessor (
    id_user_professor INT NOT NULL,
    id_role_professor INT NOT NULL,
    status_role_professor BOOLEAN NOT NULL,
    CONSTRAINT id_role_user_professor PRIMARY KEY (id_user_professor, id_role_professor),
    FOREIGN KEY (id_user_professor) REFERENCES UsersProfessors(id_user_professor),
    FOREIGN KEY (id_role_professor) REFERENCES Roles(id_role)
);

-- @author TABLE TokenUserProfessor: Alejandro Moya 20211020462 @created 25/11/2024
CREATE TABLE TokenUserProfessor (
    id_token_user_professor INT PRIMARY KEY AUTO_INCREMENT,
    token_professor VARCHAR(512) UNIQUE,
    id_user_professor INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_professor) REFERENCES Professors(id_professor)
);

-- @author TABLE ClassSections: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE ClassSections (
    id_class_section INT AUTO_INCREMENT PRIMARY KEY,
    id_dates_academic_periodicity_year INT NOT NULL,
    id_classroom_class_section INT NOT NULL,
    id_academic_schedules INT NOT NULL,
    id_professor_class_section INT NOT NULL,
    numberof_spots_available_class_section INT NOT NULL,
    status_class_section BOOLEAN NOT NULL,
    FOREIGN KEY (id_dates_academic_periodicity_year) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year),
    FOREIGN KEY (id_classroom_class_section) REFERENCES Classrooms(id_classroom),
    FOREIGN KEY (id_professor_class_section) REFERENCES Professors(id_professor),
    FOREIGN KEY (id_academic_schedules) REFERENCES AcademicSchedules(id_academic_schedules)
);

-- @author TABLE ClassSectionsDays: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE ClassSectionsDays (
    id_class_sections_days INT AUTO_INCREMENT PRIMARY KEY,
    id_class_section INT NOT NULL,
    id_day ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo') NOT NULL,
    status_class_sections_days BOOLEAN NOT NULL,
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section)
);

-- @author TABLE ClassSectionsCancelledDepartmentHead: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE ClassSectionsCancelledDepartmentHead (
    id_class_sections_cancelled INT AUTO_INCREMENT PRIMARY KEY,
    id_class_section INT NOT NULL,
    id_department_head INT NOT NULL,
    justification_sections_cancelled TEXT NOT NULL,
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section),
    FOREIGN KEY (id_department_head) REFERENCES DepartmentHead(id_department_head)
);

-- @author TABLE ClassSectionsProfessor: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE ClassSectionsProfessor (
    id_class_section_professor INT AUTO_INCREMENT PRIMARY KEY,
    id_class_section INT NOT NULL,
    class_presentation_video VARCHAR(255) NOT NULL,
    status_class_section_professor BOOLEAN NOT NULL,
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section)
);

-- @authorTABLE ClassSectionsCancelledStuden: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE ClassSectionsCancelledStudent (
    id_class_sections_cancelled_student INT AUTO_INCREMENT PRIMARY KEY,
    id_class_section INT NOT NULL,
    id_student VARCHAR(13) NOT NULL,
    status_class_sections_cancelled_student BOOLEAN NOT NULL,
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section),
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);

-- @author TABLE WaitingListsClassSections: Alejandro Moya 20211020462 @created 16/11/2024
CREATE TABLE WaitingListsClassSections (
    id_waiting_lists_class_sections INT AUTO_INCREMENT PRIMARY KEY,
    id_class_section INT NOT NULL,
    id_student VARCHAR(13) NOT NULL,
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section),
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);
 
-- @author TABLE EnrollmentClassSections: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE EnrollmentClassSections (
    id_enrollment_class_sections INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_class_section INT NOT NULL,
    status_enrollment_class_sections BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section)
);

-- @author TABLE UndergraduateChangeRequestsStudents: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE UndergraduateChangeRequestsStudents (
    id_undergraduate_change_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_undergraduate INT NOT NULL,
    id_student VARCHAR(13) NOT NULL,
    dateof_request_student DATE NOT NULL,
    reasons_undergraduate_change_request_student TEXT NOT NULL,
    status_undergraduate_change_request_student BOOLEAN NOT NULL,
    FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);

-- @author TABLE ResolutionUndergraduateChangeRequestsStudents: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE ResolutionUndergraduateChangeRequestsStudents (
    id_resolution_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_undergraduate_change_request_student INT NOT NULL,
    resolution_request_student BOOLEAN NOT NULL,
    date_resolution DATE NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_undergraduate_change_request_student) REFERENCES UndergraduateChangeRequestsStudents(id_undergraduate_change_request_student)
);

-- @author TABLE RequestsCancellationExceptionalClasses: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE RequestsCancellationExceptionalClasses (
    id_requests_cancellation_exceptional_classes INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    reasons_request_cancellation_exceptional_classes TEXT NOT NULL,
    document_request_cancellation_exceptional_classes MEDIUMBLOB NOT NULL,
    evidence_request_cancellation_exceptional_classes MEDIUMBLOB,
    status_request_cancellation_exceptional_classes BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);

-- @author TABLE ListClassSectionCancellationExceptional: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE ListClassSectionCancellationExceptional (
    id_list_class_section_cancellation_exceptional INT AUTO_INCREMENT PRIMARY KEY,
    id_requests_cancellation_exceptional_classes INT NOT NULL,
    id_class_section INT NOT NULL,
    FOREIGN KEY (id_requests_cancellation_exceptional_classes) REFERENCES RequestsCancellationExceptionalClasses(id_requests_cancellation_exceptional_classes),
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section)
);

-- @author TABLE ResolutionRequestsCancellationExceptionalClasses: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE ResolutionRequestsCancellationExceptionalClasses (
    id_resolution_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_requests_cancellation_exceptional_classes INT NOT NULL,
    resolution_request_student BOOLEAN NOT NULL,
    date_resolution DATE NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_requests_cancellation_exceptional_classes) REFERENCES RequestsCancellationExceptionalClasses(id_requests_cancellation_exceptional_classes)
);

-- @author TABLE ResolutionListClassSectionCancellationExceptional: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE ResolutionListClassSectionCancellationExceptional (
    id_resolution_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_list_class_section_cancellation_exceptional INT NOT NULL,
    resolution_request_student BOOLEAN NOT NULL,
    date_resolution DATE NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_list_class_section_cancellation_exceptional) REFERENCES ListClassSectionCancellationExceptional(id_list_class_section_cancellation_exceptional)
);

-- @author TABLE RegionalCentersChangeRequestsStudents: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE RegionalCentersChangeRequestsStudents (
    id_regional_center_change_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_regional_center_change INT NOT NULL,
    reasons_request_regional_center_change TEXT NOT NULL,
    dateod_change_regional_center_student DATE NOT NULL,
    status_regional_center_change_request_student BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_regional_center_change) REFERENCES RegionalCenters(id_regional_center)
);

-- @author TABLE ResolutionRegionalCentersChangeRequestsStudents: Alejandro Moya 20211020462 @created 17/11/2024
CREATE TABLE ResolutionRegionalCentersChangeRequestsStudents (
    id_resolution_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_regional_center_change_request_student INT NOT NULL,
    resolution_request_student BOOLEAN NOT NULL,
    date_resolution DATE NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_regional_center_change_request_student) REFERENCES RegionalCentersChangeRequestsStudents(id_regional_center_change_request_student)
);

-- @author TABLE RequestsExamRetakeStudents: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE RequestsExamRetakeStudents (
    id_request_exam_retake_student INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    dateof_request_student DATE NOT NULL,
    id_dates_academic_periodicity_year INT NOT NULL,
    reasons_request_exam_retake TEXT NOT NULL,
    status_request_exam_retake BOOLEAN NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_dates_academic_periodicity_year) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year)
);

-- @author TABLE SpecificationClassStatus: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE SpecificationClassStatus (
    id_specification_class_status INT AUTO_INCREMENT PRIMARY KEY,
    id_student_class_status VARCHAR(13) NOT NULL,
    id_class_section INT NOT NULL,
    specification_class_status ENUM('APROBADO', 'REPROBADO', 'NO SE PRESENTO', 'ABANDONO') NOT NULL,
    grade_class_student DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (id_student_class_status) REFERENCES Students(id_student),
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section)
);

-- @author TABLE StudentGradesAverages: Alejandro Moya 20211020462 @created 23/11/2024
CREATE TABLE StudentGradesAverages ( 
    id_student_grades_averages INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    global_grade_average_student DECIMAL(5, 2) NOT NULL,
    period_grade_average_student DECIMAL(5, 2) NOT NULL,
    annual_academic_grade_average_student DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student)
);

-- @author TABLE EvaluationOfProfessors: Alejandro Moya 20211020462 @created 29/11/2024
CREATE TABLE EvaluationOfProfessors (
    id_evaluation_of_professors INT AUTO_INCREMENT PRIMARY KEY,
    id_student VARCHAR(13) NOT NULL,
    id_class_section INT NOT NULL,
    id_professor INT NOT NULL,
    first_performance_indicator INT NOT NULL,
    second_performance_indicator INT NOT NULL,
    third_performance_indicator INT NOT NULL,
    fourth_performance_indicator INT NOT NULL,
    fifth_performance_indicator INT NOT NULL,
    sixth_performance_indicator INT NOT NULL,
    seventh_performance_indicator INT NOT NULL,
    eighth_performance_indicator INT NOT NULL,
    ninth_performance_indicator INT NOT NULL,
    tenth_performance_indicator INT NOT NULL,
    eleventh_performance_indicator INT NOT NULL,
    twelfth_performance_indicator INT NOT NULL,
    thirteenth_performance_indicator INT NOT NULL,
    fourteenth_performance_indicator INT NOT NULL,
    fifteenth_performance_indicator INT NOT NULL,
    sixteenth_performance_indicator INT NOT NULL,
    seventeenth_performance_indicator INT NOT NULL,
    eighteenth_performance_indicator INT NOT NULL,
    nineteenth_performance_indicator INT NOT NULL,
    twentieth_performance_indicator INT NOT NULL,
    twenty_first_performance_indicator INT NOT NULL,
    twenty_second_performance_indicator INT NOT NULL,
    twenty_third_performance_indicator INT NOT NULL,
    twenty_fourth_performance_indicator INT NOT NULL,
    twenty_fifth_performance_indicator INT NOT NULL,
    twenty_sixth_performance_indicator INT NOT NULL,
    twenty_seventh_performance_indicator INT NOT NULL,
    twenty_eighth_performance_indicator INT NOT NULL,
    FOREIGN KEY (id_student) REFERENCES Students(id_student),
    FOREIGN KEY (id_class_section) REFERENCES ClassSections(id_class_section),
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor)
);


