--DROP PROCEDURE SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID;
DELIMITER $$
CREATE PROCEDURE SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID(IN idUser INT)
BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN
        RolesUsersAdmissionsAdministrator
        ON AccessControlRoles.id_role = RolesUsersAdmissionsAdministrator.id_role_admissions_administrator
    INNER JOIN 
        UsersAdmissionsAdministrator 
        ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
    WHERE 
        UsersAdmissionsAdministrator.id_user_admissions_administrator = idUser;
END $$
DELIMITER ;

CALL SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID(2);