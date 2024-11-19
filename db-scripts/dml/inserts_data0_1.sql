USE unah_registration;

INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES ('Rutilia Calderón Padilla', TRUE);

INSERT INTO AdmissionProcess (name_admission_process,id_academic_year, start_dateof_admission_process, end_dateof_admission_process, timeof_sending_notifications_admission_process, current_status_admission_process, status_admission_process) 
VALUES ('Proceso de Admisión 2024',2024,'2024-01-01','2024-12-31','09:00:00',FALSE,TRUE);


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


INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, status_applicant)
VALUES
    ('0801200001345', 'Juan', 'Carlos', NULL, 'Pérez', 'González', 'juan.perez@gmail.com', '9878-1234', 'Tegucigalpa, Francisco Morazan, Colonia el Infiernito', TRUE),
    ('1015200109876', 'María', NULL, NULL, 'Lopez', 'Ramírez', 'maria.lopez@gmail.com', '9629-5678', 'La Esperanza, Intibuca, aldea el Ojolote', TRUE),
    ('0101199923846', 'Luis', 'Alberto', 'José', 'Hernández', NULL, 'luis.hernandez@gmail.com', '8956-9101', 'Atlandida, caserio los naranjos', TRUE),
    ('150120039444', 'Josue', 'Ramon', 'Antonio', 'Salgado', 'Nuñes', 'josueramonantoniosalgadonunes@gmail.com', '9615-0101', 'Juticalpa, barrio concepción', TRUE);

INSERT INTO Applications ( id_admission_process, id_applicant, id_aplicant_type, secondary_certificate_applicant, idregional_center, regionalcenter_admissiontest_applicant, intendedprimary_undergraduate_applicant, intendedsecondary_undergraduate_applicant, status_application)
VALUES
    (1, '0801200001345', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 3, 1, TRUE),
    (1, '1015200109876', 1, UNHEX('48656C6C6F20576F726C64'), 2, 2, 2, 4, TRUE),
    (1, '0101199923846', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 2, 4, FALSE),
    (1, '150120039444', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 2, 3, TRUE);

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

INSERT INTO RatingApplicantsTest ( id_admission_application_number,  id_type_admission_tests,  rating_applicant,  status_rating_applicant_test) 
VALUES 
	(1, 1, 0, FALSE), (1, 2, 0, FALSE), 
	(2, 2, 0, FALSE), 
	(3, 2, 0, FALSE), 
	(4, 2, 0, FALSE); 



