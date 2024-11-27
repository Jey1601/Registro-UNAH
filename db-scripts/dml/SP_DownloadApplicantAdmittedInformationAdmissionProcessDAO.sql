DELIMITER $$

CREATE PROCEDURE ACTIVE_DOWNLOAD_ADMITTED(IN idAdmissionProcess INT)
BEGIN
   	SELECT id_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$


CREATE PROCEDURE START_DATE_DOWNLOAD_ADMITTED(IN idDownloadAdmitted INT)
BEGIN
   	SELECT start_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$


CREATE PROCEDURE END_DATE_DOWNLOAD_ADMITTED(IN idDownloadAdmitted INT)
BEGIN
   	SELECT end_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1;
END$$

DELIMITER ;