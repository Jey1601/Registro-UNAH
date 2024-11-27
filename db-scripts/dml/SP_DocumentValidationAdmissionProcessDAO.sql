DELIMITER $$

CREATE PROCEDURE ACTIVE_DOCUMENT_VALIDATION(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_DOCUMENT_VALIDATION(IN idDocumentValidationAdmissionProcess INT)
BEGIN
   	SELECT start_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_DOCUMENT_VALIDATION(IN idDocumentValidationAdmissionProcess INT)
BEGIN
   	SELECT end_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1;
END$$

DELIMITER ;

