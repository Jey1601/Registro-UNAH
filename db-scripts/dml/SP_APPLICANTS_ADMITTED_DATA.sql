USE unah_registration;
DELIMITER $$
	CREATE PROCEDURE SP_APPLICANTS_ADMITTED_DATA()
	BEGIN
		SELECT CONCAT(COALESCE(first_name_applicant, ''), ' ',COALESCE(second_name_applicant, ''), ' ',COALESCE(third_name_applicant, ''), ' ',COALESCE(first_lastname_applicant, ''), ' ',COALESCE(second_lastname_applicant, '')) AS nombre_completo_apirante_admitido, ApplicantAcceptance.id_applicant,address_applicant, email_applicant,  intended_undergraduate_applicant, idregional_center 
		FROM Applicants
			INNER JOIN ApplicantAcceptance ON Applicants.id_applicant = ApplicantAcceptance.id_applicant
			INNER JOIN NotificationsApplicationsResolution ON NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant = ApplicantAcceptance.id_notification_application_resolution
			INNER JOIN ResolutionIntendedUndergraduateApplicant ON ResolutionIntendedUndergraduateApplicant.id_resolution_intended_undergraduate_applicant = NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant
			INNER JOIN Applications ON Applications.id_applicant = ApplicantAcceptance.id_applicant
 		WHERE status_applicant_acceptance = 0 AND applicant_acceptance = 1 AND ResolutionIntendedUndergraduateApplicant.resolution_intended = 1;
	END$$		
DELIMITER ;