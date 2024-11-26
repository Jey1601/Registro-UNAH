INSERT INTO Roles (role, description_role, status_role) 
VALUES 
	('Strategic Manager Admissions Process', 'Visualizar estadisticas del proceso de admisión.', TRUE),
	('Admissions Application Verification Assistant', 'Verificar la información personal y de solicitud de los aspirantes.', TRUE),
	('Applicant Support Assistant', 'Visualiza, busca y edita la información de los aspirantes.', TRUE),
	('Admissions Application Download Assistant', 'Descarga la información de las aplicaciones el proceso admisión.', TRUE),
	('Admissions Grade Entry Assistant', 'Carga de las notas de los exámenes de admisión de los solicitantes.', TRUE),
	('Admissions Admitted Applicants Download Assistant', 'Descarga la información de los aspirantes adminitos en el proceso admisión.', TRUE);
	
INSERT INTO AccessControl (id_access_control, description_access_control, status_access_control)
VALUES
	('kNLbH8EI', 'Administrador Admisiones admissions-admin.html', TRUE),
	('P3pwBDfx', 'Administrador Admisiones see-inscriptions.html', TRUE),
	('Fz1YeRgv', 'Administrador Admisiones upload-grades.html', TRUE);

INSERT INTO AccessControlRoles (id_role, id_access_control, status_access_control_roles)
VALUES 
	(5, 'Fz1YeRgv', TRUE);

INSERT INTO RolesUsersAdmissionsAdministrator (id_user_admissions_administrator, id_role_admissions_administrator, status_role_admissions_administrator, id_regional_center)
VALUES 
	(2, 5, TRUE, 1);