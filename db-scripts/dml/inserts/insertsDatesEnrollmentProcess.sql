USE unah_registration;

INSERT INTO TypesEnrollmentConditions (
    maximum_student_global_average, 
    minimum_student_global_average, 
    status_student_global_average, 
    maximum_student_period_average, 
    minimum_student_period_average, 
    status_type_enrollment_conditions
)
VALUES
(99.99, 80.00, TRUE, 99.99, 90.00, TRUE),   -- Condición 1: Matrícula para estudiantes con alto rendimiento
(79.99, 75.00, TRUE, 99.99, 89.00, TRUE),   -- Condición 2: Matrícula general para buenos promedios
(74.99, 70.00, TRUE, 79.99, 70.00, TRUE),   -- Condición 3: Matrícula con promedio regular
(69.99, 60.00, FALSE, 69.99, 60.00, TRUE),  -- Condición 4: Matrícula restringida (estado FALSE)
(59.99, 50.00, FALSE, 59.99, 50.00, FALSE); -- Condición 5: Matrícula especial o condicionada

INSERT INTO EnrollmentProcess (id_dates_academic_periodicity_year, status_enrollment_process)
VALUES
(2, TRUE); 

INSERT INTO DatesEnrollmentProcess (
    id_enrollment_process, 
    id_type_enrollment_conditions, 
    day_available_enrollment_process, 
    start_time_available_enrollment_process, 
    end_time_available_enrollment_process, 
    status_date_enrollment_process
)
VALUES
(1, 1, '2024-01-17', '09:00:00', '23:59:00', TRUE),
(1, 5, '2024-01-27', '09:00:00', '23:59:00', TRUE),
(1, 2, '2024-01-18', '09:00:00', '23:59:00', TRUE), 
(1, 4, '2024-01-19', '09:00:00', '23:59:00', TRUE),
(1, 3, '2024-01-20', '09:00:00', '23:59:00', TRUE);