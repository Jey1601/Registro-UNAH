DELIMITER $$

CREATE PROCEDURE ACTIVE_ADMISSION_PROCESS(IN Year INT)
BEGIN
   	SELECT id_admission_process FROM AdmissionProcess WHERE current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year ;
END$$


CREATE PROCEDURE START_DATE_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT start_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$

CREATE PROCEDURE END_DATE_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT end_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$


CREATE PROCEDURE NAME_ADMISSION_PROCESS(IN IdAdmissionProcess INT, IN Year INT )
BEGIN
   	SELECT name_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year;
END$$

DELIMITER ;