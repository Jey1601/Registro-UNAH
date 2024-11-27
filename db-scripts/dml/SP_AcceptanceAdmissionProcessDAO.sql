DELIMITER $$

CREATE PROCEDURE ACTIVE_ACCEPTANCE(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_acceptance_admission_process FROM AcceptanceAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$



CREATE PROCEDURE START_DATE_ACCEPTANCE(IN idAcceptanceAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_ACCEPTANCE(IN idAcceptanceAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1;
END$$

DELIMITER ;