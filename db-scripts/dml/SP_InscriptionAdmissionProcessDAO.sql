DELIMITER $$

CREATE PROCEDURE ACTIVE_INSCRIPTION_PROCESS(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_inscription_admission_process = 0 AND status_inscription_admission_processs =1;
END$$

CREATE PROCEDURE START_DATE_INSCRIPTION_PROCESS(IN idInscriptionAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess;
END$$

CREATE PROCEDURE END_DATE_INSCRIPTION_PROCESS(IN idInscriptionAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess;
END$$

DELIMITER ;