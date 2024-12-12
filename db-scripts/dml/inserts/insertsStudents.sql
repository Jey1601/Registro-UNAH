USE unah_registration;

INSERT INTO TypesEnrollmentConditions (maximum_student_global_average, minimum_student_global_average, status_student_global_average, 
    maximum_student_period_average, minimum_student_period_average, status_type_enrollment_conditions)
VALUES
(99.99, 80.00, TRUE, 99.99, 90.00, TRUE),   -- Condición 1: Matrícula para estudiantes con alto rendimiento
(79.99, 75.00, TRUE, 99.99, 89.00, TRUE),   -- Condición 2: Matrícula general para buenos promedios
(74.99, 70.00, TRUE, 79.99, 70.00, TRUE),   -- Condición 3: Matrícula con promedio regular
(69.99, 60.00, FALSE, 69.99, 60.00, TRUE),  -- Condición 4: Matrícula restringida (estado FALSE)
(59.99, 50.00, FALSE, 59.99, 50.00, FALSE); -- Condición 5: Matrícula especial o condicionada

INSERT INTO EnrollmentProcess (id_dates_academic_periodicity_year, status_enrollment_process)
VALUES
(2, TRUE); 

INSERT INTO DatesEnrollmentProcess (id_enrollment_process, id_type_enrollment_conditions, day_available_enrollment_process, 
    start_time_available_enrollment_process, end_time_available_enrollment_process, status_date_enrollment_process)
VALUES
(1, 1, '2024-01-17', '09:00:00', '23:59:00', TRUE),
(1, 5, '2024-01-27', '09:00:00', '23:59:00', TRUE),
(1, 2, '2024-01-18', '09:00:00', '23:59:00', TRUE), 
(1, 4, '2024-01-19', '09:00:00', '23:59:00', TRUE),
(1, 3, '2024-01-20', '09:00:00', '23:59:00', TRUE);

-- ESTUDIANTE1 SIN CLASES MATRICULADAS
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240001', 'estudiante1@unah.hn', '080119900001', 'Juan', 'Carlos', 'Gómez', 'López', 'Tegucigalpa, Honduras', 'juan.gomez@email.com', '9999-0001', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (1, '20240001', 'password1', TRUE);

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

-- ESTUDIANTE2 CON CLASES Y SIN HISTORIAL ACADEMICO
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240002', 'estudiante2@unah.hn', '080119900002', 'María', 'Fernanda', 'Hernández', 'Mejía', 'San Pedro Sula, Honduras', 'maria.hernandez@email.com', '9999-0002', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (2, '20240002', 'password2', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (1, '20240002', 1, TRUE); 

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
-- ESTUDIANTE3 CON HISTORIAL DE CLASES, AUN NO CERCANO A GRADUARSE
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240003', 'estudiante3@unah.edu.hn', '080119900003', 'Pedro', 'Antonio', 'Martínez', 'Zelaya', 'Comayagua, Honduras', 'pedro.martinez@email.com', '9999-0003', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (3, '20240003', 'password3', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (2, '20240003', 1, TRUE);

INSERT INTO StudentClassStatus (id_student, id_class, class_status)
VALUES 
('20240003', 1, FALSE), 
('20240003', 2, FALSE), 
('20240003', 3, FALSE); 

-- SECCIONES DE CLASES REGISTRADAS PARA EL ESTUDIANTE3
INSERT INTO ClassSections (id_class, id_dates_academic_periodicity_year, id_classroom_class_section, id_academic_schedules, id_professor_class_section, numberof_spots_available_class_section, status_class_section)
VALUES
(1, 2, 27, 4, 1, 5, FALSE),
(2, 2, 29, 6, 2, 10, FALSE),
(3, 2, 30, 5, 3, 15, FALSE);

INSERT INTO ClassSectionsProfessor (id_class_section, class_presentation_video, status_class_section_professor)
VALUES
(1,'https://youtube1.com', FALSE),
(2,'https://youtube2.com', FALSE),
(3,'https://youtube3.com', FALSE);

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

INSERT INTO SpecificationClassStatus (id_student_class_status, id_class_section, specification_class_status, grade_class_student)
VALUES
('20240003', 1, 'APROBADO', 87.5),
('20240003', 2, 'REPROBADO', 59.3),
('20240003', 3, 'APROBADO', 92.8);

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
-- ESTUDIANTE4 CON UN 90% DE CLASES APROBADAS
INSERT INTO Students (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student)
VALUES ('20240004', 'estudiante4@unah.edu.hn', '080119900004', 'Ana', 'Isabel', 'Ramírez', 'Santos', 'La Ceiba, Honduras', 'ana.ramirez@email.com', '9999-0004', TRUE);

INSERT INTO UsersStudents (id_user_student, username_user_student, password_user_student, status_user_student)
VALUES (4, '20240004', 'password4', TRUE);

INSERT INTO StudentsUndergraduates (id_student_undergraduate, id_student, id_undergraduate, status_student_undergraduate)
VALUES (3, '20240004', 1, TRUE);

INSERT INTO StudentClassStatus (id_student, id_class, class_status)
VALUES 
('20240004', 1, FALSE), 
('20240004', 2, FALSE), 
('20240004', 3, FALSE),
('20240004', 4, FALSE), 
('20240004', 5, FALSE),
('20240004', 6, FALSE),
('20240004', 7, FALSE),
('20240004', 8, FALSE), 
('20240004', 9, FALSE);

-- SECCIONES DE CLASES REGISTRADAS PARA EL ESTUDIANTE4
INSERT INTO ClassSections (id_class, id_dates_academic_periodicity_year, id_classroom_class_section, id_academic_schedules, id_professor_class_section, numberof_spots_available_class_section, status_class_section)
VALUES
(1, 2, 27, 4, 1, 5, FALSE),
(2, 2, 28, 5, 2, 10, FALSE),
(3, 2, 29, 6, 3, 15, FALSE),
(4, 2, 30, 7, 4, 10, FALSE),
(5, 2, 31, 8, 5, 15, FALSE),
(6, 2, 32, 9, 6, 10, FALSE),
(7, 2, 33, 10, 7, 15, FALSE),
(8, 2, 34, 11, 8, 15, FALSE),
(9, 2, 35, 12, 9, 15, FALSE);

INSERT INTO ClassSectionsProfessor (id_class_section, class_presentation_video, status_class_section_professor)
VALUES
(4,'https://youtube1.com', FALSE),
(5,'https://youtube1.com', FALSE),
(6,'https://youtube1.com', FALSE),
(7,'https://youtube1.com', FALSE),
(8,'https://youtube1.com', FALSE),
(9,'https://youtube1.com', FALSE),
(10,'https://youtube1.com', FALSE),
(11,'https://youtube1.com', FALSE),
(12,'https://youtube1.com', FALSE);

INSERT INTO ClassSectionsDays (id_class_section, id_day, status_class_sections_days)
VALUES
(4, 'Lunes', FALSE),
(4, 'Martes', FALSE),
(4, 'Miercoles', FALSE),
(5, 'Jueves', FALSE),
(5, 'Viernes', FALSE),
(6, 'Lunes', FALSE),
(7, 'Martes', FALSE),
(7, 'Miercoles', FALSE),
(7, 'Jueves', FALSE),
(7, 'Viernes', FALSE),
(8, 'Lunes', FALSE),
(8, 'Martes', FALSE),
(8, 'Miercoles', FALSE),
(8, 'Jueves', FALSE),
(8, 'Viernes', FALSE),
(9, 'Lunes', FALSE),
(9, 'Martes', FALSE),
(9, 'Miercoles', FALSE),
(9, 'Jueves', FALSE),
(9, 'Viernes', FALSE),
(10, 'Lunes', FALSE),
(10, 'Martes', FALSE),
(10, 'Miercoles', FALSE),
(10, 'Jueves', FALSE),
(10, 'Viernes', FALSE),
(11, 'Lunes', FALSE),
(12, 'Lunes', FALSE),
(12, 'Martes', FALSE),
(12, 'Miercoles', FALSE),
(12, 'Jueves', FALSE),
(12, 'Viernes', FALSE);

INSERT INTO SpecificationClassStatus (id_student_class_status, id_class_section, specification_class_status, grade_class_student)
VALUES
('20240004', 4, 'APROBADO', 87.5),
('20240004', 5, 'APROBADO', 87.3),
('20240004', 6, 'APROBADO', 92.8),
('20240004', 7, 'APROBADO', 87.5),
('20240004', 8, 'APROBADO', 92.3),
('20240004', 9, 'APROBADO', 92.8),
('20240004', 10, 'APROBADO', 87.5),
('20240004', 11, 'APROBADO', 92.3),
('20240004', 12, 'APROBADO', 92.8);

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
-- SECCIONES PARA MATRICULAR ALUMNOS
INSERT INTO ClassSections (id_class, id_dates_academic_periodicity_year, id_classroom_class_section, id_academic_schedules, id_professor_class_section, numberof_spots_available_class_section, status_class_section)
VALUES
(5, 1, 27, 4, 10, 5, TRUE),
(6, 1, 29, 6, 10, 10, TRUE),
(10, 1, 30, 5, 10, 15, TRUE);

INSERT INTO ClassSectionsDays (id_class_section, id_day, status_class_sections_days)
VALUES
(13, 'Lunes', TRUE),
(13, 'Martes', TRUE),
(13, 'Miercoles', TRUE),
(13, 'Jueves', TRUE),
(13, 'Viernes', TRUE),
(14, 'Lunes', TRUE),
(14, 'Martes', TRUE),
(14, 'Miercoles', TRUE),
(14, 'Jueves', TRUE),
(14, 'Viernes', TRUE),
(15, 'Lunes', TRUE),
(15, 'Martes', TRUE),
(15, 'Miercoles', TRUE),
(15, 'Jueves', TRUE),
(15, 'Viernes', TRUE);

-- ESTUDIANTES MATRICULADOS EN CLASE
INSERT INTO EnrollmentClassSections (id_student, id_class_section, status_enrollment_class_sections)
VALUES
('20240002', '1', TRUE),
('20240004', '3', TRUE),
('20240003', '2', TRUE);

-- INDICE DE ESTUDIANTES 3 Y 4
INSERT INTO StudentGradesAverages (id_student, global_grade_average_student, period_grade_average_student, annual_academic_grade_average_student)
VALUES
('20240004', 92.3, 91.8, 92.0),
('20240003', 78.4, 80.1, 79.2);


select * from `Students`;



INSERT INTO EnrollmentClassSections (id_student, id_class_section, status_enrollment_class_sections)
VALUES
('20240003', '7', TRUE);