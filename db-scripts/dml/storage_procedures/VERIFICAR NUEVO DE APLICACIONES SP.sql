DELIMITER $$

CREATE PROCEDURE GET_APPLICATIONS_WITH_MULTIPLE_RATINGS(IN applicant_id VARCHAR(20),OUT application_count INT)
BEGIN
    SELECT 
        COUNT(DISTINCT app.id_admission_application_number) AS total_applications_with_multiple_ratings
    INTO 
        application_count
    FROM 
        Applications app
    JOIN 
        RatingApplicantsTest rat ON app.id_admission_application_number = rat.id_admission_application_number
    WHERE 
        app.id_applicant = applicant_id
    GROUP BY 
        app.id_applicant
    HAVING 
        COUNT(rat.id_rating_applicant_test) > 1;
END$$

DELIMITER ;
