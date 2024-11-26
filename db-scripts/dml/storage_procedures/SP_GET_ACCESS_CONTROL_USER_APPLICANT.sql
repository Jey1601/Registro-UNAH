--DROP PROCEDURE SP_GET_ACCESS_CONTROL_USER_APPLICANT;
DELIMITER $$
CREATE PROCEDURE SP_GET_ACCESS_CONTROL_USER_APPLICANT()
BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN 
        Roles 
        ON Roles.id_role = AccessControlRoles.id_role
    WHERE 
        Roles.id_role = 7;
END $$
DELIMITER ;

CALL SP_GET_ACCESS_CONTROL_USER_APPLICANT();