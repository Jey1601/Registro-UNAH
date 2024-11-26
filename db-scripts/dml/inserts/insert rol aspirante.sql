INSERT INTO Roles (role, description_role, status_role) VALUES ("Applicant", "Seleccion de carrera", 1);

INSERT INTO `AccessControl` (id_access_control, description_access_control, status_access_control) VALUES ("MnkUaWui", "Aspirante results.html", 1);
INSERT INTO `AccessControl` (id_access_control, description_access_control, status_access_control) VALUES ("2nzVZZD9", "Aspirante login.html", 1);

INSERT INTO `AccessControlRoles` (id_role, id_access_control, status_access_control_roles) VALUES (7, "MnkUaWui", 1);
INSERT INTO `AccessControlRoles` (id_role, id_access_control, status_access_control_roles) VALUES (7, "2nzVZZD9", 1);

