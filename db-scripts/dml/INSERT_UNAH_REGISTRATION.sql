USE unah_registration;

INSERT INTO UsersAdmissionsAdministrator (username_user_admissions_administrator, password_user_admissions_administrator, status_user_admissions_administrator) 
VALUES 
	('admin_nouser', 'MiContraseña1+', 0),
	('admin_user', 'BienHecho2-*', 1);

INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES ('Rutilia Calderón Padilla', 1);

INSERT INTO AdmissionProcess (name_admission_process,id_academic_year, start_dateof_admission_process, end_dateof_admission_process, timeof_sending_notifications_admission_process, current_status_admission_process, status_admission_process) 
VALUES ('Proceso de Admisión 2024',2024,'2024-01-01','2024-12-31','09:00:00',1,1);


INSERT INTO RegionalCenters (name_regional_center, acronym_regional_center, location_regional_center, email_regional_center, phone_number_regional_center, address_regional_center, status_regional_center) 
VALUES 
	('Ciudad Universitaria','CU','TEGUCIGALPA','formaciontecnologica@unah.edu.hn','2216-7000','Edificio "Alma mater" 8° Piso, UNAH, Tegucigalpa, Ciudad Universitaria',1),
	('UNAH Valle de Sula','UNAH-VS','CORTES','rrpp.unahvs@unah.edu.hn','2545-6600','Edificio 3, UNAH-VS, Sector Pedregal, San Pedro Sula, Honduras, Centroamérica.',1),
	('Centro Universitario Regional de Litoral Atlántico','CURLA','ATLANTIDA','desarrollo.inst@unah.edu.hn','2442-9500','Carretera CA-13 La Ceiba-Tela, desvío frente a Maxi-Despensa aeropuerto.',1),
	('Centro Universitario Regional del Centro','CURC','COMAYAGUA','curc@unah.edu.hn','2771-5700','Carretera salida a Tegucigalpa, Colonia San Miguel, contiguo a Ferromax',1),
	('Centro Univesitario Regional del Litoral Pacífico','CURLP','CHOLUTECA','info.curlp@unah.edu.hn','(+504)9484-9916','Km 5 salida a San Marcos de Colón, desvío a la derecha frente a Residencial Anda Lucía.',1),
        ('Centro Universitario Regional de Occidente','CUROC','COPÁN','jordonez@unah.edu.hn','2662-3223','COPÁN',1),
        ('Centro Tecnológico de Danlí','UNAH-TEC Danli ','EL PARAISO','tecdanli@unah.edu.hn','2763-9900','Danlí, carretera hacía El Paraíso, antes de llegar al Hospital Básico "Gabriela Alvarado".',1),
        ('Centro Universitario Regional Nor-Oriental','CURNO','OLANCHO','curno@unah.edu.hn','1111-2222','OLANCHO',1),
        ('Centro Tecnológico del Valle de Aguan','UNAH-TEC Aguán','YORO','tecaguan@unah.edu.hn','1111-2222','YORO',1);

INSERT INTO Faculties (name_faculty, address_faculty, phone_number_faculty, email_faculty, status_faculty)
VALUES
    ('Facultad de Ingeniería', 'Ciudad Universitaria, Edificio B2. Tegucigalpa M.D.C. Honduras, Centroamérica', '2216-6100', 'facultaddeingenieria@unah.edu.hn', 1),
    ('Facultad de Ciencias', 'Edificio E1, 2da planta, Ciudad Universitaria Tegucigalpa, M.D.C. Honduras, Centroamérica.', '2216-5100', 'facultaddeciencias@unah.edu.hn', 1),
    ('Facultad de Humanidades y Artes', 'Bulevar Suyapa, Tegucigalpa M.D.C., Honduras Decanato Facultad de Humanidades y Artes Planta baja del Edificio F1 Ciudad Universitaria', '2216-6100', 'f.humanidadesyartes@unah.edu.hn', 1);

INSERT INTO Departments (name_departmet, id_faculty, status_department)
VALUES
    ('Departamento Ingeniería Agroindustrial', 1, 1),
    ('Departamento Ingeniería Agronómica', 1, 1),
    ('Departamento Ingeniería en Ciencias Acuícolas', 1, 1),
    ('Departamento Ingeniería Eléctrica Industrial', 1, 1),
    ('Departamento Ingeniería Forestal', 1, 1),
    ('Departamento Ingeniería Industrial', 1, 1),
    ('Departamento Ingeniería Mecánica Industrial', 1, 1),
    ('Departamento Ingeniería Química Industrial', 1, 1),
    ('Departamento Ingeniería en Sistemas', 1, 1),
    ('Departamento  Física', 2, 1),
    ('Departamento Biología', 2, 1),
    ('Departamento Matemática', 2, 1),
    ('Departamento de Arquitectura', 3, 1),
    ('Departamento de Arte', 3, 1),
    ('Departamento de Cultura Física y Deportes', 3, 1),
    ('Departamento de Filosofía', 3, 1),
    ('Departamento de Lenguas Extranjeras', 3, 1),
    ('Departamento de Letras', 3, 1),
    ('Departamento Pedagogía y Ciencias de la Educación', 3, 1),
    ('Departamento Ingeniería Civil', 1, 1);


INSERT INTO Undergraduates (name_undergraduate, id_department, status_undergraduate, duration_undergraduate, mode_undergraduate, study_plan_undergraduate)
VALUES
    ('Arquitectura', 13, 1, 5.0, 'Presencial', NULL),
    ('Ingeniería en Sistemas', 9, 1, 5.0, 'Presencial', NULL),
    ('Licenciatura en Letras', 18, 1, 5.0, 'Presencial', NULL),
    ('Licenciatura en Matemática', 12, 1, 4.0, 'Presencial', NULL);

INSERT INTO DepartmentsRegionalCenters (id_department, id_regionalcenter, status_department_regional_center)
VALUES
    (9, 1, 1), (9, 4, 1), (9, 2, 1), (9, 5, 1), (9, 6, 1),
    (12, 1, 1), (12, 2, 1), (12, 3, 1), (12, 4, 1), (12, 5, 1), (12, 6, 1),
    (13, 1, 1),
    (18, 1, 1), (18, 2, 1);

INSERT INTO UndergraduatesRegionalCenters (id_undergraduate, id_regionalcenter, status_undergraduate_Regional_Center)
VALUES
    (1, 1, 1),
    (2, 1, 1), (2, 2, 1), (2, 4, 1), (2, 5, 1), (2, 6, 1),
    (3, 1, 1), (3, 2, 1),
    (4, 1, 1), (4, 2, 1);

INSERT INTO ApplicantType (name_aplicant_type, admission_test_aplicant, status_aplicant_type)
VALUES ('PRIMER INGRESO', 1 , 1);


/*INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, status_applicant)
VALUES
    ('0801200001345', 'Juan', 'Carlos', NULL, 'Pérez', 'González', 'juan.perez@gmail.com', '9878-1234', 'Tegucigalpa, Francisco Morazan, Colonia el Infiernito', 1),
    ('1015200109876', 'María', NULL, NULL, 'Lopez', 'Ramírez', 'maria.lopez@gmail.com', '9629-5678', 'La Esperanza, Intibuca, aldea el Ojolote', 1),
    ('0101199923846', 'Luis', 'Alberto', 'José', 'Hernández', NULL, 'luis.hernandez@gmail.com', '8956-9101', 'Atlandida, caserio los naranjos', 1),
    ('1501200309444', 'Josue', 'Ramon', 'Antonio', 'Salgado', 'Nuñes', 'josueramonantoniosalgadonunes@gmail.com', '9615-0101', 'Juticalpa, barrio concepción', 1);

INSERT INTO Applications ( id_admission_process, id_applicant, id_aplicant_type, secondary_certificate_applicant, idregional_center, regionalcenter_admissiontest_applicant, intendedprimary_undergraduate_applicant, intendedsecondary_undergraduate_applicant, status_application)
VALUES
    (1, '0801200001345', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 3, 1, 1),
    (1, '1015200109876', 1, UNHEX('48656C6C6F20576F726C64'), 2, 2, 2, 4, 1),
    (1, '0101199923846', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 2, 4, 0),
    (1, '1501200309444', 1, UNHEX('48656C6C6F20576F726C64'), 1, 1, 2, 3, 1);*/

INSERT INTO TypesAdmissionTests (name_type_admission_tests, status_type_admission_tests) 
VALUES 
	('Pruebas Psicológicas Específicas de Arquitectura (PPEA)', 1),
	('Prueba de Aptitud Académica (PAA)', 1),
	('Prueba de Aprovechamiento Matemático (PAM)', 1),
	('Prueba de Conocimientos de las Ciencias Naturales y de la Salud (PCCNS)', 1);

INSERT INTO UndergraduateTypesAdmissionTests (id_type_admission_tests, id_undergraduate, required_rating, status_undergraduate_type_admission_tests) 
VALUES 
	(1, 1, 400, 1), (2, 1, 900.0, 1),
	(3, 2, 400, 0), (2, 2, 900, 1),
	(2, 3, 700, 1),
	(2, 4, 700, 1);
