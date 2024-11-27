DELIMITER $$

CREATE PROCEDURE ACTIVE_SENDING_NOTIFICATIONS(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$

CREATE PROCEDURE START_DATE_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE START_TIME_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT star_timeof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$


CREATE PROCEDURE END_TIME_SENDING_NOTIFICATIONS(IN idSendingNotificationsAdmissionProcess INT)
BEGIN
   	SELECT end_timeof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1;
END$$

DELIMITER ;