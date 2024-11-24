--DROP PROCEDURE SP_GET_ACCESS_CONTROL_USERNAME_ADMISSION_ADMIN;

DELIMITER $$
CREATE PROCEDURE SP_GET_ACCESS_CONTROL_USERNAME_ADMISSION_ADMIN(IN idUser INT)
BEGIN
    SELECT 
        UsersAdmissionsAdministrator.username_user_admissions_administrator,  
        AccessControl.id_access_control 
    FROM 
        UsersAdmissionsAdministrator
    INNER JOIN 
        RolesUsersAdmissionsAdministrator 
        ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
    INNER JOIN 
        AccessControlRoles 
        ON AccessControlRoles.id_role = RolesUsersAdmissionsAdministrator.id_role_admissions_administrator
    INNER JOIN 
        AccessControl 
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control 
    WHERE 
        UsersAdmissionsAdministrator.id_user_admissions_administrator = idUser;
END $$
DELIMITER ;

CALL SP_GET_ACCESS_CONTROL_USERNAME_ADMISSION_ADMIN(2);
