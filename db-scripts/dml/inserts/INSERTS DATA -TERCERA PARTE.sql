
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
	('Prospective Student', 'Completar el proceso de inscripción y seleccionar la carrera deseada.', TRUE);



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
    ('V3yWAxgH', 'Administrador Facultad Creación de gestion de docentes, y establecimiento de fechas de planificación y cancelcación professors.html', TRUE);

INSERT INTO AccessControlRoles (id_role, id_access_control, status_access_control_roles)
VALUES 
    (2, 'lwx50K7f', TRUE),
    (3, 'IeMfti20', TRUE),
    (4, 'rllHaveq', TRUE),
    (5, 'Fz1YeRgv', TRUE),
    (6, 'pFw9dYOw', TRUE),
    (12, '78mD0SYa', TRUE),
    (12, 'dJPR7ohs', TRUE),
    (8, 'V3yWAxgH', TRUE);


INSERT INTO RolesUsersAdmissionsAdministrator (id_user_admissions_administrator, id_role_admissions_administrator, status_role_admissions_administrator, id_regional_center)
VALUES 
    (2, 2, TRUE, 1),
    (7, 2, TRUE, 1),
    (8, 2, TRUE, 1);
    (3, 3, TRUE, 1),
    (4, 4, TRUE, 1),
    (5, 5, TRUE, 1),
    (6, 6, TRUE, 1);

INSERT INTO RolesUsersFacultiesAdministrator (id_user_faculties_administrator, id_role_faculties_administrator, status_role_faculties_administrator)
VALUES
    (3, 8, TRUE)