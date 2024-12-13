USE unah_registration;
-- @author  Kenia Romero 20171003359 @created 10/12/2024
/*
ESTAS TABLAS PERMITEN ORGANIZAR Y GESTIONAR LOS CALENDARIOS ACADÉMICOS Y PERIODOS
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

-- DEFINE EL PROCESO Y NOTIFICACIÓN PARA EL REGISTRO DE ESTUDIANTES
-- CONFIGURACIÓN DE LA PERIODICIDAD ACADÉMICA (MENSUAL, SEMESTRAL, ANUAL, ETC.)
INSERT INTO StudentRegistrationProcess (
    start_dateof_creation_students_registration_process,
    end_dateof_creation_students_registration_process,
    start_date_notification_to_students_registration_process,
    end_dateof_notification_students_registration_process,
    timeof_sending_notifications_students_registration_process,
    current_status_students_registration_process,
    status_students_registration_process
) VALUES (
    '2024-01-15', '2024-01-20', -- Creación de registros
    '2024-01-21', '2024-01-23', -- Notificaciones
    '08:00:00', -- Hora de envío de notificaciones
    1, 1 -- Estado activo
);
