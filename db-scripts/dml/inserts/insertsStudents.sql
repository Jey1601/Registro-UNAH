USE unah_registration;

-- ESTUDIANTE SIN CLASES MATRICULADAS
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240001', 'estudiante1@unah.hn', '080119900001', 'Juan', 'Carlos', 'Gómez', 'López', 'Tegucigalpa, Honduras', 'juan.gomez@email.com', '9999-0001', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (1, '20240001', 'password1', TRUE);

-- ESTUDIANTE CON CLASES Y SIN HISTORIAL ACADEMICO
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240002', 'estudiante2@unah.hn', '080119900002', 'María', 'Fernanda', 'Hernández', 'Mejía', 'San Pedro Sula, Honduras', 'maria.hernandez@email.com', '9999-0002', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (2, '20240002', 'password2', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (1, '20240002', 1, TRUE); 

-- ESTUDIANTE CON HISTORIAL DE CLASES, AUN NO CERCANO A GRADUARSE
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240003', 'estudiante3@unah.edu.hn', '080119900003', 'Pedro', 'Antonio', 'Martínez', 'Zelaya', 'Comayagua, Honduras', 'pedro.martinez@email.com', '9999-0003', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (3, '20240003', 'password3', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (2, '20240003', 1, TRUE);

INSERT INTO StudentClassStatus (id_student_class_status, id_student, id_class, class_status)
VALUES 
(3, '20240003', 1, TRUE), 
(4, '20240003', 2, TRUE), 
(5, '20240003', 3, TRUE); 

-- ESTUDIANTE CON UN 90% DE CLASES APROBADAS
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240004', 'estudiante4@unah.edu.hn', '080119900004', 'Ana', 'Isabel', 'Ramírez', 'Santos', 'La Ceiba, Honduras', 'ana.ramirez@email.com', '9999-0004', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (4, '20240004', 'password4', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (3, '20240004', 1, TRUE);

INSERT INTO StudentClassStatus (id_student_class_status, id_student, id_class, class_status)
VALUES 
(6, '20240004', 1, TRUE), 
(7, '20240004', 2, TRUE), 
(8, '20240004', 3, TRUE),
(9, '20240004', 4, TRUE), 
(10, '20240004', 5, TRUE),
(11, '20240004', 6, TRUE),
(12, '20240004', 7, TRUE),
(13, '20240004', 8, TRUE), 
(14, '20240004', 9, TRUE);

-- SECCIONES PARA MATRICULAR ALUMNOS
INSERT INTO ClassSections (id_class, id_dates_academic_periodicity_year, id_classroom_class_section, id_academic_schedules, id_professor_class_section, numberof_spots_available_class_section, status_class_section)
VALUES
(5, 2, 27, 4, 3, 5, TRUE),
(6, 2, 29, 6, 9, 10, TRUE),
(10, 2, 30, 5, 1, 15, TRUE);

INSERT INTO ClassSectionsDays (id_class_section, id_day, status_class_sections_days)
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
(2, 'Viernes', TRUE),
(3, 'Lunes', TRUE),
(3, 'Martes', TRUE),
(3, 'Miercoles', TRUE),
(3, 'Jueves', TRUE),
(3, 'Viernes', TRUE);

-- ESTUDIANTES MATRICULADOS EN CLASE
INSERT INTO EnrollmentClassSections (id_student, id_class_section, status_enrollment_class_sections)
VALUES
('20240002', '1', TRUE),
('20240004', '3', TRUE),
('20240003', '2', TRUE);




