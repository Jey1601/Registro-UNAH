USE unah_registration;


	UPDATE RatingApplicantsTest SET rating_applicant = 400, status_rating_applicant_test = TRUE WHERE id_rating_applicant_test=1;
	UPDATE RatingApplicantsTest SET rating_applicant = 750, status_rating_applicant_test = TRUE WHERE id_rating_applicant_test=2;
	UPDATE RatingApplicantsTest SET rating_applicant = 899, status_rating_applicant_test = TRUE WHERE id_rating_applicant_test=3;
	UPDATE RatingApplicantsTest SET rating_applicant = 901, status_rating_applicant_test = TRUE WHERE id_rating_applicant_test=4;
	UPDATE RatingApplicantsTest SET rating_applicant = 999, status_rating_applicant_test = TRUE WHERE id_rating_applicant_test=5;

INSERT INTO ResolutionIntendedUndergraduateApplicant (id_admission_application_number,intended_undergraduate_applicant,resolution_intended,status_resolution_intended_undergraduate_applicant) 
VALUES
	(1001, 1, FALSE, TRUE),
	(1001, 3, TRUE, TRUE),
	(1002, 2, FALSE, TRUE),
	(1002, 4, TRUE, TRUE),
	(1003, 2, FALSE, TRUE),
	(1003, 4, FALSE, TRUE),
	(1004, 2, FALSE, TRUE),
	(1004, 3, TRUE, TRUE);

INSERT INTO NotificationsApplicationsResolution (id_resolution_intended_undergraduate_applicant,email_sent_application_resolution,date_email_sent_application_resolution) 
VALUES
	(1, TRUE, '2024-11-20'),
	(2, TRUE, '2024-11-20'),
	(3, TRUE, '2024-11-20'),
	(4, TRUE, '2024-11-20'),
	(5, TRUE, '2024-11-20'),
	(6, TRUE, '2024-11-20'),
	(7, TRUE, '2024-11-20'),
	(8, TRUE, '2024-11-20');

INSERT INTO ApplicantAcceptance (id_notification_application_resolution,id_applicant,date_applicant_acceptance,applicant_acceptance,status_applicant_acceptance,id_admission_process,status_admission_process) 
VALUES
	(2, '0801200001345', '2024-11-20', TRUE, FALSE, 1, TRUE),
	(4, '1015200109876', '2024-11-20', TRUE, FALSE, 1, TRUE),
	(8, '1501200309444', '2024-11-20', TRUE, FALSE, 1, TRUE);