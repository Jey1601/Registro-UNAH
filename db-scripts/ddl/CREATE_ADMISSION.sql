DROP DATABASE unah_registration;
CREATE DATABASE IF NOT EXISTS unah_registration;
USE unah_registration;

CREATE TABLE Roles (
	id_role INT PRIMARY KEY AUTO_INCREMENT,
	role VARCHAR (50) NOT NULL,
	description_role VARCHAR (200) NOT NULL,
	status_role BOOLEAN NOT NULL
);

CREATE TABLE AccessControl (
	id_access_control CHAR(8) NOT NULL PRIMARY KEY,
	description_access_control VARCHAR(150) NOT NULL,
	status_access_control BOOLEAN NOT NULL
);

CREATE TABLE AccessControlRoles (
    id_role INT NOT NULL,
    id_access_control CHAR(8) NOT NULL,
    status_access_control_roles BOOLEAN NOT NULL,
    CONSTRAINT id_access_control_roles PRIMARY KEY (id_role, id_access_control), 
    FOREIGN KEY (id_role) REFERENCES Roles(id_role),
    FOREIGN KEY (id_access_control) REFERENCES AccessControl(id_access_control)
);


CREATE TABLE UsersAdmissionsAdministrator (
	id_user_admissions_administrator INT PRIMARY KEY AUTO_INCREMENT,
	username_user_admissions_administrator VARCHAR(50) UNIQUE NOT NULL,
	password_user_admissions_administrator VARCHAR(100) NOT NULL,
	status_user_admissions_administrator BOOLEAN NOT NULL
);


CREATE TABLE AcademicYear (
    id_academic_year INT PRIMARY KEY AUTO_INCREMENT,
    name_academic_year VARCHAR(100) NOT NULL,
    status_academic_year BOOLEAN NOT NULL
)AUTO_INCREMENT = 2024;


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

CREATE TABLE InscriptionAdmissionProcess (
	id_inscription_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_inscription_admission_process DATE NOT NULL,
	end_dateof_inscription_admission_process DATE NOT NULL,
	current_status_inscription_admission_process BOOLEAN NOT NULL,
	status_inscription_admission_processs BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

CREATE TABLE DocumentValidationAdmissionProcess (	
	id_document_validation_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_document_validation_admission_process DATE NOT NULL,
	end_dateof_document_validation_admission_process DATE NOT NULL,
	current_status_document_validation_admission_process BOOLEAN NOT NULL,
	status_document_validation_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

CREATE TABLE AdmissionTestAdmissionProcess (
	id_admission_test_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	dateof_admission_test_admission_process DATE NOT NULL,
	current_status_admission_test_admission_process BOOLEAN NOT NULL,
	status_admission_test_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);


CREATE TABLE RegistrationRatingAdmissionProcess (
	id_registration_rating_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_registration_rating_admission_process DATE NOT NULL,
	end_dateof_registration_rating_admission_process DATE NOT NULL,
	current_status_registration_rating_admission_process BOOLEAN NOT NULL,
	status_sending_registration_rating_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

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

CREATE TABLE AcceptanceAdmissionProcess (
	id_acceptance_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_acceptance_admission_process DATE NOT NULL,
	end_dateof_acceptance_admission_process DATE NOT NULL,
	current_status_acceptance_admission_process BOOLEAN NOT NULL,
	status_acceptance_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

CREATE TABLE RectificationPeriodAdmissionProcess (
	id_rectification_period_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_rectification_period_admission_process DATE NOT NULL,
	end_dateof_rectification_period_admission_process DATE NOT NULL,
	current_status_rectification_period_admission_process BOOLEAN NOT NULL,
	status_rectification_period_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);

CREATE TABLE DownloadApplicantAdmittedInformationAdmissionProcess (
	id_download_applicant_information_admission_process INT PRIMARY KEY AUTO_INCREMENT,
	id_admission_process INT NOT NULL,
	start_dateof_download_applicant_information_admission_process DATE NOT NULL,
	end_dateof_download_applicant_information_admission_process DATE NOT NULL,
	current_status_download_applicant_information_admission_process BOOLEAN NOT NULL,
	status_download_applicant_information_admission_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_admission_process) REFERENCES AdmissionProcess(id_admission_process)
);
	

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

CREATE TABLE NumberExtensions (
    id_number_extension INT PRIMARY KEY AUTO_INCREMENT,
    number_extension VARCHAR(20) NOT NULL,
    status_number_extension BOOLEAN NOT NULL
);


CREATE TABLE NumberExtensionsRegionalCenters (
    id_number_extension_regional_center INT PRIMARY KEY AUTO_INCREMENT,
    id_number_extension INT NOT NULL,
    id_regional_center INT NOT NULL,
    status_number_extension_regional_center BOOLEAN NOT NULL,
    FOREIGN KEY (id_number_extension) REFERENCES NumberExtensions(id_number_extension),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters(id_regional_center)
);


CREATE TABLE Faculties (
    id_faculty INT PRIMARY KEY AUTO_INCREMENT,
    name_faculty VARCHAR(100) NOT NULL,
    address_faculty VARCHAR(255) NOT NULL,
    phone_number_faculty VARCHAR(20) NOT NULL,
    email_faculty VARCHAR(100) NOT NULL,
    status_faculty BOOLEAN NOT NULL
);


CREATE TABLE Departments (
    id_department INT PRIMARY KEY AUTO_INCREMENT,
    name_departmet VARCHAR(100) NOT NULL,
    id_faculty INT NOT NULL,
    status_department BOOLEAN NOT NULL,
    FOREIGN KEY (id_faculty) REFERENCES Faculties(id_faculty)
);


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

CREATE TABLE DepartmentsRegionalCenters (
    id_department_Regional_Center INT PRIMARY KEY AUTO_INCREMENT,
    id_department INT NOT NULL,
    id_regionalcenter INT NOT NULL,
    status_department_regional_center BOOLEAN NOT NULL,
    FOREIGN KEY (id_department) REFERENCES Departments(id_department),
    FOREIGN KEY (id_regionalcenter) REFERENCES RegionalCenters(id_regional_center)
);

CREATE TABLE UndergraduatesRegionalCenters (
    id_undergraduate_Regional_Center INT PRIMARY KEY AUTO_INCREMENT,
    id_undergraduate INT NOT NULL,
    id_regionalcenter INT NOT NULL,
    status_undergraduate_Regional_Center BOOLEAN NOT NULL,
    FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
    FOREIGN KEY (id_regionalcenter) REFERENCES RegionalCenters(id_regional_center)
);


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

CREATE TABLE ApplicantType (
    id_aplicant_type INT PRIMARY KEY AUTO_INCREMENT,
    name_aplicant_type VARCHAR(50) NOT NULL,
    admission_test_aplicant BOOLEAN NOT NULL,
    status_aplicant_type BOOLEAN NOT NULL
);

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

CREATE TABLE  CheckErrorsApplicantApplications(
	id_check_errors_applicant_applications INT PRIMARY KEY AUTO_INCREMENT,
	id_check_applicant_applications INT NOT NULL,
	incorrect_data VARCHAR(100) NOT NULL,
	description_incorrect_data VARCHAR(255) NOT NULL,
	FOREIGN KEY (id_check_applicant_applications) REFERENCES CheckApplicantApplications(id_check_applicant_applications)
);


CREATE TABLE UsersApplicants (
	id_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
	username_user_applicant VARCHAR(50) UNIQUE NOT NULL,
	password_user_applicant VARCHAR(100) NOT NULL,
	status_user_applicant BOOLEAN NOT NULL,
	FOREIGN KEY (username_user_applicant) REFERENCES Applicants(id_applicant)
);

CREATE TABLE TypesAdmissionTests (
    id_type_admission_tests INT PRIMARY KEY AUTO_INCREMENT,
    name_type_admission_tests VARCHAR(100) NOT NULL,
    status_type_admission_tests BOOLEAN NOT NULL
);

CREATE TABLE UndergraduateTypesAdmissionTests (
    id_undergraduate_type_admission_tests INT PRIMARY KEY AUTO_INCREMENT,
    id_type_admission_tests INT NOT NULL, 
    id_undergraduate INT NOT NULL,
    required_rating DECIMAL(5, 2) NOT NULL,
    status_undergraduate_type_admission_tests BOOLEAN NOT NULL
);

CREATE TABLE RatingApplicantsTest (
    id_rating_applicant_test INT PRIMARY KEY AUTO_INCREMENT,
    id_admission_application_number INT NOT NULL,
    id_type_admission_tests INT NOT NULL,
    rating_applicant DECIMAL(5, 2) NOT NULL,
    status_rating_applicant_test BOOLEAN NOT NULL,
    FOREIGN KEY (id_admission_application_number) REFERENCES Applications(id_admission_application_number),
    FOREIGN KEY (id_type_admission_tests) REFERENCES TypesAdmissionTests(id_type_admission_tests)
);

CREATE TABLE ResolutionIntendedUndergraduateApplicant (
    id_resolution_intended_undergraduate_applicant INT PRIMARY KEY AUTO_INCREMENT,
    id_admission_application_number INT NOT NULL,
    intended_undergraduate_applicant INT NOT NULL,
    resolution_intended BOOLEAN NOT NULL,
    status_resolution_intended_undergraduate_applicant BOOLEAN NOT NULL,
    FOREIGN KEY (id_admission_application_number) REFERENCES Applications(id_admission_application_number),
    FOREIGN KEY (intended_undergraduate_applicant) REFERENCES Undergraduates(id_undergraduate)
);

CREATE TABLE NotificationsApplicationsResolution (
    id_notification_application_resolution INT PRIMARY KEY AUTO_INCREMENT,
    id_resolution_intended_undergraduate_applicant INT NOT NULL,
    email_sent_application_resolution BOOLEAN NOT NULL,
    date_email_sent_application_resolution DATE NOT NULL,
    FOREIGN KEY (id_resolution_intended_undergraduate_applicant) REFERENCES ResolutionIntendedUndergraduateApplicant(id_resolution_intended_undergraduate_applicant)
);

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

