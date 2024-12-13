USE unah_registration;
-- @author  Kenia Romero 20171003359 @created 10/12/2024
/*
ESTAS TABLAS PERMITEN EL MANEJO DE CALIFICACIONES
(PREVIAMENTE SE DEBEN EXISTIR DATOS RELACIONADOS A UN ESTUDIANTE MATRICULADO)
LA INSERCIÓN DE REGISTROS DEBE RESPETARSE EN EL ORDEN DE ESTE DOCUMENTO DEBIDO A LAS DEPENDENCIAS EN LAS TABLAS
*/

-- DEFINE LOS AÑOS ACADEMICOS ACTIVOS 
-- name_academic_year NOMBRE DEL AÑO ACADÉMICO
-- status_academic_year DESCRIBE SI EL AÑO ESTÁ ACTIVO O NO (TRUE/FALSE (1,0)) 
INSERT INTO AcademicYear (name_academic_year, status_academic_year)
VALUES ('Rutilia Calderón Padilla', TRUE);

-- CONFIGURA LAS FECHAS CLAVE PARA LA PERIODICIDAD ACADÉMICA
-- description_academic_periodicity DESCRIPCIÓN DEL TIPO DE PERIODO ACADÉMICO
-- numberof_months_academic_periodicity NÚMERO DE MESES QUE EL PERIODO TIENE DE DURACIÓN
-- status_academic_periodicity DESCRIBE SI EL PERIODO ESTÁ ACTIVO O NO (TRUE/FALSE (1,0)) 
INSERT INTO AcademicPeriodicity (description_academic_periodicity, numberof_months_academic_periodicity, status_academic_periodicity) 
VALUES 
('Semestral', 6, TRUE),
('Trimestral', 4, TRUE);

-- DEFINE FECHAS RELACIONADAS CON EL REGISTRO DE CALIFICACIONES
-- id_academic_periodicity ES UNA LLAVE FORANEA QUE HACE REFERENCIA A LA TABLA AcademicPeriodicity
-- id_academic_year ES UNA LLAVE FORANEA QUE HACE REFERENCIA A LA TABLA AcademicYear
-- start_dateof_academic_periodicity, end_dateof_academic_periodicity TIEMPO DE DURACIÓN (EN MESES) DEL PERIODO ACADÉMICO
-- start_dateof_class_enrollment, end_dateof_class_enrollment TIEMPO DE DURACIÓN DEL PROCESO DE MATRÍCULA DE CLASES
-- start_dateof_exam_retake_students, end_dateof_exam_retake_students TIEMPO DE DURACIÓN DEL PROCESO PARA RETOMAR EL EXAMEN DE ADMISIÓN
-- start_datefor_recording_grades, end_datefor_recording_grades TIEMPO DE DURACIÓN DEL PROCESO PARA REGISTRAR CALIFICACIONES
-- description_dates_academic_periodicity_year DESCRIPCIÓN DEL PERIODO ACADÉMICO
-- status_dates_academic_periodicity_year DESCRIBE SI EL PERIODO ESTÁ ACTIVO O NO (TRUE/FALSE (1,0)) 
INSERT INTO DatesAcademicPeriodicityYear (
    id_academic_periodicity, id_academic_year, 
    start_dateof_academic_periodicity, end_dateof_academic_periodicity, 
    start_dateof_class_enrollment, end_dateof_class_enrollment, 
    start_dateof_classes, end_dateof_classes, 
    start_dateof_exam_retake_students, end_dateof_exam_retake_students, 
    start_datefor_recording_grades, end_datefor_recording_grades, 
    description_dates_academic_periodicity_year, status_dates_academic_periodicity_year) 
VALUES 
(2, 2024, '2024-01-17', '2024-05-10', '2024-01-18', '2024-01-21', '2024-01-22', '2024-05-03', '2024-04-23', '2024-05-07', '2024-05-06', '2024-05-08', 'Primer Periodo académico', FALSE), 
(2, 2024, '2024-09-09', '2024-12-20', '2024-09-07', '2024-09-10', '2024-09-11', '2024-12-13', '2024-11-29', '2024-12-17', '2024-12-16', '2024-12-18', 'Tercer Periodo académico', TRUE);


-- PERMITE EL REGISTRO DE CALIFICACIONES POR CLASE
-- DEBEN EXISTIR DATOS EN StudentClassStatus QUE PERMITE EL REGISTRO DE LAS CLASES QUE EL ESTUDIANTE YA CURSÓ
INSERT INTO SpecificationClassStatus (id_student_class_status, id_class_section, specification_class_status, grade_class_student)
VALUES
('20240003', 1, 'APROBADO', 87.5);

-- ALMACENA PROMEDIOS GLOBALES, ANUALES Y DE PERIODO DE LOS ESTUDIANTES
INSERT INTO StudentGradesAverages (id_student, global_grade_average_student, period_grade_average_student, annual_academic_grade_average_student)
VALUES
('20240004', 92.3, 91.8, 92.0),
('20240003', 78.4, 80.1, 79.2);

-- EN EL EJEMPLO SE INSERTAN EVALUACIONES PARA UN DOCENTE1 POR UN ESTUDIANTE1 EN LA CLASE1
INSERT INTO EvaluationOfProfessors (
    id_student, id_class_section, id_professor,
    first_performance_indicator, second_performance_indicator, third_performance_indicator,
    fourth_performance_indicator, fifth_performance_indicator, sixth_performance_indicator,
    seventh_performance_indicator, eighth_performance_indicator, ninth_performance_indicator,
    tenth_performance_indicator, eleventh_performance_indicator, twelfth_performance_indicator,
    thirteenth_performance_indicator, fourteenth_performance_indicator, fifteenth_performance_indicator,
    sixteenth_performance_indicator, seventeenth_performance_indicator, eighteenth_performance_indicator,
    nineteenth_performance_indicator, twentieth_performance_indicator, twenty_first_performance_indicator,
    twenty_second_performance_indicator, twenty_third_performance_indicator, twenty_fourth_performance_indicator,
    twenty_fifth_performance_indicator, twenty_sixth_performance_indicator, twenty_seventh_performance_indicator,
    twenty_eighth_performance_indicator
) VALUES (
    '20221000001', 1, 1, -- id_student, id_class_section, id_professor
    4, 5, 3, 4, 5, 4, 3, 5, 4, 4, 3, 5, 4, 5, 4, 4, 3, 5, 4, 4, 5, 4, 3, 5, 4, 5, 4, 5 -- Indicadores
);

