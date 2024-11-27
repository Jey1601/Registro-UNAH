DELIMITER $$

CREATE PROCEDURE ACTIVE_REGISTRATION_RATING(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_admission_process = idAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_REGISTRATION_RATING(IN idRegistrationRatingAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_REGISTRATION_RATING(IN idRegistrationRatingAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1;
END$$

DELIMITER ;