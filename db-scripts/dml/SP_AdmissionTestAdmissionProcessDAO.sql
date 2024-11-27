
DELIMITER $$

CREATE PROCEDURE ACTIVE_ADMISSION_TEST(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_admission_test_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1;
END$$

CREATE PROCEDURE DATE_ADMISSION_TEST(IN idAdmissionTestAdmissionProcess INT)
BEGIN
   	SELECT dateof_admission_test_admission_process FROM AdmissionTestAdmissionProcess WHERE id_admission_test_admission_process = idAdmissionTestAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1;
END$$

DELIMITER ;