CREATE DATABASE IF NOT EXISTS unah_registration;
USE unah_registration;

CREATE TABLE Holydays (
	id_holyday INT PRIMARY KEY AUTO_INCREMENT,
	holyday_name VARCHAR(50) NOT NULL,
	holyday_date VARCHAR(5) NOT NULL,
	status_holyday	BOOLEAN NOT NULL
);

CREATE TABLE Vacations (
	id_vacation INT PRIMARY KEY AUTO_INCREMENT,
	vacation_date VARCHAR(5) NOT NULL,
	academic_year_vacation INT NOT NULL,
	status_vacation BOOLEAN NOT NULL,
	FOREIGN KEY (academic_year_vacation) REFERENCES AcademicYear(id_academic_year)
);


CREATE TABLE AcademicPeriodicity (
	id_academic_periodicity INT PRIMARY KEY AUTO_INCREMENT,
	description_academic_periodicity VARCHAR(50) NOT NULL,
	numberof_months_academic_periodicity TINYINT UNSIGNED NOT NULL CHECK (numberof_months_academic_periodicity BETWEEN 1 AND 12),
	status_academic_periodicity BOOLEAN NOT NULL
);

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

CREATE TABLE TypesEnrollmentConditions (
	id_type_enrollment_conditions INT PRIMARY KEY AUTO_INCREMENT,
	maximum_student_global_average DECIMAL(4,2) UNSIGNED NOT NULL,
	minimum_student_global_average DECIMAL(4,2) UNSIGNED NOT NULL,
	status_student_global_average BOOLEAN NOT NULL,
	maximum_student_period_average DECIMAL(4,2) UNSIGNED NOT NULL,
	minimum_student_period_average DECIMAL(4,2) UNSIGNED NOT NULL,
	status_type_enrollment_conditions BOOLEAN NOT NULL
);

CREATE TABLE EnrollmentProcess (
	id_enrollment_process INT PRIMARY KEY AUTO_INCREMENT,
	id_dates_academic_periodicity_year INT NOT NULL,
	status_enrollment_process BOOLEAN NOT NULL,
	FOREIGN KEY (id_dates_academic_periodicity_year) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year)
);

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

CREATE TABLE UndergraduateChangeStudentsProcess (
	id_undergraduate_change_student_process INT PRIMARY KEY AUTO_INCREMENT,
	academic_year_undergraduate_change_student_process INT NOT NULL,
	start_dateof_undergraduate_change_student_process DATE NOT NULL,
	end_dateof_undergraduate_change_student_process DATE NOT NULL,
	status_undergraduate_change_student_process BOOLEAN NOT NULL,
	FOREIGN KEY (academic_year_undergraduate_change_student_process) REFERENCES AcademicYear(id_academic_year)

);

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


CREATE TABLE AcademicPlanningProcess(
	id_academic_planning_process INT PRIMARY KEY AUTO_INCREMENT,
	date_academic_periodicity_academic_planning_process INT NOT NULL,
	department_academic_planning_process INT NOT NULL,
	start_dateof_academic_planning_process DATE NOT NULL,
	end_dateof_academic_planning_process DATE NOT NULL,
	status_academic_planning_process BOOLEAN NOT NULL,
	FOREIGN KEY (date_academic_periodicity_academic_planning_process) REFERENCES DatesAcademicPeriodicityYear(id_dates_academic_periodicity_year),
	FOREIGN KEY (department_academic_planning_process) REFERENCES Departments(id_department)
);


CREATE TABLE AcademicSchedules (
	id_academic_schedules INT PRIMARY KEY AUTO_INCREMENT,
	start_timeof_classes TIME NOT NULL,
	end_timeof_classes TIME NOT NULL,
	status_academic_schedules BOOLEAN NOT NULL
);

CREATE TABLE Building (
	id_building INT PRIMARY KEY AUTO_INCREMENT,
	name_building VARCHAR(20) NOT NULL,
	regionalcenters_building INT NOT NULL,
	status_building BOOLEAN NOT NULL,
	FOREIGN KEY (regionalcenters_building) REFERENCES RegionalCenters(id_regional_center)
);

CREATE TABLE BuildingLevels (
	id_building_level INT PRIMARY KEY AUTO_INCREMENT,
	id_building INT NOT NULL,
	description_building_level VARCHAR(100) NOT NULL,
	status_building_level BOOLEAN NOT NULL,
	FOREIGN KEY (id_building) REFERENCES Building(id_building)
);

CREATE TABLE Classrooms (
	id_classroom INT PRIMARY KEY AUTO_INCREMENT,
	name_classroom VARCHAR(20) NOT NULL,
	description_classroom VARCHAR(255) NOT NULL,
	building_level_classroom  INT NOT NULL,
	status_classroom BOOLEAN NOT NULL,
	FOREIGN KEY (building_level_classroom) REFERENCES  BuildingLevels(id_building_level)
);

CREATE TABLE BuildingsDepartmentsRegionalsCenters (
	id_building_department_regionalcenter INT PRIMARY KEY AUTO_INCREMENT,
	department_regional_center INT NOT NULL,
	building_department_regionalcenter  INT NOT NULL,
	status_building_department_regionalcenter BOOLEAN NOT NULL,
	FOREIGN KEY (department_regional_center) REFERENCES DepartmentsRegionalCenters(id_department_Regional_Center),
	FOREIGN KEY (building_department_regionalcenter) REFERENCES Building(id_building)
);

CREATE TABLE ClassroomsBuildingsDepartmentsRegionalCenters (
	id_classroom_building_department_regionalcenter INT PRIMARY KEY AUTO_INCREMENT,
	building_department_regional_center INT NOT NULL,
	id_classroom INT NOT NULL,
	status_classroom_building_department_regionalcenter BOOLEAN NOT NULL,
	FOREIGN KEY (building_department_regional_center) REFERENCES BuildingsDepartmentsRegionalsCenters(id_building_department_regionalcenter),
	FOREIGN KEY (id_classroom) REFERENCES Classrooms(id_classroom)
);


CREATE TABLE classes (
	id_class INT PRIMARY KEY AUTO_INCREMENT,
	name_class VARCHAR(100) NOT NULL,
	description_class VARCHAR(255) NOT NULL,
	credit_units INT UNSIGNED NOT NULL,
	hours_required_week TINYINT UNSIGNED NOT NULL,
	department_class INT NOT NULL,
	academic_periodicity_class INT NOT NULL,
	status_class BOOLEAN NOT NULL,
	FOREIGN KEY (department_class) REFERENCES Departments(id_department),
	FOREIGN KEY (academic_periodicity_class) REFERENCES AcademicPeriodicity(id_academic_periodicity)
);


CREATE TABLE UndergraduateClass (
	id_undergraduate_class INT PRIMARY KEY AUTO_INCREMENT,
	id_undergraduate INT NOT NULL,
	id_class INT NOT NULL,
	status_undergraduate_class BOOLEAN NOT NULL,
	FOREIGN KEY (id_undergraduate) REFERENCES Undergraduates(id_undergraduate),
	FOREIGN KEY (id_class) REFERENCES classes(id_class)
);

CREATE TABLE RequirementUndergraduateClass (
	id_requirement_undergraduate_class INT PRIMARY KEY AUTO_INCREMENT,
	id_undergraduate_class INT NOT NULL,
	id_class INT NOT NULL,
	status_requirement_undergraduate_class BOOLEAN NOT NULL,
	FOREIGN KEY (id_undergraduate_class) REFERENCES UndergraduateClass(id_undergraduate_class),
	FOREIGN KEY (id_class) REFERENCES classes(id_class)
);

CREATE TABLE ProfessorsObligations (
	id_professor_obligation  INT PRIMARY KEY AUTO_INCREMENT,
	maximum_credit_units_professor_obligation TINYINT UNSIGNED NOT NULL,
	minimum_credit_units_professor_obligation TINYINT UNSIGNED NOT NULL,
	status_professor_oblgation BOOLEAN NOT NULL
);

CREATE TABLE DepartmentHeadObligations (
	id_department_head_obligations INT PRIMARY KEY AUTO_INCREMENT,
	credit_units_department_head_obligations TINYINT UNSIGNED NOT NULL,
	status_units_department_head_obligations BOOLEAN NOT NULL
);


CREATE TABLE AcademicCoordinatorObligations (
	id_academic_coordinator_obligations INT PRIMARY KEY AUTO_INCREMENT,
	credit_units_academic_coordinator_obligations TINYINT UNSIGNED NOT NULL,
	status_academic_coordinator_obligations BOOLEAN NOT NULL
);

CREATE TABLE WorkingHours (
    id_working_hour INT AUTO_INCREMENT PRIMARY KEY,
    name_working_hour VARCHAR(100) NOT NULL,
    day_week_working_hour VARCHAR(20) NOT NULL,
    check_in_time_working_hour TIME NOT NULL,
    check_out_time_working_hour TIME NOT NULL,
    status_working_hour BOOLEAN NOT NULL
);

CREATE TABLE Professors (
    id_professor INT AUTO_INCREMENT PRIMARY KEY,
    first_name_professor VARCHAR(50) NOT NULL,
    second_name_professor VARCHAR(50),
    third_name_professor VARCHAR(50),
    first_lastname_professor VARCHAR(50) NOT NULL,
    second_lastname_professor VARCHAR(50),
    picture_professor MEDIUMBLOB,
    id_professors_obligations INT NOT NULL,
    id_regional_center INT NOT NULL,
    status_professor BOOLEAN NOT NULL,
    FOREIGN KEY (id_professors_obligations) REFERENCES ProfessorsObligations(id_professor_obligation),
    FOREIGN KEY (id_regional_center) REFERENCES RegionalCenters( id_regional_center)
);

CREATE TABLE ProfessorsDepartments (
    id_professor_department INT AUTO_INCREMENT PRIMARY KEY,
    id_department INT NOT NULL,
    id_professor INT NOT NULL,
    status_professor_department ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (id_department) REFERENCES Departments(id_department),
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor)
);

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

CREATE TABLE AcademicCoordinatorValidityPeriod (
    id_academic_coordinator_validity_period INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    start_date_academic_coordinator_validity_period DATE NOT NULL,
    end_date_academic_coordinator_validity_period DATE NOT NULL,
    actual_end_date_academic_coordinator_validity_period DATE,
    status_academic_coordinator_validity_period BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator)
);

CREATE TABLE DepartmentHeadValidityPeriod (
    id_department_head_validity_period INT AUTO_INCREMENT PRIMARY KEY,
    id_department_head INT NOT NULL,
    start_date_department_head_validity_period DATE NOT NULL,
    end_date_department_head_validity_period DATE NOT NULL,
    actual_end_date_department_head_validity_period DATE,
    status_department_head_validity_period BOOLEAN NOT NULL,
FOREIGN KEY (id_department_head) REFERENCES DepartmentHead(id_department_head)
);

CREATE TABLE AcademicCoordinatorWorkingHours (
    id_academic_coordinator_working_hours INT AUTO_INCREMENT PRIMARY KEY,
    id_academic_coordinator INT NOT NULL,
    id_working_hour INT NOT NULL,
    status_academic_coordinator_working_hours BOOLEAN NOT NULL,
    FOREIGN KEY (id_academic_coordinator) REFERENCES AcademicCoordinator(id_academic_coordinator),
    FOREIGN KEY (id_working_hour) REFERENCES WorkingHours(id_working_hour)
);

CREATE TABLE DepartmentHeadWorkingHours (
    id_department_head_working_hours INT AUTO_INCREMENT PRIMARY KEY,
    id_department_head INT NOT NULL,
    id_working_hour INT NOT NULL,
    status_department_head_working_hours BOOLEAN NOT NULL,
    FOREIGN KEY (id_department_head) REFERENCES DepartmentHead(id_department_head),
    FOREIGN KEY (id_working_hour) REFERENCES WorkingHours(id_working_hour)
);

CREATE TABLE UsersFacultiesAdministrator (
    id_user_faculties_administrator INT PRIMARY KEY AUTO_INCREMENT,
    username_user_faculties_administrator VARCHAR(50) UNIQUE NOT NULL,
    password_user_faculties_administrator VARCHAR(100) NOT NULL,
    id_faculty INT NOT NULL,
    status_user_faculties_administrator BOOLEAN NOT NULL,
    FOREIGN KEY (id_faculty) REFERENCES Faculties(id_faculty)	
);

CREATE TABLE RolesUsersFacultiesAdministrator (
    id_user_faculties_administrator INT NOT NULL,
    id_role_faculties_administrator INT NOT NULL,
    status_role_faculties_administrator BOOLEAN NOT NULL,
    CONSTRAINT id_role_user_faculties_administrator PRIMARY KEY (id_user_faculties_administrator, id_role_faculties_administrator),
    FOREIGN KEY (id_user_faculties_administrator) REFERENCES UsersFacultiesAdministrator(id_user_faculties_administrator),
    FOREIGN KEY (id_role_faculties_administrator) REFERENCES Roles(id_role)
);
