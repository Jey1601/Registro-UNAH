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

INSERT INTO UsersFacultiesAdministrator (username_user_faculties_administrator, password_user_faculties_administrator, id_faculty, status_user_faculties_administrator)
VALUES
    ('facultyAdmin08011999015', 'Dark19*Fish', 1, TRUE);

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
    ('Licenciatura en Matemática', 12, TRUE, 4.0, 'Presencial', NULL);

INSERT INTO DepartmentsRegionalCenters (id_department, id_regionalcenter, status_department_regional_center)
VALUES
    (9, 1, TRUE), (9, 4, TRUE), (9, 2, TRUE), (9, 5, TRUE), (9, 6, TRUE),
    (12, 1, TRUE), (12, 2, TRUE), (12, 3, TRUE), (12, 4, TRUE), (12, 5, TRUE), (12, 6, TRUE),
    (13, 1, TRUE),
    (18, 1, TRUE), (18, 2, TRUE);

INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
    (1, 1, TRUE),
    (2, 1, TRUE), (2, 2, TRUE), (2, 4, TRUE), (2, 5, TRUE), (2, 6, TRUE),
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





