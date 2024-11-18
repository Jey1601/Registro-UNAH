DROP DATABASE unah_registration;

CREATE DATABASE IF NOT EXISTS unah_registration;
USE unah_registration;

CREATE TABLE UsersAdmissionsAdministrator (
	id_user_admissions_administrator INT PRIMARY KEY AUTO_INCREMENT,
	username_user_admissions_administrator VARCHAR(50) NOT NULL,
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
    timeof_sending_notifications_admission_process TIME NOT NULL,
    current_status_admission_process BOOLEAN NOT NULL,
    status_admission_process BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_year) REFERENCES AcademicYear(id_academic_year)
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

CREATE TABLE Applicants (
    id_applicant VARCHAR(20) PRIMARY KEY,
    first_name_applicant VARCHAR(50) NOT NULL,
    second_name_applicant VARCHAR(50),
    third_name_applicant VARCHAR(50),
    first_lastname_applicant VARCHAR(50) NOT NULL,
    second_lastname_applicant VARCHAR(50),
    email_applicant VARCHAR(100)NOT NULL,
    phone_number_applicant VARCHAR(20) NOT NULL,
    address_applicant VARCHAR(255)NOT NULL,
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
);

CREATE TABLE UsersApplicants (
	id_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
	username_user_applicant VARCHAR(50) NOT NULL,
	password_user_applicant INT NOT NULL,
	status_user_applicant BOOLEAN NOT NULL,
        FOREIGN KEY (password_user_applicant) REFERENCES Applications( id_admission_application_number),
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
    id_rating_applicants_test INT NOT NULL,
    intended_undergraduate_applicant INT NOT NULL,
    resolution_intended BOOLEAN NOT NULL,
    status_resolution_intended_undergraduate_applicant BOOLEAN NOT NULL,
    FOREIGN KEY (id_rating_applicants_test) REFERENCES RatingApplicantsTest(id_rating_applicant_test),
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