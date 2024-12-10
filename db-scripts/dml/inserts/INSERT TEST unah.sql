USE unah_registration;

INSERT INTO UsersAdmissionsAdministrator (username_user_admissions_administrator, password_user_admissions_administrator, status_user_admissions_administrator) 
VALUES 
	('admin_nouser', 'MiContraseña1+', FALSE),
	('admin08011990021', 'Apple1*B', TRUE),
	('admin07031987016', 'Cat4*Dog', TRUE),
	('admin11021995037', 'Happy7+T', TRUE),
	('admin02012000043', 'Book2-Tea', TRUE),
	('admin19041985058', 'Tree9+Fly', TRUE),
	('admin05011993066', 'Light8-Now', TRUE),
	('admin15031989071', 'Star5*Moon', TRUE),
	('admin_user', 'BienHecho2-*', TRUE);

INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES ('Rutilia Calderón Padilla', TRUE);

INSERT INTO AdmissionProcess (name_admission_process,id_academic_year, start_dateof_admission_process, end_dateof_admission_process, current_status_admission_process, status_admission_process) 
VALUES ('Proceso de Admisión 2024',2024,'2024-01-01','2024-12-31',FALSE,TRUE);

INSERT INTO InscriptionAdmissionProcess (id_admission_process,start_dateof_inscription_admission_process,end_dateof_inscription_admission_process,current_status_inscription_admission_process, status_inscription_admission_processs) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);

INSERT INTO DocumentValidationAdmissionProcess (id_admission_process, start_dateof_document_validation_admission_process, end_dateof_document_validation_admission_process, current_status_document_validation_admission_process, status_document_validation_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);

INSERT INTO AdmissionTestAdmissionProcess (id_admission_process, dateof_admission_test_admission_process, current_status_admission_test_admission_process, status_admission_test_admission_process) 
VALUES (1, '2024-01-01', FALSE, TRUE);

INSERT INTO RegistrationRatingAdmissionProcess (id_admission_process, start_dateof_registration_rating_admission_process, end_dateof_registration_rating_admission_process, current_status_registration_rating_admission_process, status_sending_registration_rating_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);

INSERT INTO SendingNotificationsAdmissionProcess ( id_admission_process, start_dateof_sending_notifications_admission_process, end_dateof_sending_notifications_admission_process, star_timeof_sending_notifications_admission_process, end_timeof_sending_notifications_admission_process, current_status_sending_notifications_admission_process, status_sending_notifications_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', '21:00:00', '04:00:00', FALSE, TRUE);

INSERT INTO AcceptanceAdmissionProcess (id_admission_process,start_dateof_acceptance_admission_process,end_dateof_acceptance_admission_process,current_status_acceptance_admission_process, status_acceptance_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);

INSERT INTO RectificationPeriodAdmissionProcess (id_admission_process, start_dateof_rectification_period_admission_process, end_dateof_rectification_period_admission_process, current_status_rectification_period_admission_process, status_rectification_period_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);

INSERT INTO DownloadApplicantAdmittedInformationAdmissionProcess (
	id_admission_process, start_dateof_download_applicant_information_admission_process, end_dateof_download_applicant_information_admission_process, current_status_download_applicant_information_admission_process, status_download_applicant_information_admission_process) 
VALUES (1, '2024-01-01', '2024-12-31', FALSE, TRUE);



INSERT INTO RegionalCenters (name_regional_center, acronym_regional_center, location_regional_center, email_regional_center, phone_number_regional_center, address_regional_center, status_regional_center) 
VALUES 
	('Ciudad Universitaria','CU','TEGUCIGALPA','formaciontecnologica@unah.edu.hn','2216-7000','Edificio "Alma mater" 8° Piso, UNAH, Tegucigalpa, Ciudad Universitaria',TRUE),
	('UNAH Valle de Sula','UNAH-VS','CORTES','rrpp.unahvs@unah.edu.hn','2545-6600','Edificio 3, UNAH-VS, Sector Pedregal, San Pedro Sula, Honduras, Centroamérica.',TRUE),
	('Centro Universitario Regional de Litoral Atlántico','CURLA','ATLANTIDA','desarrollo.inst@unah.edu.hn','2442-9500','Carretera CA-13 La Ceiba-Tela, desvío frente a Maxi-Despensa aeropuerto.',TRUE),
	('Centro Universitario Regional del Centro','CURC','COMAYAGUA','curc@unah.edu.hn','2771-5700','Carretera salida a Tegucigalpa, Colonia San Miguel, contiguo a Ferromax',TRUE),
	('Centro Univesitario Regional del Litoral Pacífico','CURLP','CHOLUTECA','info.curlp@unah.edu.hn','(+504)9484-9916','Km 5 salida a San Marcos de Colón, desvío a la derecha frente a Residencial Anda Lucía.',TRUE),
        ('Centro Universitario Regional de Occidente','CUROC','COPÁN','jordonez@unah.edu.hn','2662-3223','COPÁN',TRUE),
        ('Centro Tecnológico de Danlí','UNAH-TEC Danli ','EL PARAISO','tecdanli@unah.edu.hn','2763-9900','Danlí, carretera hacía El Paraíso, antes de llegar al Hospital Básico "Gabriela Alvarado".',TRUE),
        ('Centro Universitario Regional Nor-Oriental','CURNO','OLANCHO','curno@unah.edu.hn','1111-2222','OLANCHO',TRUE),
        ('Centro Tecnológico del Valle de Aguan','UNAH-TEC Aguán','YORO','tecaguan@unah.edu.hn','1111-2222','YORO',TRUE);

INSERT INTO Faculties (name_faculty, address_faculty, phone_number_faculty, email_faculty, status_faculty)
VALUES
    ('Facultad de Ingeniería', 'Ciudad Universitaria, Edificio B2. Tegucigalpa M.D.C. Honduras, Centroamérica', '2216-6100', 'facultaddeingenieria@unah.edu.hn', TRUE),
    ('Facultad de Ciencias', 'Edificio E1, 2da planta, Ciudad Universitaria Tegucigalpa, M.D.C. Honduras, Centroamérica.', '2216-5100', 'facultaddeciencias@unah.edu.hn', TRUE),
    ('Facultad de Humanidades y Artes', 'Bulevar Suyapa, Tegucigalpa M.D.C., Honduras Decanato Facultad de Humanidades y Artes Planta baja del Edificio F1 Ciudad Universitaria', '2216-6100', 'f.humanidadesyartes@unah.edu.hn', TRUE);

INSERT INTO Departments (name_departmet, id_faculty, status_department)
VALUES
    ('Departamento Ingeniería Agroindustrial', 1, TRUE),
    ('Departamento Ingeniería Agronómica', 1, TRUE),
    ('Departamento Ingeniería en Ciencias Acuícolas', 1, TRUE),
    ('Departamento Ingeniería Eléctrica Industrial', 1, TRUE),
    ('Departamento Ingeniería Forestal', 1, TRUE),
    ('Departamento Ingeniería Industrial', 1, TRUE),
    ('Departamento Ingeniería Mecánica Industrial', 1, TRUE),
    ('Departamento Ingeniería Química Industrial', 1, TRUE),
    ('Departamento Ingeniería en Sistemas', 1, TRUE),
    ('Departamento  Física', 2, TRUE),
    ('Departamento Biología', 2, TRUE),
    ('Departamento Matemática', 2, TRUE),
    ('Departamento de Arquitectura', 3, TRUE),
    ('Departamento de Arte', 3, TRUE),
    ('Departamento de Cultura Física y Deportes', 3, TRUE),
    ('Departamento de Filosofía', 3, TRUE),
    ('Departamento de Lenguas Extranjeras', 3, TRUE),
    ('Departamento de Letras', 3, TRUE),
    ('Departamento Pedagogía y Ciencias de la Educación', 3, TRUE),
    ('Departamento Ingeniería Civil', 1, TRUE);


INSERT INTO Undergraduates (name_undergraduate, id_department, status_undergraduate, duration_undergraduate, mode_undergraduate, study_plan_undergraduate)
VALUES
    ('Arquitectura', 13, TRUE, 5.0, 'Presencial', NULL),
    ('Ingeniería en Sistemas', 9, TRUE, 5.0, 'Presencial', NULL),
    ('Licenciatura en Letras', 18, TRUE, 5.0, 'Presencial', NULL),
    ('Licenciatura en Letras', 18, TRUE, 5.0, 'Presencial', NULL),
    ('Licenciatura en Matemática', 12, TRUE, 4.0, 'Presencial', NULL),
    ('Ingeniería en Software', 9, TRUE, 5.0, 'Presencial', NULL);

INSERT INTO DepartmentsRegionalCenters (id_department, id_regionalcenter, status_department_regional_center)
VALUES
    (9, 1, TRUE), (9, 4, TRUE), (9, 2, TRUE), (9, 5, TRUE), (9, 6, TRUE),
    (12, 1, TRUE), (12, 2, TRUE), (12, 3, TRUE), (12, 4, TRUE), (12, 5, TRUE), (12, 6, TRUE),
    (13, 1, TRUE),
    (18, 1, TRUE), (18, 2, TRUE);

INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
    (1, 1, TRUE),
    (2, 1, TRUE), (2, 2, TRUE), (2, 4, TRUE), (2, 5, TRUE), (2, 6, TRUE),(6, 1, TRUE),
    (3, 1, TRUE), (3, 2, TRUE),
    (4, 1, TRUE), (4, 2, TRUE);

INSERT INTO ApplicantType (name_aplicant_type, admission_test_aplicant, status_aplicant_type)
VALUES ('PRIMER INGRESO', TRUE , TRUE);

INSERT INTO TypesAdmissionTests (name_type_admission_tests, status_type_admission_tests) 
VALUES 
	('Pruebas Psicológicas Específicas de Arquitectura (PPEA)', TRUE),
	('Prueba de Aptitud Académica (PAA)', TRUE),
	('Prueba de Aprovechamiento Matemático (PAM)', TRUE),
	('Prueba de Conocimientos de las Ciencias Naturales y de la Salud (PCCNS)', TRUE);

INSERT INTO UndergraduateTypesAdmissionTests (id_type_admission_tests, id_undergraduate, required_rating, status_undergraduate_type_admission_tests) 
VALUES 
	(1, 1, 400, TRUE), (2, 1, 900.0, TRUE),
	(3, 2, 400, FALSE), (2, 2, 900, TRUE),
	(2, 3, 700, TRUE),
	(2, 4, 700, TRUE);

INSERT INTO Roles (role, description_role, status_role) 
VALUES 
	('Strategic Manager Admissions Process', 'Visualizar estadisticas del proceso de admisión.', TRUE),
	('Admissions Application Verification Assistant', 'Verificar la información personal y de solicitud de los aspirantes.', TRUE),
	('Applicant Support Assistant', 'Visualiza, busca y edita la información de los aspirantes.', TRUE),
	('Admissions Application Download Assistant', 'Descarga la información de las aplicaciones el proceso admisión.', TRUE),
	('Admissions Grade Entry Assistant', 'Carga de las notas de los exámenes de admisión de los solicitantes.', TRUE),
	('Admissions Admitted Applicants Download Assistant', 'Descarga la información de los aspirantes adminitos en el proceso admisión.', TRUE),
	('Student Registration Administrator', 'Registrar los datos personales de los nuevos estudiantes y  Crear cuentas de usuario en el sistema.', TRUE),
	('Faculty User Administrator', 'Crear y gestionar las cuentas de los nuevos docentes en el sistema.', TRUE),
	('Academic Planning Coordinator', 'Establecer y coordinar el proceso de planificación académica dentro de la facultad.', TRUE),
	('Process Administrator Cancellation Of Classes', 'Definir y gestionar el proceso para la cancelación de clases excepcionales ', TRUE),
	('Strategic Manager Faculty', 'Visualizar estadisticas de la facultad.', TRUE),
	('Prospective Student', 'Completar el proceso de inscripción y seleccionar la carrera deseada.', TRUE),
	('Department Head', 'Administración de un departamento.', TRUE),
	('Professor', 'Docente ', TRUE),
	('Student', 'Estudiante', TRUE),
	('Coordinator', 'Coordinador', TRUE);
	
INSERT INTO AccessControl (id_access_control, description_access_control, status_access_control)
VALUES
	('kNLbH8EI', 'Administrador Admisiones admissions-admin.html', TRUE),
	('P3pwBDfx', 'Administrador Admisiones see-inscriptions.html', TRUE),
	('Fz1YeRgv', 'Administrador Admisiones upload-grades.html', TRUE),
	('lwx50K7f', 'Administrador Admisiones Verificar la información personal y de solicitud de los aspirantes.', TRUE),
	('IeMfti20', 'Administrador Admisiones Visualiza, busca y edita la información de los aspirantes.', TRUE),
	('rllHaveq', 'Administrador Admisiones Descarga la información de las aplicaciones del proceso de admisión.', TRUE),
	('pFw9dYOw', 'Administrador Admisiones Descarga la información de los aspirantes admitidos en el proceso de admisión.', TRUE),
	('78mD0SYa', "Aspirante Seleccion de carrera results.html", TRUE),
	('dJPR7ohs', "Aspirante Inicio de sesion login.html", TRUE),
	('V3yWAxgH', 'Administrador Facultad Creación de usuarios y perfiles docentes, y establecimiento de fechas de planificación y cancelcación professor.html', TRUE),
	('zKQFIY69', 'Planeación y creación de planificación academica academic-planning.html', TRUE),
	('p62NcCiC', 'Dashboard  dashboard.html', TRUE),
	('2izGK2WC', 'Pagina principal de acceso docente index.html', TRUE),
	('bG8uB0wH', 'Administrador de reigstro upload-students.html', TRUE);

INSERT INTO AccessControlRoles (id_role, id_access_control, status_access_control_roles)
VALUES 
	(2, 'lwx50K7f', TRUE),
	(3, 'IeMfti20', TRUE),
	(4, 'rllHaveq', TRUE),
	(5, 'Fz1YeRgv', TRUE),
	(6, 'pFw9dYOw', TRUE),
	(12, '78mD0SYa', TRUE),
	(12, 'dJPR7ohs', TRUE),
	(8, 'V3yWAxgH', TRUE),
	(13, 'zKQFIY69', TRUE),
	(13, 'p62NcCiC', TRUE),
	(14, '2izGK2WC', TRUE),
	(7, 'bG8uB0wH', TRUE);

INSERT INTO RolesUsersAdmissionsAdministrator (id_user_admissions_administrator, id_role_admissions_administrator, status_role_admissions_administrator, id_regional_center)
VALUES 
	(2, 5, TRUE, 1),
	(2, 2, TRUE, 1),
    	(7, 2, TRUE, 1),
    	(8, 2, TRUE, 1),
 	(3, 3, TRUE, 1),
    	(4, 4, TRUE, 1),
    	(5, 5, TRUE, 1),
    	(6, 6, TRUE, 1);


INSERT INTO UsersFacultiesAdministrator (username_user_faculties_administrator, password_user_faculties_administrator, id_faculty, status_user_faculties_administrator)
VALUES
    ('facultyAdmin08011999015', 'Dark19*Fish', 1, TRUE);
INSERT INTO RolesUsersFacultiesAdministrator (id_user_faculties_administrator, id_role_faculties_administrator, status_role_faculties_administrator)
VALUES
    (1, 8, TRUE);

INSERT INTO `UsersRegistryAdministrator` (username_user_registry_administrator, password_user_registry_administrator, status_user_registry_administrator)
VALUES
	('20001001010', 'G4to-h1Draulico', TRUE),
	('20002002020', 'seDosa*4crobat1ca', TRUE);

INSERT INTO `RolesUsersRegistryAdministrator` (id_role_registry_administrator, id_user_registry_administrator, id_regional_center, status_role_registry_administrator)
VALUES
	(7, 1, 1, TRUE),
	(7, 2, 1, TRUE);

INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES ('SIN NOMNRE', FALSE);

INSERT INTO AcademicSchedules (start_timeof_classes, end_timeof_classes, status_academic_schedules)
VALUES 
('06:00:00', '07:00:00', TRUE),
('07:00:00', '08:00:00', TRUE),
('08:00:00', '09:00:00', TRUE),
('09:00:00', '10:00:00', TRUE),
('10:00:00', '11:00:00', TRUE),
('11:00:00', '12:00:00', TRUE),
('12:00:00', '13:00:00', TRUE),
('13:00:00', '14:00:00', TRUE),
('14:00:00', '15:00:00', TRUE),
('15:00:00', '16:00:00', TRUE),
('16:00:00', '17:00:00', TRUE),
('17:00:00', '18:00:00', TRUE),
('18:00:00', '19:00:00', TRUE),
('19:00:00', '20:00:00', TRUE),
('07:00:00', '10:00:00', TRUE),  
('10:00:00', '13:00:00', TRUE), 
('08:00:00', '12:00:00', TRUE),  
('14:00:00', '19:00:00', TRUE);

INSERT INTO Building (name_building, regionalcenters_building, status_building) VALUES
('A1', 1, TRUE),
('A2', 1, TRUE),
('B1', 1, TRUE),
('B2', 1, TRUE),
('C1', 1, TRUE),
('C2', 1, TRUE),
('C3', 1, TRUE),
('D1', 1, TRUE),
('E1', 1, TRUE),
('F1', 1, TRUE),
('G1', 1, TRUE),
('H1', 1, TRUE),
('K1', 1, TRUE),
('K2', 1, TRUE),
('I1', 1, TRUE),
('J1', 1, TRUE),
('EDIFICIO 1987', 1, TRUE),
('Edificio Alfa', 4, TRUE),
('Edificio Bravo', 4, TRUE),
('Edificio Delta', 4, TRUE),
('Edificio Omega', 4, TRUE);

INSERT INTO BuildingLevels (id_building, description_building_level, status_building_level) VALUES
(1, 'Nivel 1 de A1', TRUE),
(1, 'Nivel 2 de A1', TRUE),
(1, 'Nivel 3 de A1', TRUE),
(1, 'Nivel 4 de A1', TRUE),
(2, 'Nivel 1 de A2', TRUE),
(2, 'Nivel 2 de A2', TRUE),
(2, 'Nivel 3 de A2', TRUE),
(2, 'Nivel 4 de A2', TRUE),
(3, 'Nivel 1 de B1', TRUE),
(3, 'Nivel 2 de B1', TRUE),
(3, 'Nivel 3 de B1', TRUE),
(3, 'Nivel 4 de B1', TRUE),
(4, 'Nivel 1 de B2', TRUE),
(4, 'Nivel 2 de B2', TRUE),
(4, 'Nivel 3 de B2', TRUE),
(4, 'Nivel 4 de B2', TRUE),
(5, 'Nivel 1 de C1', TRUE),
(5, 'Nivel 2 de C1', TRUE),
(5, 'Nivel 3 de C1', TRUE),
(5, 'Nivel 4 de C1', TRUE),
(6, 'Nivel 1 de C2', TRUE),
(6, 'Nivel 2 de C2', TRUE),
(6, 'Nivel 3 de C2', TRUE),
(6, 'Nivel 4 de C2', TRUE),
(7, 'Nivel 1 de C3', TRUE),
(7, 'Nivel 2 de C3', TRUE),
(7, 'Nivel 3 de C3', TRUE),
(7, 'Nivel 4 de C3', TRUE),
(8, 'Nivel 1 de D1', TRUE),
(8, 'Nivel 2 de D1', TRUE),
(8, 'Nivel 3 de D1', TRUE),
(8, 'Nivel 4 de D1', TRUE),
(9, 'Nivel 1 de E1', TRUE),
(9, 'Nivel 2 de E1', TRUE),
(9, 'Nivel 3 de E1', TRUE),
(9, 'Nivel 4 de E1', TRUE),
(10, 'Nivel 1 de F1', TRUE),
(10, 'Nivel 2 de F1', TRUE),
(10, 'Nivel 3 de F1', TRUE),
(10, 'Nivel 4 de F1', TRUE),
(11, 'Nivel 1 de G1', TRUE),
(11, 'Nivel 2 de G1', TRUE),
(11, 'Nivel 3 de G1', TRUE),
(11, 'Nivel 4 de G1', TRUE),
(12, 'Nivel 1 de H1', TRUE),
(12, 'Nivel 2 de H1', TRUE),
(12, 'Nivel 3 de H1', TRUE),
(12, 'Nivel 4 de H1', TRUE),
(13, 'Nivel 1 de K1', TRUE),
(13, 'Nivel 2 de K1', TRUE),
(13, 'Nivel 3 de K1', TRUE),
(13, 'Nivel 4 de K1', TRUE),
(14, 'Nivel 1 de K2', TRUE),
(14, 'Nivel 2 de K2', TRUE),
(14, 'Nivel 3 de K2', TRUE),
(14, 'Nivel 4 de K2', TRUE),
(15, 'Nivel 1 de I1', TRUE),
(15, 'Nivel 2 de I1', TRUE),
(15, 'Nivel 3 de I1', TRUE),
(15, 'Nivel 4 de I1', TRUE),
(16, 'Nivel 1 de J1', TRUE),
(16, 'Nivel 2 de J1', TRUE),
(16, 'Nivel 3 de J1', TRUE),
(16, 'Nivel 4 de J1', TRUE),
(17, 'Nivel 1 de EDIFICIO 1987', TRUE),
(17, 'Nivel 2 de EDIFICIO 1987', TRUE),
(17, 'Nivel 3 de EDIFICIO 1987', TRUE),
(17, 'Nivel 4 de EDIFICIO 1987', TRUE),
(18, 'Nivel 1 de Edificio Alfa', TRUE),
(18, 'Nivel 2 de Edificio Alfa', TRUE),
(18, 'Nivel 3 de Edificio Alfa', TRUE),
(18, 'Nivel 4 de Edificio Alfa', TRUE),
(19, 'Nivel 1 de Edificio Bravo', TRUE),
(19, 'Nivel 2 de Edificio Bravo', TRUE),
(19, 'Nivel 3 de Edificio Bravo', TRUE),
(19, 'Nivel 4 de Edificio Bravo', TRUE),
(20, 'Nivel 1 de Edificio Delta', TRUE),
(20, 'Nivel 2 de Edificio Delta', TRUE),
(20, 'Nivel 3 de Edificio Delta', TRUE),
(20, 'Nivel 4 de Edificio Delta', TRUE),
(21, 'Nivel 1 de Edificio Omega', TRUE),
(21, 'Nivel 2 de Edificio Omega', TRUE),
(21, 'Nivel 3 de Edificio Omega', TRUE),
(21, 'Nivel 4 de Edificio Omega', TRUE);

INSERT INTO Classrooms (name_classroom, description_classroom, building_level_classroom, status_classroom) VALUES
('B1-1', 'Aula 1 en el Nivel 1 de B1', 1, TRUE),
('B1-2', 'Aula 2 en el Nivel 1 de B1', 1, TRUE),
('B1-3', 'Aula 3 en el Nivel 1 de B1', 1, TRUE),
('B1-4', 'Aula 4 en el Nivel 1 de B1', 1, TRUE),
('B1-5', 'Aula 5 en el Nivel 1 de B1', 1, TRUE),
('B1-1', 'Aula 1 en el Nivel 2 de B1', 2, TRUE),
('B1-2', 'Aula 2 en el Nivel 2 de B1', 2, TRUE),
('B1-3', 'Aula 3 en el Nivel 2 de B1', 2, TRUE),
('B1-4', 'Aula 4 en el Nivel 2 de B1', 2, TRUE),
('B1-5', 'Aula 5 en el Nivel 2 de B1', 2, TRUE),
('B1-1', 'Aula 1 en el Nivel 3 de B1', 3, TRUE),
('B1-2', 'Aula 2 en el Nivel 3 de B1', 3, TRUE),
('B1-3', 'Aula 3 en el Nivel 3 de B1', 3, TRUE),
('B1-4', 'Aula 4 en el Nivel 3 de B1', 3, TRUE),
('B1-5', 'Aula 5 en el Nivel 3 de B1', 3, TRUE),
('B1-1', 'Aula 1 en el Nivel 4 de B1', 4, TRUE),
('B1-2', 'Aula 2 en el Nivel 4 de B1', 4, TRUE),
('B1-3', 'Aula 3 en el Nivel 4 de B1', 4, TRUE),
('B1-4', 'Aula 4 en el Nivel 4 de B1', 4, TRUE),
('B1-5', 'Aula 5 en el Nivel 4 de B1', 4, TRUE),
('B2-1', 'Aula 1 en el Nivel 1 de B2', 5, TRUE),
('B2-2', 'Aula 2 en el Nivel 1 de B2', 5, TRUE),
('B2-3', 'Aula 3 en el Nivel 1 de B2', 5, TRUE),
('B2-4', 'Aula 4 en el Nivel 1 de B2', 5, TRUE),
('B2-5', 'Aula 5 en el Nivel 1 de B2', 5, TRUE),
('B2-1', 'Aula 1 en el Nivel 2 de B2', 6, TRUE),
('B2-2', 'Aula 2 en el Nivel 2 de B2', 6, TRUE),
('B2-3', 'Aula 3 en el Nivel 2 de B2', 6, TRUE),
('B2-4', 'Aula 4 en el Nivel 2 de B2', 6, TRUE),
('B2-5', 'Aula 5 en el Nivel 2 de B2', 6, TRUE),
('B2-1', 'Aula 1 en el Nivel 3 de B2', 7, TRUE),
('B2-2', 'Aula 2 en el Nivel 3 de B2', 7, TRUE),
('B2-3', 'Aula 3 en el Nivel 3 de B2', 7, TRUE),
('B2-4', 'Aula 4 en el Nivel 3 de B2', 7, TRUE),
('B2-5', 'Aula 5 en el Nivel 3 de B2', 7, TRUE),
('B2-1', 'Aula 1 en el Nivel 4 de B2', 8, TRUE),
('B2-2', 'Aula 2 en el Nivel 4 de B2', 8, TRUE),
('B2-3', 'Aula 3 en el Nivel 4 de B2', 8, TRUE),
('B2-4', 'Aula 4 en el Nivel 4 de B2', 8, TRUE),
('B2-5', 'Aula 5 en el Nivel 4 de B2', 8, TRUE),
('D1-1', 'Aula 1 en el Nivel 1 de D1', 9, TRUE),
('D1-2', 'Aula 2 en el Nivel 1 de D1', 9, TRUE),
('D1-3', 'Aula 3 en el Nivel 1 de D1', 9, TRUE),
('D1-4', 'Aula 4 en el Nivel 1 de D1', 9, TRUE),
('D1-5', 'Aula 5 en el Nivel 1 de D1', 9, TRUE),
('D1-1', 'Aula 1 en el Nivel 2 de D1', 10, TRUE),
('D1-2', 'Aula 2 en el Nivel 2 de D1', 10, TRUE),
('D1-3', 'Aula 3 en el Nivel 2 de D1', 10, TRUE),
('D1-4', 'Aula 4 en el Nivel 2 de D1', 10, TRUE),
('D1-5', 'Aula 5 en el Nivel 2 de D1', 10, TRUE),
('D1-1', 'Aula 1 en el Nivel 3 de D1', 11, TRUE),
('D1-2', 'Aula 2 en el Nivel 3 de D1', 11, TRUE),
('D1-3', 'Aula 3 en el Nivel 3 de D1', 11, TRUE),
('D1-4', 'Aula 4 en el Nivel 3 de D1', 11, TRUE),
('D1-5', 'Aula 5 en el Nivel 3 de D1', 11, TRUE),
('D1-1', 'Aula 1 en el Nivel 4 de D1', 12, TRUE),
('D1-2', 'Aula 2 en el Nivel 4 de D1', 12, TRUE),
('D1-3', 'Aula 3 en el Nivel 4 de D1', 12, TRUE),
('D1-4', 'Aula 4 en el Nivel 4 de D1', 12, TRUE),
('D1-5', 'Aula 5 en el Nivel 4 de D1', 12, TRUE),
('Alfa-1', 'Aula 1 en el Nivel 1 de Edificio Alfa', 18, TRUE),
('Alfa-2', 'Aula 2 en el Nivel 1 de Edificio Alfa', 18, TRUE),
('Alfa-3', 'Aula 3 en el Nivel 1 de Edificio Alfa', 18, TRUE),
('Alfa-4', 'Aula 4 en el Nivel 1 de Edificio Alfa', 18, TRUE),
('Alfa-5', 'Aula 5 en el Nivel 1 de Edificio Alfa', 18, TRUE),
('Bravo-1', 'Aula 1 en el Nivel 1 de Edificio Bravo', 19, TRUE),
('Bravo-2', 'Aula 2 en el Nivel 1 de Edificio Bravo', 19, TRUE),
('Bravo-3', 'Aula 3 en el Nivel 1 de Edificio Bravo', 19, TRUE),
('Bravo-4', 'Aula 4 en el Nivel 1 de Edificio Bravo', 19, TRUE),
('Bravo-5', 'Aula 5 en el Nivel 1 de Edificio Bravo', 19, TRUE);


INSERT INTO BuildingsDepartmentsRegionalsCenters (department_regional_center, building_department_regionalcenter, status_building_department_regionalcenter)
VALUES 
(1, 3, TRUE),
(1, 4, TRUE),
(1, 8, TRUE),
(2, 18, TRUE),
(2, 19, TRUE);

INSERT INTO ClassroomsBuildingsDepartmentsRegionalCenters (building_department_regional_center, id_classroom, status_classroom_building_department_regionalcenter)
VALUES 
(1, 1, TRUE), (1, 8, TRUE),
(2, 21, TRUE), (2, 28, TRUE),
(3, 41, TRUE), (3, 55, TRUE),
(4, 61, TRUE),
(5, 68, TRUE);

INSERT INTO AcademicPeriodicity (description_academic_periodicity, numberof_months_academic_periodicity, status_academic_periodicity) 
VALUES 
('Semestral', 6, TRUE),
('Trimestral', 4, TRUE);

INSERT INTO classes (name_class, description_class, credit_units, hours_required_week, department_class, academic_periodicity_class, class_service, status_class)
VALUES 
('Introducción a la Ingeniería en Sistemas', 'Curso introductorio sobre los fundamentos de la ingeniería en sistemas', 4, 4, 9, 2, FALSE, TRUE),
('Programación II', 'Curso intermedio de programación, basado en los conocimientos fundamentales', 4, 4, 9, 2, FALSE, TRUE),
('Circuitos Eléctricos para Ingeniería en Sistemas', 'Curso enfocado en circuitos eléctricos para la ingeniería en sistemas', 4, 4, 9, 2, FALSE, TRUE),
('Algoritmos y Estructura de Datos', 'Curso sobre algoritmos y estructuras de datos para la ingeniería en sistemas', 4, 4, 9, 2, FALSE, TRUE),
('Programación I', 'Curso introductorio a la programación básica en diversos lenguajes', 4, 4, 9, 2, TRUE, TRUE),
('Ecuaciones Diferenciales', 'Curso sobre el estudio de ecuaciones diferenciales y sus aplicaciones', 4, 4, 9, 2, TRUE, TRUE),
('Ingeniería de Software I', 'Curso sobre los principios fundamentales de la ingeniería de software', 4, 4, 9, 2, FALSE, TRUE),
('Ingeniería de Software II', 'Curso intermedio sobre el diseño, desarrollo y mantenimiento de software', 4, 4, 9, 2, FALSE, TRUE),
('Gestión de Proyectos de Software', 'Curso sobre la planificación y gestión de proyectos de software', 4, 4, 9, 2, FALSE, TRUE),
('Pruebas de Software', 'Curso sobre las metodologías y técnicas para la prueba de software', 4, 4, 9, 2, FALSE, TRUE),
('Arquitectura de Software', 'Curso que abarca el diseño y la implementación de arquitecturas de software', 4, 4, 9, 2, FALSE, TRUE);

INSERT INTO UndergraduateClass (id_undergraduate, id_class, status_undergraduate_class)
VALUES 
(2, 1, TRUE), (2, 2, TRUE), (2, 3, TRUE),(2, 4, TRUE),(2, 5, TRUE),(2, 6, TRUE),
(6, 5, TRUE), (6, 6, TRUE), (6, 7, TRUE), (6, 8, TRUE), (6, 9, TRUE), (6, 10, TRUE), (6, 11, TRUE);



INSERT INTO DatesAcademicPeriodicityYear (
    id_academic_periodicity, id_academic_year, 
    start_dateof_academic_periodicity, end_dateof_academic_periodicity, 
    start_dateof_class_enrollment, end_dateof_class_enrollment, 
    start_dateof_classes, end_dateof_classes, 
    start_dateof_exam_retake_students, end_dateof_exam_retake_students, 
    start_datefor_recording_grades, end_datefor_recording_grades, 
    description_dates_academic_periodicity_year, status_dates_academic_periodicity_year) 
VALUES 
(2, 2024, '2024-09-09', '2024-12-20', '2024-09-07', '2024-09-10', '2024-09-11', '2024-12-13', '2024-11-29', '2024-12-17', '2024-12-16', '2024-12-18', 'Tercer Periodo académico', TRUE),
(2, 2024, '2024-01-17', '2024-05-10', '2024-01-18', '2024-01-21', '2024-01-22', '2024-05-03', '2024-04-23', '2024-05-07', '2024-05-06', '2024-05-08', 'Primer Periodo académico', FALSE), 
(1, 2024, '2024-07-08', '2024-12-20', '2024-07-07', '2024-07-09', '2024-07-10', '2024-12-13', '2024-11-29', '2024-12-17', '2024-12-16', '2024-12-18', 'Segundo Semestre académico', TRUE),
(1, 2025, '2025-01-30', '2025-12-20', '2025-01-30', '2025-12-20', '2025-01-30', '2025-12-20', '2025-01-30', '2025-12-20', '2025-01-30', '2025-12-20', 'Primer Periodo académico', FALSE);


INSERT INTO AcademicPlanningProcess (
    date_academic_periodicity_academic_planning_process,  
    start_dateof_academic_planning_process, end_dateof_academic_planning_process, 
    status_academic_planning_process) 
VALUES 
(4, '2024-10-1', '2024-12-20',TRUE);

INSERT INTO ProfessorsObligations 
(maximum_credit_units_professor_obligation, minimum_credit_units_professor_obligation, status_professor_oblgation) 
VALUES
(15, 12, TRUE);


INSERT INTO Professors 
(first_name_professor, second_name_professor, third_name_professor, first_lastname_professor, second_lastname_professor, email_professor, id_professors_obligations, id_regional_center, status_professor) 
VALUES
('Carlos', 'Alberto', NULL, 'Pérez', 'López', 'carlos.perez@example.com', 1, 1, TRUE),
('María', NULL, NULL, 'González', 'Martínez', 'maria.gonzalez@example.com', 1, 1, TRUE),
('Juan', 'Francisco', 'José', 'Ramírez', NULL, 'juan.ramirez@example.com', 1, 1, TRUE),
('Ana', 'Luisa', NULL, 'Hernández', 'Díaz', 'ana.hernandez@example.com', 1, 1, TRUE),
('Lucía', NULL, NULL, 'Vargas', 'Morales', 'lucia.vargas@example.com', 1, 1, TRUE),
('Roberto', NULL, 'Carlos', 'Navarro', 'Pineda', 'roberto.navarro@example.com', 1, 1, TRUE),
('Jorge', 'Luis', NULL, 'Castro', 'Gómez', 'jorge.castro@example.com', 1, 4, TRUE),
('Diana', 'Paola', NULL, 'Moreno', 'Zapata', 'diana.moreno@example.com', 1, 4, TRUE),
('César', NULL, NULL, 'Ortega', 'Méndez', 'cesar.ortega@example.com', 1, 4, TRUE);

INSERT INTO `UsersProfessors` (username_user_professor, password_user_professor, status_user_professor)
VALUES
(1, 'd4nDadAn', TRUE);

INSERT INTO `RolesUsersProfessor` (id_user_professor, id_role_professor, status_role_professor)
VALUES
(1, 14, TRUE),
(1, 8, TRUE),
(1, 16, TRUE),
(1, 13, TRUE);

INSERT INTO ProfessorsDepartments (id_department, id_professor, status_professor_department) 
VALUES
(9, 1, 'active'),
(9, 2, 'active'),
(9, 3, 'active'),
(9, 4, 'active'),
(9, 5, 'active'),
(9, 6, 'active'),
(9, 7, 'active'),
(9, 8, 'active'),
(9, 9, 'active');

INSERT INTO WorkingHours (name_working_hour, day_week_working_hour, check_in_time_working_hour, check_out_time_working_hour, status_working_hour) 
VALUES
('Turno Mañana (08:00 - 14:00)', 'Lunes', '08:00:00', '14:00:00', TRUE),
('Turno Mañana (08:00 - 14:00)', 'Martes', '08:00:00', '14:00:00', TRUE),
('Turno Mañana (08:00 - 14:00)', 'Miércoles', '08:00:00', '14:00:00', TRUE),
('Turno Mañana (08:00 - 14:00)', 'Jueves', '08:00:00', '14:00:00', TRUE),
('Turno Mañana (08:00 - 14:00)', 'Viernes', '08:00:00', '14:00:00', TRUE),
('Turno Mañana (09:00 - 15:00)', 'Lunes', '09:00:00', '15:00:00', TRUE),
('Turno Mañana (09:00 - 15:00)', 'Martes', '09:00:00', '15:00:00', TRUE),
('Turno Mañana (09:00 - 15:00)', 'Miércoles', '09:00:00', '15:00:00', TRUE),
('Turno Mañana (09:00 - 15:00)', 'Jueves', '09:00:00', '15:00:00', TRUE),
('Turno Mañana (09:00 - 15:00)', 'Viernes', '09:00:00', '15:00:00', TRUE),
('Turno Mañana (10:00 - 16:00)', 'Lunes', '10:00:00', '16:00:00', TRUE),
('Turno Mañana (10:00 - 16:00)', 'Martes', '10:00:00', '16:00:00', TRUE),
('Turno Mañana (10:00 - 16:00)', 'Miércoles', '10:00:00', '16:00:00', TRUE),
('Turno Mañana (10:00 - 16:00)', 'Jueves', '10:00:00', '16:00:00', TRUE),
('Turno Mañana (10:00 - 16:00)', 'Viernes', '10:00:00', '16:00:00', TRUE),
('Turno Mañana (08:30 - 14:30)', 'Lunes', '08:30:00', '14:30:00', TRUE),
('Turno Mañana (08:30 - 14:30)', 'Martes', '08:30:00', '14:30:00', TRUE),
('Turno Mañana (08:30 - 14:30)', 'Miércoles', '08:30:00', '14:30:00', TRUE),
('Turno Mañana (08:30 - 14:30)', 'Jueves', '08:30:00', '14:30:00', TRUE),
('Turno Mañana (08:30 - 14:30)', 'Viernes', '08:30:00', '14:30:00', TRUE),
('Turno Mañana (07:30 - 13:30)', 'Lunes', '07:30:00', '13:30:00', TRUE),
('Turno Mañana (07:30 - 13:30)', 'Martes', '07:30:00', '13:30:00', TRUE),
('Turno Mañana (07:30 - 13:30)', 'Miércoles', '07:30:00', '13:30:00', TRUE),
('Turno Mañana (07:30 - 13:30)', 'Jueves', '07:30:00', '13:30:00', TRUE),
('Turno Mañana (07:30 - 13:30)', 'Viernes', '07:30:00', '13:30:00', TRUE);


INSERT INTO ProfessorsDepartmentsWorkingHours (id_professor_department, id_working_hour, id_dates_academic_periodicity_year, status_working_hour) 
VALUES
(1, 1, 4, TRUE),
(1, 2, 4, TRUE),
(1, 3, 4, TRUE),
(1, 4, 4, TRUE),
(1, 5, 4, TRUE),
(2, 1, 4, TRUE),
(2, 1, 4, TRUE),
(2, 1, 4, TRUE),
(2, 1, 4, TRUE),
(2, 1, 4, TRUE),
(3, 2, 4, TRUE),
(3, 2, 4, TRUE),
(3, 2, 4, TRUE),
(3, 2, 4, TRUE),
(3, 2, 4, TRUE),
(4, 3, 4, TRUE),
(4, 3, 4, TRUE),
(4, 3, 4, TRUE),
(4, 3, 4, TRUE),
(4, 3, 4, TRUE),
(5, 4, 4, TRUE),
(5, 4, 4, TRUE),
(5, 4, 4, TRUE),
(5, 4, 4, TRUE),
(5, 4, 4, TRUE),
(6, 5, 4, TRUE),
(6, 5, 4, TRUE),
(6, 5, 4, TRUE),
(6, 5, 4, TRUE),
(6, 5, 4, TRUE),
(7, 2, 4, TRUE),
(7, 1, 4, TRUE),
(7, 3, 4, TRUE),
(7, 4, 4, TRUE),
(7, 5, 4, TRUE),
(8, 1, 4, TRUE),
(8, 1, 4, TRUE),
(8, 1, 4, TRUE),
(8, 1, 4, TRUE),
(8, 1, 4, TRUE),
(9, 2, 4, TRUE),
(9, 2, 4, TRUE),
(9, 2, 4, TRUE),
(9, 2, 4, TRUE),
(9, 2, 4, TRUE);

INSERT INTO DepartmentHeadObligations (credit_units_department_head_obligations, status_units_department_head_obligations) 
VALUES (5, TRUE);

INSERT INTO DepartmentHead (id_professor, id_department, id_department_head_obligations, status_department_head) 
VALUES (1, 9, 1, TRUE);

INSERT INTO DepartmentHeadValidityPeriod (id_department_head, start_date_department_head_validity_period, end_date_department_head_validity_period, actual_end_date_department_head_validity_period, status_department_head_validity_period) 
VALUES (1, '2024-01-01', '2024-12-31', NULL, TRUE);

INSERT INTO DepartmentHeadWorkingHours (id_department_head, id_working_hour, status_department_head_working_hours) 
VALUES
(1, 1, TRUE), 
(1, 2, TRUE), 
(1, 3, TRUE), 
(1, 4, TRUE),
(1, 5, TRUE); 

INSERT INTO `Students` (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, third_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES
('20201001559', 'enrique.valenzuela@unah.hn', '0801200049092', 'Enrique', null, null, 'Valenzuela', null, 'Col. Kennedy', 'enrique.valenzuela@example.com', '93940294', TRUE);

INSERT INTO `ClassSections` (id_class, id_dates_academic_periodicity_year, id_classroom_class_section, id_academic_schedules, id_professor_class_section, numberof_spots_available_class_section, status_class_section)
VALUES
(1, 1, 27, 4, 1, 25, TRUE),
(2, 1, 28, 4, 1, 25, TRUE),
(3, 1, 26, 5, 1, 15, TRUE);

INSERT INTO `ClassSectionsProfessor` (id_class_section, class_presentation_video, status_class_section_professor)
VALUES
(1, 'www.youtube.com', TRUE),
(2, 'www.youtube.com', TRUE);

INSERT INTO `ClassSectionsDays` (id_class_section, id_day, status_class_sections_days)
VALUES
(1, 'Lunes', TRUE),
(1, 'Martes', TRUE),
(1, 'Miercoles', TRUE),
(1, 'Jueves', TRUE),
(1, 'Viernes', TRUE),
(2, 'Lunes', TRUE),
(2, 'Martes', TRUE),
(2, 'Miercoles', TRUE),
(2, 'Jueves', TRUE),
(2, 'Viernes', TRUE);

INSERT INTO `EnrollmentClassSections` (id_student, id_class_section, status_enrollment_class_sections)
VALUES
('20201001559', '1', TRUE),
('20201001559', '2', TRUE),
('20201001559', '3', TRUE);

INSERT INTO `RequestsCancellationExceptionalClasses` (id_student, reasons_request_cancellation_exceptional_classes, document_request_cancellation_exceptional_classes, evidence_request_cancellation_exceptional_classes, status_request_cancellation_exceptional_classes)
VALUES
('20201001559', 'Calamidad domestica', '', null, TRUE);

