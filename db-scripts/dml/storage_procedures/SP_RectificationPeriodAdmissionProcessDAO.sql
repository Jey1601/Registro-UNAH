DELIMITER $$

CREATE PROCEDURE ACTIVE_RECTIFICATION_PERIOD(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_RECTIFICATION_PERIOD(IN idRectificationPeriodAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_RECTIFICATION_PERIOD(IN idRectificationPeriodAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1;
END$$

DELIMITER ;