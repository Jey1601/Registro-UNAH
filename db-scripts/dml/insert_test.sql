USE unah_registration;

-- Insertar en la tabla AcademicYear
INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES
    ('2024-2025', 1),
    ('2023-2024', 0),
    ('2022-2023', 1);

-- Insertar en la tabla AdmissionProcess
INSERT INTO AdmissionProcess (name_admission_process, id_academic_year, start_dateof_admission_process, end_dateof_admission_process, timeof_sending_notifications_admission_process, current_status_admission_process, status_admission_process)
VALUES
    ('Proceso de Admisión 2024', 2025, '2024-05-01', '2024-06-30', '10:00:00', 1, 1),
    ('Proceso de Admisión 2023', 2026, '2023-05-01', '2023-06-30', '10:00:00', 0, 0);

-- Insertar en la tabla RegionalCenters
INSERT INTO RegionalCenters (name_regional_center, acronym_regional_center, location_regional_center, email_regional_center, phone_number_regional_center, address_regional_center, status_regional_center)
VALUES
    ('Centro Regional Norte', 'CRN', 'San Pedro Sula', 'norte@unah.edu', '2555-1234', 'Calle 10, San Pedro Sula', 1),
    ('Centro Regional Sur', 'CRS', 'Tegucigalpa', 'sur@unah.edu', '2555-5678', 'Avenida 5, Tegucigalpa', 1);

-- Insertar en la tabla NumberExtensions
INSERT INTO NumberExtensions (number_extension, status_number_extension)
VALUES
    ('12345', 1),
    ('67890', 1);

-- Insertar en la tabla NumberExtensionsRegionalCenters
INSERT INTO NumberExtensionsRegionalCenters (id_number_extension, id_regional_center, status_number_extension_regional_center)
VALUES
    (1, 1, 1),
    (2, 2, 1);

-- Insertar en la tabla Faculties
INSERT INTO Faculties (name_faculty, address_faculty, phone_number_faculty, email_faculty, status_faculty)
VALUES
    ('Facultad de Ciencias Sociales', 'Ciudad Universitaria', '2555-2345', 'cienciasociales@unah.edu', 1),
    ('Facultad de Ingenierías', 'Av. Universidad', '2555-6789', 'ingenieria@unah.edu', 1);

-- Insertar en la tabla Departments
INSERT INTO Departments (name_departmet, id_faculty, status_department)
VALUES
    ('Departamento de Sociología', 1, 1),
    ('Departamento de Ingeniería Informática', 2, 1);

-- Insertar en la tabla Undergraduates
INSERT INTO Undergraduates (name_undergraduate, id_department, status_undergraduate,duration_undergraduate, mode_undergraduate)
VALUES
    ('Licenciatura en Sociología', 1, 1,5.0,'Presencial'),
    ('Licenciatura en Ingeniería Informática', 2, 1,5.0,'Presencial');

INSERT INTO Undergraduates (name_undergraduate, id_department, status_undergraduate, duration_undergraduate, mode_undergraduate)
VALUES 
('Ingeniería en Sistemas', 1, 1,5.0,'Presencial'),
('Ingeniería Civil', 1, 1,5.0,'Presencial'),
('Ingeniería Industrial', 1, 1,5.0,'Presencial'),
('Ingeniería Mecánica', 1, 1,5.0,'Presencial'),
('Ingeniería Eléctrica', 1, 1,5.0,'Presencial');    

INSERT INTO Undergraduates (name_undergraduate, id_department, status_undergraduate,duration_undergraduate, mode_undergraduate)
VALUES 
('Derecho', 2, 1,4.0,'Presencial'),
('Contaduría Pública', 2, 1,4.0,'Presencial'),
('Administración de Empresas', 2, 1,4.0,'Presencial'),
('Economía', 2, 1,4.0,'Presencial'),
('Marketing', 2, 1,4.0,'Presencial');|

-- Insertar en la tabla DepartmentsRegionalCenters
INSERT INTO DepartmentsRegionalCenters (id_department, id_regionalcenter, status_department_regional_center)
VALUES
    (1, 1, 1),
    (2, 2, 1);

-- Insertar en la tabla UndergraduatesRegionalCenters
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
    (1, 1, 1),
    (2, 2, 1);

-- Ingeniería en Sistemas (id_undergraduate = 3)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(3, 1, TRUE),  -- Sistemas en Centro Regional 1
(3, 2, TRUE);  -- Sistemas en Centro Regional 2

-- Ingeniería Civil (id_undergraduate = 4)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(4, 1, TRUE),  -- Ingeniería Civil en Centro Regional 1
(4, 2, TRUE);  -- Ingeniería Civil en Centro Regional 2

-- Ingeniería Industrial (id_undergraduate = 5)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(5, 1, TRUE),  -- Ingeniería Industrial en Centro Regional 1
(5, 2, TRUE);  -- Ingeniería Industrial en Centro Regional 2

-- Ingeniería Mecánica (id_undergraduate = 6)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(6, 1, TRUE),  -- Ingeniería Mecánica en Centro Regional 1
(6, 2, TRUE);  -- Ingeniería Mecánica en Centro Regional 2

-- Ingeniería Eléctrica (id_undergraduate = 7)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(7, 1, TRUE),  -- Ingeniería Eléctrica en Centro Regional 1
(7, 2, TRUE);  -- Ingeniería Eléctrica en Centro Regional 2

-- Derecho (id_undergraduate = 8)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(8, 1, TRUE),  -- Derecho en Centro Regional 1
(8, 2, TRUE);  -- Derecho en Centro Regional 2

-- Contaduría Pública (id_undergraduate = 9)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(9, 1, TRUE),  -- Contaduría Pública en Centro Regional 1
(9, 2, TRUE);  -- Contaduría Pública en Centro Regional 2

-- Administración de Empresas (id_undergraduate = 10)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(10, 1, TRUE),  -- Administración en Centro Regional 1
(10, 2, TRUE);  -- Administración en Centro Regional 2

-- Economía (id_undergraduate = 11)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(11, 1, TRUE),  -- Economía en Centro Regional 1
(11, 2, TRUE);  -- Economía en Centro Regional 2

-- Marketing (id_undergraduate = 12)
INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
(12, 1, TRUE),  -- Marketing en Centro Regional 1
(12, 2, TRUE);  -- Marketing en Centro Regional 2    

-- Insertar en la tabla Applicants


-- Insertar en la tabla ApplicantType
INSERT INTO ApplicantType (name_aplicant_type, admission_test_aplicant, status_aplicant_type)
VALUES
    ('Nuevo Ingreso', 1, 1),
    ('Reingreso', 0, 1);

-- Insertar en la tabla Applications

-- Insertar en la tabla TypesAdmissionTests
INSERT INTO TypesAdmissionTests (name_type_admission_tests, status_type_admission_tests)
VALUES
    ('Examen de Matemáticas', 1),
    ('Examen de Lengua y Literatura', 1);

-- Insertar en la tabla UndergraduateTypesAdmissionTests
INSERT INTO UndergraduateTypesAdmissionTests (id_type_admission_tests, id_undergraduate, required_rating, status_undergraduate_type_admission_tests)
VALUES
    (1, 1, 70.00, 1),
    (2, 2, 80.00, 1);

-- Insertar en la tabla RatingApplicantsTest


-- Insertar en la tabla ResolutionIntendedUndergraduateApplicant

-- Insertar en la tabla NotificationsApplicationsResolution

-- Insertar en la tabla ApplicantAcceptance
