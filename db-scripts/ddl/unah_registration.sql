-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-11-2024 a las 15:49:12
-- Versión del servidor: 8.0.40-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `unah_registration`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_ACCEPTANCE` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_acceptance_admission_process FROM AcceptanceAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_ADMISSION_PROCESS` (IN `Year` INT)   BEGIN
   	SELECT id_admission_process FROM AdmissionProcess WHERE current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_ADMISSION_TEST` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_admission_test_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_DOCUMENT_VALIDATION` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_DOWNLOAD_ADMITTED` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_INSCRIPTION_PROCESS` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_admission_process = idAdmissionProcess AND current_status_inscription_admission_process = 0 AND status_inscription_admission_processs =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_RECTIFICATION_PERIOD` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_REGISTRATION_RATING` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_admission_process = idAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTIVE_SENDING_NOTIFICATIONS` (IN `idAdmissionProcess` INT)   BEGIN
   	SELECT id_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess
	WHERE id_admission_process = idAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `APPLICANT_DATA_VIEW` (IN `idApplicant` VARCHAR(20))   BEGIN

    SELECT 
        Applications.id_admission_application_number,
        Applicants.id_applicant,
         CONCAT_WS(' ',
            COALESCE(Applicants.first_name_applicant, ''),
            COALESCE(Applicants.second_name_applicant, ''),
            COALESCE(Applicants.third_name_applicant, '')
        ) AS name,
        CONCAT_WS(' ',
            COALESCE(Applicants.first_lastname_applicant, ''),
            COALESCE(Applicants.second_lastname_applicant, '')
        ) AS lastname,
        email_applicant, 
        phone_number_applicant, 
        address_applicant, 
        image_id_applicant,
        AdmissionProcess.name_admission_process,
        RegionalCenters.name_regional_center,
        first.name_undergraduate as firstC,
        second.name_undergraduate as secondC,
        Applications.secondary_certificate_applicant as certificate,
        CheckApplicantApplications.id_check_applicant_applications
    FROM Applicants
    INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
    AND `Applications`.status_application=1
    INNER JOIN AdmissionProcess ON Applications.id_admission_process = AdmissionProcess.id_admission_process
    INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
    INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
    INNER JOIN Undergraduates  second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
    INNER JOIN `CheckApplicantApplications` ON `CheckApplicantApplications`.id_admission_application_number = `Applications`.id_admission_application_number
    WHERE Applicants.id_applicant = idApplicant
    ORDER BY Applications.id_admission_application_number$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_PENDING_BY_USER_ADMINISTRADOR` (IN `userID` INT)   BEGIN
   	SELECT id_check_applicant_applications, id_applicant, id_admission_application_number FROM CheckApplicantApplications
	WHERE admissions_administrator_check_applicant_applications = userID AND revision_status_check_applicant_applications = 0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DATE_ADMISSION_TEST` (IN `idAdmissionTestAdmissionProcess` INT)   BEGIN
   	SELECT dateof_admission_test_admission_process FROM AdmissionTestAdmissionProcess WHERE id_admission_test_admission_process = idAdmissionTestAdmissionProcess AND current_status_admission_test_admission_process = 0 AND status_admission_test_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DELETE_CHECK_ERRORS_BY_APPLICANT` (IN `p_id_check_applicant_applications` INT)   BEGIN
    DELETE FROM CheckErrorsApplicantApplications
    WHERE id_check_applicant_applications = p_id_check_applicant_applications$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_ACCEPTANCE` (IN `idAcceptanceAdmissionProcess` INT)   BEGIN
   	SELECT end_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_ADMISSION_PROCESS` (IN `IdAdmissionProcess` INT, IN `Year` INT)   BEGIN
   	SELECT end_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_DOCUMENT_VALIDATION` (IN `idDocumentValidationAdmissionProcess` INT)   BEGIN
   	SELECT end_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_DOWNLOAD_ADMITTED` (IN `idDownloadAdmitted` INT)   BEGIN
   	SELECT end_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_INSCRIPTION_PROCESS` (IN `idInscriptionAdmissionProcess` INT)   BEGIN
   	SELECT end_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_RECTIFICATION_PERIOD` (IN `idRectificationPeriodAdmissionProcess` INT)   BEGIN
   	SELECT end_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `END_DATE_REGISTRATION_RATING` (IN `idRegistrationRatingAdmissionProcess` INT)   BEGIN
   	SELECT end_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUsersAdmissionsAdministratorByRol` (IN `RolAdmissionsAdministrator` INT)   BEGIN
   	SELECT UsersAdmissionsAdministrator.id_user_admissions_administrator FROM UsersAdmissionsAdministrator
	INNER JOIN RolesUsersAdmissionsAdministrator ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
	WHERE RolesUsersAdmissionsAdministrator.id_role_admissions_administrator = RolAdmissionsAdministrator AND RolesUsersAdmissionsAdministrator.status_role_admissions_administrator =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_APPLICATIONS_BY_ADMIN_PROCESS` (IN `IdAdminProcess` INT)   BEGIN
   	SELECT id_applicant,id_admission_application_number FROM Applications
	WHERE Applications.id_admission_process  =IdAdminProcess AND Applications.status_application = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CHECK_BY_IDAPPLICANT_IDAPLICATION` (IN `idApplicant` VARCHAR(20), IN `idAplication` INT)   BEGIN
   	SELECT id_check_applicant_applications  FROM CheckApplicantApplications
	WHERE id_applicant = idApplicant AND id_admission_application_number =  idAplication  AND revision_status_check_applicant_applications = 0 AND verification_status_data_applicant =0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_DATA_APPLICANT_APPLICATIONS_MAIL` (IN `idCheckApplicant` INT)   BEGIN
    SELECT CONCAT_WS(' ',
            COALESCE(Applicants.first_name_applicant, ''),
            COALESCE(Applicants.second_name_applicant, ''),
            COALESCE(Applicants.third_name_applicant, '')
        ) AS nameApplicant,
	email_applicant
	FROM Applicants
	INNER JOIN CheckApplicantApplications ON CheckApplicantApplications.id_applicant = Applicants.id_applicant$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `INSERT_CHECK_APPLICANT_APPLICATIONS` (IN `p_id_applicant` VARCHAR(20), IN `p_id_admission_application_number` INT, IN `p_verification_status_data_applicant` BOOLEAN, IN `p_date_check_applicant_applications` DATE, IN `p_revision_status_check_applicant_applications` BOOLEAN, IN `p_admissions_administrator` INT)   BEGIN
    INSERT INTO CheckApplicantApplications (
        id_applicant,
        id_admission_application_number,
        verification_status_data_applicant,
        date_check_applicant_applications,
        revision_status_check_applicant_applications,
        admissions_administrator_check_applicant_applications
    )
    VALUES (
        p_id_applicant,
        p_id_admission_application_number,
        p_verification_status_data_applicant, 
        p_date_check_applicant_applications,
        p_revision_status_check_applicant_applications,
        p_admissions_administrator
    )$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `INSERT_CHECK_ERROR` (IN `idCheckApplicant` INT, IN `wrongData` VARCHAR(100), IN `description` VARCHAR(255))   BEGIN
	INSERT INTO CheckErrorsApplicantApplications (id_check_applicant_applications, incorrect_data, description_incorrect_data) 
	VALUES (idCheckApplicant, wrongData, description)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `NAME_ADMISSION_PROCESS` (IN `IdAdmissionProcess` INT, IN `Year` INT)   BEGIN
   	SELECT name_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_APPLICANTS_ADMITTED_DATA` ()   BEGIN
		SELECT CONCAT(COALESCE(first_name_applicant, ''), ' ',COALESCE(second_name_applicant, ''), ' ',COALESCE(third_name_applicant, ''), ' ',COALESCE(first_lastname_applicant, ''), ' ',COALESCE(second_lastname_applicant, '')) AS nombre_completo_apirante_admitido, ApplicantAcceptance.id_applicant,address_applicant, email_applicant,  intended_undergraduate_applicant, idregional_center 
		FROM Applicants
			INNER JOIN ApplicantAcceptance ON Applicants.id_applicant = ApplicantAcceptance.id_applicant
			INNER JOIN NotificationsApplicationsResolution ON NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant = ApplicantAcceptance.id_notification_application_resolution
			INNER JOIN ResolutionIntendedUndergraduateApplicant ON ResolutionIntendedUndergraduateApplicant.id_resolution_intended_undergraduate_applicant = NotificationsApplicationsResolution.id_resolution_intended_undergraduate_applicant
			INNER JOIN Applications ON Applications.id_applicant = ApplicantAcceptance.id_applicant
 		WHERE status_applicant_acceptance = 0 AND applicant_acceptance = 1 AND ResolutionIntendedUndergraduateApplicant.resolution_intended = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_APPLICANTS_DATA` ()   BEGIN
   SELECT A.id_applicant, A.id_admission_application_number,E.id_type_admission_tests, E.name_type_admission_tests, C.rating_applicant,
        CONCAT(
            COALESCE(B.first_name_applicant, ''),
            ' ',
            COALESCE(B.second_name_applicant, ''),
            ' ',
            COALESCE(B.third_name_applicant, ''),
            ' ',
            COALESCE(B.first_lastname_applicant, ''),
            ' ',
            COALESCE(B.second_lastname_applicant, '')
        ) AS name, D.name_regional_center 
    FROM Applications A
    LEFT JOIN Applicants B on A.id_applicant = B.id_applicant
    LEFT JOIN RatingApplicantsTest C on A.id_admission_application_number = C.id_admission_application_number
    LEFT JOIN RegionalCenters D on A.idregional_center = D.id_regional_center
    LEFT JOIN TypesAdmissionTests E on C.id_type_admission_tests = E.id_type_admission_tests
    WHERE A.status_application=TRUE$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ASPIRANTS_DATA_VIEW` ()   BEGIN

    SELECT 
        Applications.id_admission_application_number,
        Applicants.id_applicant,
         CONCAT_WS(' ',
            COALESCE(Applicants.first_name_applicant, ''),
            COALESCE(Applicants.second_name_applicant, ''),
            COALESCE(Applicants.third_name_applicant, '')
        ) AS name,
        CONCAT_WS(' ',
            COALESCE(Applicants.first_lastname_applicant, ''),
            COALESCE(Applicants.second_lastname_applicant, '')
        ) AS lastname,
        email_applicant, 
        phone_number_applicant, 
        address_applicant, 
        image_id_applicant,
        AdmissionProcess.name_admission_process,
        RegionalCenters.name_regional_center,
        first.name_undergraduate as firstC,
        second.name_undergraduate as secondC,
        Applications.secondary_certificate_applicant as certificate,
        CheckApplicantApplications.id_check_applicant_applications
    FROM Applicants
    INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
    AND `Applications`.status_application=1
    INNER JOIN AdmissionProcess ON Applications.id_admission_process = AdmissionProcess.id_admission_process
    INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
    INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
    INNER JOIN Undergraduates  second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
    INNER JOIN `CheckApplicantApplications` ON `CheckApplicantApplications`.id_admission_application_number = `Applications`.id_admission_application_number
    ORDER BY Applications.id_admission_application_number$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID` (IN `idUser` INT)   BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN
        RolesUsersAdmissionsAdministrator
        ON AccessControlRoles.id_role = RolesUsersAdmissionsAdministrator.id_role_admissions_administrator
    INNER JOIN 
        UsersAdmissionsAdministrator 
        ON RolesUsersAdmissionsAdministrator.id_user_admissions_administrator = UsersAdmissionsAdministrator.id_user_admissions_administrator
    WHERE 
        UsersAdmissionsAdministrator.id_user_admissions_administrator = idUser$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_GET_ACCESS_CONTROL_USER_APPLICANT` ()   BEGIN
    SELECT  
        AccessControl.id_access_control 
    FROM 
        AccessControl
    INNER JOIN
        AccessControlRoles
        ON AccessControl.id_access_control = AccessControlRoles.id_access_control
    INNER JOIN 
        Roles 
        ON Roles.id_role = AccessControlRoles.id_role
    WHERE 
        Roles.id_role = 7$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_ACCEPTANCE` (IN `idAcceptanceAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_acceptance_admission_process FROM AcceptanceAdmissionProcess 
	WHERE id_acceptance_admission_process = idAcceptanceAdmissionProcess AND current_status_acceptance_admission_process = 0 AND status_acceptance_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_ADMISSION_PROCESS` (IN `IdAdmissionProcess` INT, IN `Year` INT)   BEGIN
   	SELECT start_dateof_admission_process FROM AdmissionProcess WHERE id_admission_process = IdAdmissionProcess AND current_status_admission_process = 0 AND status_admission_process =1 AND id_academic_year = Year$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_DOCUMENT_VALIDATION` (IN `idDocumentValidationAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_document_validation_admission_process FROM DocumentValidationAdmissionProcess WHERE id_document_validation_admission_process = idDocumentValidationAdmissionProcess AND current_status_document_validation_admission_process = 0 AND status_document_validation_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_DOWNLOAD_ADMITTED` (IN `idDownloadAdmitted` INT)   BEGIN
   	SELECT start_dateof_download_applicant_information_admission_process FROM DownloadApplicantAdmittedInformationAdmissionProcess 
	WHERE id_download_applicant_information_admission_process = idDownloadAdmitted AND current_status_download_applicant_information_admission_process = 0 AND status_download_applicant_information_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_INSCRIPTION_PROCESS` (IN `idInscriptionAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_inscription_admission_process FROM InscriptionAdmissionProcess WHERE id_inscription_admission_process = idInscriptionAdmissionProcess$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_RECTIFICATION_PERIOD` (IN `idRectificationPeriodAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_rectification_period_admission_process FROM RectificationPeriodAdmissionProcess 
	WHERE id_rectification_period_admission_process = idRectificationPeriodAdmissionProcess AND current_status_rectification_period_admission_process = 0 AND status_rectification_period_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_REGISTRATION_RATING` (IN `idRegistrationRatingAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_registration_rating_admission_process FROM RegistrationRatingAdmissionProcess 
	WHERE id_registration_rating_admission_process = idRegistrationRatingAdmissionProcess AND current_status_registration_rating_admission_process = 0 AND status_sending_registration_rating_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `START_DATE_SENDING_NOTIFICATIONS` (IN `idSendingNotificationsAdmissionProcess` INT)   BEGIN
   	SELECT start_dateof_sending_notifications_admission_process FROM SendingNotificationsAdmissionProcess 
	WHERE id_sending_notifications_admission_process = idSendingNotificationsAdmissionProcess AND current_status_sending_notifications_admission_process = 0 AND status_sending_notifications_admission_process =1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UPDATE_CHECK_APPLICANT_APPLICATIONS` (IN `p_id_check_applicant_applications` INT, IN `p_verification_status_data_applicant` BOOLEAN, IN `p_date_check_applicant_applications` DATE, IN `p_revision_status_check_applicant_applications` BOOLEAN, IN `p_description_general_check_applicant_applications` VARCHAR(255))   BEGIN
    UPDATE CheckApplicantApplications
    SET 
        verification_status_data_applicant = p_verification_status_data_applicant,
        date_check_applicant_applications = p_date_check_applicant_applications,
        revision_status_check_applicant_applications = p_revision_status_check_applicant_applications,
	description_general_check_applicant_applications = p_description_general_check_applicant_applications
    WHERE id_check_applicant_applications = p_id_check_applicant_applications$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USER_ADMIN_BY_USERNAME` (IN `userNameAdmin` VARCHAR(50))   BEGIN
   	SELECT id_user_admissions_administrator FROM UsersAdmissionsAdministrator
	WHERE username_user_admissions_administrator = userNameAdmin$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AcademicYear`
--

CREATE TABLE `AcademicYear` (
  `id_academic_year` int NOT NULL,
  `name_academic_year` varchar(100) NOT NULL,
  `status_academic_year` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AcademicYear`
--

INSERT INTO `AcademicYear` (`id_academic_year`, `name_academic_year`, `status_academic_year`) VALUES
(2024, 'Rutilia Calderón Padilla', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AcceptanceAdmissionProcess`
--

CREATE TABLE `AcceptanceAdmissionProcess` (
  `id_acceptance_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_acceptance_admission_process` date NOT NULL,
  `end_dateof_acceptance_admission_process` date NOT NULL,
  `current_status_acceptance_admission_process` tinyint(1) NOT NULL,
  `status_acceptance_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AcceptanceAdmissionProcess`
--

INSERT INTO `AcceptanceAdmissionProcess` (`id_acceptance_admission_process`, `id_admission_process`, `start_dateof_acceptance_admission_process`, `end_dateof_acceptance_admission_process`, `current_status_acceptance_admission_process`, `status_acceptance_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-09-01', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AccessControl`
--

CREATE TABLE `AccessControl` (
  `id_access_control` char(8) NOT NULL,
  `description_access_control` varchar(150) NOT NULL,
  `status_access_control` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AccessControl`
--

INSERT INTO `AccessControl` (`id_access_control`, `description_access_control`, `status_access_control`) VALUES
('Fz1YeRgv', 'Administrador Admisiones upload-grades.html', 1),
('IeMfti20', 'Administrador Admisiones Visualiza, busca y edita la información de los aspirantes.', 1),
('kNLbH8EI', 'Administrador Admisiones admissions-admin.html', 1),
('lwx50K7f', 'Administrador Admisiones Verificar la información personal y de solicitud de los aspirantes.', 1),
('P3pwBDfx', 'Administrador Admisiones see-inscriptions.html', 1),
('pFw9dYOw', 'Administrador Admisiones Descarga la información de los aspirantes admitidos en el proceso de admisión.', 1),
('rllHaveq', 'Administrador Admisiones Descarga la información de las aplicaciones del proceso de admisión.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AccessControlRoles`
--

CREATE TABLE `AccessControlRoles` (
  `id_role` int NOT NULL,
  `id_access_control` char(8) NOT NULL,
  `status_access_control_roles` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AccessControlRoles`
--

INSERT INTO `AccessControlRoles` (`id_role`, `id_access_control`, `status_access_control_roles`) VALUES
(2, 'lwx50K7f', 1),
(3, 'IeMfti20', 1),
(4, 'rllHaveq', 1),
(5, 'Fz1YeRgv', 1),
(6, 'pFw9dYOw', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AdmissionProcess`
--

CREATE TABLE `AdmissionProcess` (
  `id_admission_process` int NOT NULL,
  `name_admission_process` varchar(100) NOT NULL,
  `id_academic_year` int NOT NULL,
  `start_dateof_admission_process` date NOT NULL,
  `end_dateof_admission_process` date NOT NULL,
  `current_status_admission_process` tinyint(1) NOT NULL,
  `status_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AdmissionProcess`
--

INSERT INTO `AdmissionProcess` (`id_admission_process`, `name_admission_process`, `id_academic_year`, `start_dateof_admission_process`, `end_dateof_admission_process`, `current_status_admission_process`, `status_admission_process`) VALUES
(1, 'Proceso de Admisión 2024', 2024, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AdmissionTestAdmissionProcess`
--

CREATE TABLE `AdmissionTestAdmissionProcess` (
  `id_admission_test_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `dateof_admission_test_admission_process` date NOT NULL,
  `current_status_admission_test_admission_process` tinyint(1) NOT NULL,
  `status_admission_test_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AdmissionTestAdmissionProcess`
--

INSERT INTO `AdmissionTestAdmissionProcess` (`id_admission_test_admission_process`, `id_admission_process`, `dateof_admission_test_admission_process`, `current_status_admission_test_admission_process`, `status_admission_test_admission_process`) VALUES
(1, 1, '2024-01-01', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ApplicantAcceptance`
--

CREATE TABLE `ApplicantAcceptance` (
  `id_applicant_acceptance` int NOT NULL,
  `id_notification_application_resolution` int NOT NULL,
  `id_applicant` varchar(20) NOT NULL,
  `date_applicant_acceptance` date NOT NULL,
  `applicant_acceptance` tinyint(1) NOT NULL,
  `status_applicant_acceptance` tinyint(1) NOT NULL,
  `id_admission_process` int NOT NULL,
  `status_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Applicants`
--

CREATE TABLE `Applicants` (
  `id_applicant` varchar(20) NOT NULL,
  `first_name_applicant` varchar(50) NOT NULL,
  `second_name_applicant` varchar(50) DEFAULT NULL,
  `third_name_applicant` varchar(50) DEFAULT NULL,
  `first_lastname_applicant` varchar(50) NOT NULL,
  `second_lastname_applicant` varchar(50) DEFAULT NULL,
  `email_applicant` varchar(100) NOT NULL,
  `phone_number_applicant` varchar(20) NOT NULL,
  `address_applicant` varchar(255) NOT NULL,
  `image_id_applicant` mediumblob NOT NULL,
  `status_applicant` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ApplicantType`
--

CREATE TABLE `ApplicantType` (
  `id_aplicant_type` int NOT NULL,
  `name_aplicant_type` varchar(50) NOT NULL,
  `admission_test_aplicant` tinyint(1) NOT NULL,
  `status_aplicant_type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ApplicantType`
--

INSERT INTO `ApplicantType` (`id_aplicant_type`, `name_aplicant_type`, `admission_test_aplicant`, `status_aplicant_type`) VALUES
(1, 'PRIMER INGRESO', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Applications`
--

CREATE TABLE `Applications` (
  `id_admission_application_number` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `id_applicant` varchar(20) NOT NULL,
  `id_aplicant_type` int NOT NULL,
  `secondary_certificate_applicant` mediumblob NOT NULL,
  `idregional_center` int NOT NULL,
  `regionalcenter_admissiontest_applicant` int NOT NULL,
  `intendedprimary_undergraduate_applicant` int NOT NULL,
  `intendedsecondary_undergraduate_applicant` int NOT NULL,
  `status_application` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CheckApplicantApplications`
--

CREATE TABLE `CheckApplicantApplications` (
  `id_check_applicant_applications` int NOT NULL,
  `id_applicant` varchar(20) NOT NULL,
  `id_admission_application_number` int NOT NULL,
  `verification_status_data_applicant` tinyint(1) NOT NULL,
  `date_check_applicant_applications` date NOT NULL,
  `revision_status_check_applicant_applications` tinyint(1) NOT NULL,
  `admissions_administrator_check_applicant_applications` int NOT NULL,
  `description_general_check_applicant_applications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CheckErrorsApplicantApplications`
--

CREATE TABLE `CheckErrorsApplicantApplications` (
  `id_check_errors_applicant_applications` int NOT NULL,
  `id_check_applicant_applications` int NOT NULL,
  `incorrect_data` varchar(100) NOT NULL,
  `description_incorrect_data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ConfirmationEmailApplicants`
--

CREATE TABLE `ConfirmationEmailApplicants` (
  `id_confirmation_email_applicant` int NOT NULL,
  `applicant_id_email_confirmation` varchar(20) NOT NULL,
  `email_sent_email_confirmation` tinyint(1) NOT NULL,
  `date_email_sent_email_confirmation` datetime DEFAULT CURRENT_TIMESTAMP,
  `confirmation_code_email_confirmation` varchar(255) NOT NULL,
  `experied_email_confirmation` datetime NOT NULL,
  `status_email_confirmation` enum('pending','used','expired') DEFAULT 'pending',
  `attempts_email_confirmation` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Departments`
--

CREATE TABLE `Departments` (
  `id_department` int NOT NULL,
  `name_departmet` varchar(100) NOT NULL,
  `id_faculty` int NOT NULL,
  `status_department` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Departments`
--

INSERT INTO `Departments` (`id_department`, `name_departmet`, `id_faculty`, `status_department`) VALUES
(1, 'Departamento Ingeniería Agroindustrial', 1, 1),
(2, 'Departamento Ingeniería Agronómica', 1, 1),
(3, 'Departamento Ingeniería en Ciencias Acuícolas', 1, 1),
(4, 'Departamento Ingeniería Eléctrica Industrial', 1, 1),
(5, 'Departamento Ingeniería Forestal', 1, 1),
(6, 'Departamento Ingeniería Industrial', 1, 1),
(7, 'Departamento Ingeniería Mecánica Industrial', 1, 1),
(8, 'Departamento Ingeniería Química Industrial', 1, 1),
(9, 'Departamento Ingeniería en Sistemas', 1, 1),
(10, 'Departamento  Física', 2, 1),
(11, 'Departamento Biología', 2, 1),
(12, 'Departamento Matemática', 2, 1),
(13, 'Departamento de Arquitectura', 3, 1),
(14, 'Departamento de Arte', 3, 1),
(15, 'Departamento de Cultura Física y Deportes', 3, 1),
(16, 'Departamento de Filosofía', 3, 1),
(17, 'Departamento de Lenguas Extranjeras', 3, 1),
(18, 'Departamento de Letras', 3, 1),
(19, 'Departamento Pedagogía y Ciencias de la Educación', 3, 1),
(20, 'Departamento Ingeniería Civil', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DepartmentsRegionalCenters`
--

CREATE TABLE `DepartmentsRegionalCenters` (
  `id_department_Regional_Center` int NOT NULL,
  `id_department` int NOT NULL,
  `id_regionalcenter` int NOT NULL,
  `status_department_regional_center` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `DepartmentsRegionalCenters`
--

INSERT INTO `DepartmentsRegionalCenters` (`id_department_Regional_Center`, `id_department`, `id_regionalcenter`, `status_department_regional_center`) VALUES
(1, 9, 1, 1),
(2, 9, 4, 1),
(3, 9, 2, 1),
(4, 9, 5, 1),
(5, 9, 6, 1),
(6, 12, 1, 1),
(7, 12, 2, 1),
(8, 12, 3, 1),
(9, 12, 4, 1),
(10, 12, 5, 1),
(11, 12, 6, 1),
(12, 13, 1, 1),
(13, 18, 1, 1),
(14, 18, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DocumentValidationAdmissionProcess`
--

CREATE TABLE `DocumentValidationAdmissionProcess` (
  `id_document_validation_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_document_validation_admission_process` date NOT NULL,
  `end_dateof_document_validation_admission_process` date NOT NULL,
  `current_status_document_validation_admission_process` tinyint(1) NOT NULL,
  `status_document_validation_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `DocumentValidationAdmissionProcess`
--

INSERT INTO `DocumentValidationAdmissionProcess` (`id_document_validation_admission_process`, `id_admission_process`, `start_dateof_document_validation_admission_process`, `end_dateof_document_validation_admission_process`, `current_status_document_validation_admission_process`, `status_document_validation_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DownloadApplicantAdmittedInformationAdmissionProcess`
--

CREATE TABLE `DownloadApplicantAdmittedInformationAdmissionProcess` (
  `id_download_applicant_information_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_download_applicant_information_admission_process` date NOT NULL,
  `end_dateof_download_applicant_information_admission_process` date NOT NULL,
  `current_status_download_applicant_information_admission_process` tinyint(1) NOT NULL,
  `status_download_applicant_information_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `DownloadApplicantAdmittedInformationAdmissionProcess`
--

INSERT INTO `DownloadApplicantAdmittedInformationAdmissionProcess` (`id_download_applicant_information_admission_process`, `id_admission_process`, `start_dateof_download_applicant_information_admission_process`, `end_dateof_download_applicant_information_admission_process`, `current_status_download_applicant_information_admission_process`, `status_download_applicant_information_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Faculties`
--

CREATE TABLE `Faculties` (
  `id_faculty` int NOT NULL,
  `name_faculty` varchar(100) NOT NULL,
  `address_faculty` varchar(255) NOT NULL,
  `phone_number_faculty` varchar(20) NOT NULL,
  `email_faculty` varchar(100) NOT NULL,
  `status_faculty` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Faculties`
--

INSERT INTO `Faculties` (`id_faculty`, `name_faculty`, `address_faculty`, `phone_number_faculty`, `email_faculty`, `status_faculty`) VALUES
(1, 'Facultad de Ingeniería', 'Ciudad Universitaria, Edificio B2. Tegucigalpa M.D.C. Honduras, Centroamérica', '2216-6100', 'facultaddeingenieria@unah.edu.hn', 1),
(2, 'Facultad de Ciencias', 'Edificio E1, 2da planta, Ciudad Universitaria Tegucigalpa, M.D.C. Honduras, Centroamérica.', '2216-5100', 'facultaddeciencias@unah.edu.hn', 1),
(3, 'Facultad de Humanidades y Artes', 'Bulevar Suyapa, Tegucigalpa M.D.C., Honduras Decanato Facultad de Humanidades y Artes Planta baja del Edificio F1 Ciudad Universitaria', '2216-6100', 'f.humanidadesyartes@unah.edu.hn', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `InscriptionAdmissionProcess`
--

CREATE TABLE `InscriptionAdmissionProcess` (
  `id_inscription_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_inscription_admission_process` date NOT NULL,
  `end_dateof_inscription_admission_process` date NOT NULL,
  `current_status_inscription_admission_process` tinyint(1) NOT NULL,
  `status_inscription_admission_processs` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `InscriptionAdmissionProcess`
--

INSERT INTO `InscriptionAdmissionProcess` (`id_inscription_admission_process`, `id_admission_process`, `start_dateof_inscription_admission_process`, `end_dateof_inscription_admission_process`, `current_status_inscription_admission_process`, `status_inscription_admission_processs`) VALUES
(1, 1, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NotificationsApplicationsResolution`
--

CREATE TABLE `NotificationsApplicationsResolution` (
  `id_notification_application_resolution` int NOT NULL,
  `id_resolution_intended_undergraduate_applicant` int NOT NULL,
  `email_sent_application_resolution` tinyint(1) NOT NULL,
  `date_email_sent_application_resolution` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NumberExtensions`
--

CREATE TABLE `NumberExtensions` (
  `id_number_extension` int NOT NULL,
  `number_extension` varchar(20) NOT NULL,
  `status_number_extension` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NumberExtensionsRegionalCenters`
--

CREATE TABLE `NumberExtensionsRegionalCenters` (
  `id_number_extension_regional_center` int NOT NULL,
  `id_number_extension` int NOT NULL,
  `id_regional_center` int NOT NULL,
  `status_number_extension_regional_center` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RatingApplicantsTest`
--

CREATE TABLE `RatingApplicantsTest` (
  `id_rating_applicant_test` int NOT NULL,
  `id_admission_application_number` int NOT NULL,
  `id_type_admission_tests` int NOT NULL,
  `rating_applicant` decimal(5,2) NOT NULL,
  `status_rating_applicant_test` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RatingApplicantsTest`
--

INSERT INTO `RatingApplicantsTest` (`id_rating_applicant_test`, `id_admission_application_number`, `id_type_admission_tests`, `rating_applicant`, `status_rating_applicant_test`) VALUES
(1, 1001, 1, 0.00, 0),
(2, 1001, 2, 0.00, 0),
(3, 1001, 3, 0.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RectificationPeriodAdmissionProcess`
--

CREATE TABLE `RectificationPeriodAdmissionProcess` (
  `id_rectification_period_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_rectification_period_admission_process` date NOT NULL,
  `end_dateof_rectification_period_admission_process` date NOT NULL,
  `current_status_rectification_period_admission_process` tinyint(1) NOT NULL,
  `status_rectification_period_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RectificationPeriodAdmissionProcess`
--

INSERT INTO `RectificationPeriodAdmissionProcess` (`id_rectification_period_admission_process`, `id_admission_process`, `start_dateof_rectification_period_admission_process`, `end_dateof_rectification_period_admission_process`, `current_status_rectification_period_admission_process`, `status_rectification_period_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RegionalCenters`
--

CREATE TABLE `RegionalCenters` (
  `id_regional_center` int NOT NULL,
  `name_regional_center` varchar(100) NOT NULL,
  `acronym_regional_center` varchar(20) NOT NULL,
  `location_regional_center` varchar(100) NOT NULL,
  `email_regional_center` varchar(100) NOT NULL,
  `phone_number_regional_center` varchar(20) NOT NULL,
  `address_regional_center` varchar(255) NOT NULL,
  `status_regional_center` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RegionalCenters`
--

INSERT INTO `RegionalCenters` (`id_regional_center`, `name_regional_center`, `acronym_regional_center`, `location_regional_center`, `email_regional_center`, `phone_number_regional_center`, `address_regional_center`, `status_regional_center`) VALUES
(1, 'Ciudad Universitaria', 'CU', 'TEGUCIGALPA', 'formaciontecnologica@unah.edu.hn', '2216-7000', 'Edificio \"Alma mater\" 8° Piso, UNAH, Tegucigalpa, Ciudad Universitaria', 1),
(2, 'UNAH Valle de Sula', 'UNAH-VS', 'CORTES', 'rrpp.unahvs@unah.edu.hn', '2545-6600', 'Edificio 3, UNAH-VS, Sector Pedregal, San Pedro Sula, Honduras, Centroamérica.', 1),
(3, 'Centro Universitario Regional de Litoral Atlántico', 'CURLA', 'ATLANTIDA', 'desarrollo.inst@unah.edu.hn', '2442-9500', 'Carretera CA-13 La Ceiba-Tela, desvío frente a Maxi-Despensa aeropuerto.', 1),
(4, 'Centro Universitario Regional del Centro', 'CURC', 'COMAYAGUA', 'curc@unah.edu.hn', '2771-5700', 'Carretera salida a Tegucigalpa, Colonia San Miguel, contiguo a Ferromax', 1),
(5, 'Centro Univesitario Regional del Litoral Pacífico', 'CURLP', 'CHOLUTECA', 'info.curlp@unah.edu.hn', '(+504)9484-9916', 'Km 5 salida a San Marcos de Colón, desvío a la derecha frente a Residencial Anda Lucía.', 1),
(6, 'Centro Universitario Regional de Occidente', 'CUROC', 'COPÁN', 'jordonez@unah.edu.hn', '2662-3223', 'COPÁN', 1),
(7, 'Centro Tecnológico de Danlí', 'UNAH-TEC Danli ', 'EL PARAISO', 'tecdanli@unah.edu.hn', '2763-9900', 'Danlí, carretera hacía El Paraíso, antes de llegar al Hospital Básico \"Gabriela Alvarado\".', 1),
(8, 'Centro Universitario Regional Nor-Oriental', 'CURNO', 'OLANCHO', 'curno@unah.edu.hn', '1111-2222', 'OLANCHO', 1),
(9, 'Centro Tecnológico del Valle de Aguan', 'UNAH-TEC Aguán', 'YORO', 'tecaguan@unah.edu.hn', '1111-2222', 'YORO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RegistrationRatingAdmissionProcess`
--

CREATE TABLE `RegistrationRatingAdmissionProcess` (
  `id_registration_rating_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_registration_rating_admission_process` date NOT NULL,
  `end_dateof_registration_rating_admission_process` date NOT NULL,
  `current_status_registration_rating_admission_process` tinyint(1) NOT NULL,
  `status_sending_registration_rating_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RegistrationRatingAdmissionProcess`
--

INSERT INTO `RegistrationRatingAdmissionProcess` (`id_registration_rating_admission_process`, `id_admission_process`, `start_dateof_registration_rating_admission_process`, `end_dateof_registration_rating_admission_process`, `current_status_registration_rating_admission_process`, `status_sending_registration_rating_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-12-31', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ResolutionIntendedUndergraduateApplicant`
--

CREATE TABLE `ResolutionIntendedUndergraduateApplicant` (
  `id_resolution_intended_undergraduate_applicant` int NOT NULL,
  `id_admission_application_number` int NOT NULL,
  `intended_undergraduate_applicant` int NOT NULL,
  `resolution_intended` tinyint(1) NOT NULL,
  `status_resolution_intended_undergraduate_applicant` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles`
--

CREATE TABLE `Roles` (
  `id_role` int NOT NULL,
  `role` varchar(50) NOT NULL,
  `description_role` varchar(200) NOT NULL,
  `status_role` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Roles`
--

INSERT INTO `Roles` (`id_role`, `role`, `description_role`, `status_role`) VALUES
(1, 'Strategic Manager Admissions Process', 'Visualizar estadísticas del proceso de admisión.', 1),
(2, 'Admissions Application Verification Assistant', 'Verificar la información personal y de solicitud de los aspirantes.', 1),
(3, 'Applicant Support Assistant', 'Visualiza, busca y edita la información de los aspirantes.', 1),
(4, 'Admissions Application Download Assistant', 'Descarga la información de las aplicaciones del proceso de admisión.', 1),
(5, 'Admissions Grade Entry Assistant', 'Carga de las notas de los exámenes de admisión de los solicitantes.', 1),
(6, 'Admissions Admitted Applicants Download Assistant', 'Descarga la información de los aspirantes admitidos en el proceso de admisión.', 1),
(7, 'Applicant', 'Seleccion de carrera', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RolesUsersAdmissionsAdministrator`
--

CREATE TABLE `RolesUsersAdmissionsAdministrator` (
  `id_user_admissions_administrator` int NOT NULL,
  `id_role_admissions_administrator` int NOT NULL,
  `status_role_admissions_administrator` tinyint(1) NOT NULL,
  `id_regional_center` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RolesUsersAdmissionsAdministrator`
--

INSERT INTO `RolesUsersAdmissionsAdministrator` (`id_user_admissions_administrator`, `id_role_admissions_administrator`, `status_role_admissions_administrator`, `id_regional_center`) VALUES
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 1, 1),
(5, 5, 1, 1),
(6, 6, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SendingNotificationsAdmissionProcess`
--

CREATE TABLE `SendingNotificationsAdmissionProcess` (
  `id_sending_notifications_admission_process` int NOT NULL,
  `id_admission_process` int NOT NULL,
  `start_dateof_sending_notifications_admission_process` date NOT NULL,
  `end_dateof_sending_notifications_admission_process` date NOT NULL,
  `star_timeof_sending_notifications_admission_process` time NOT NULL,
  `end_timeof_sending_notifications_admission_process` time NOT NULL,
  `current_status_sending_notifications_admission_process` tinyint(1) NOT NULL,
  `status_sending_notifications_admission_process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `SendingNotificationsAdmissionProcess`
--

INSERT INTO `SendingNotificationsAdmissionProcess` (`id_sending_notifications_admission_process`, `id_admission_process`, `start_dateof_sending_notifications_admission_process`, `end_dateof_sending_notifications_admission_process`, `star_timeof_sending_notifications_admission_process`, `end_timeof_sending_notifications_admission_process`, `current_status_sending_notifications_admission_process`, `status_sending_notifications_admission_process`) VALUES
(1, 1, '2024-01-01', '2024-12-31', '21:00:00', '04:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TokenUserAdmissionAdmin`
--

CREATE TABLE `TokenUserAdmissionAdmin` (
  `id_token_user_admission_administrator` int NOT NULL,
  `token` varchar(512) DEFAULT NULL,
  `id_user_admissions_administrator` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `TokenUserAdmissionAdmin`
--

INSERT INTO `TokenUserAdmissionAdmin` (`id_token_user_admission_administrator`, `token`, `id_user_admissions_administrator`) VALUES
(1, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyQWRtaXNzaW9uQWRtaW4iOiJhZG1pbjA4MDExOTkwMDIxIiwiYWNjZXNzQXJyYXkiOlsibHd4NTBLN2YiXSwiZXhwIjoxNzMyNzkyNjI3fQ.vlnAKOgJM5IDrmUHlOcNC3vfK9zlQlQ4-yaQxnQLpU0', 2),
(2, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyQWRtaXNzaW9uQWRtaW4iOiJhZG1pbjA3MDMxOTg3MDE2IiwiYWNjZXNzQXJyYXkiOlsiSWVNZnRpMjAiXSwiZXhwIjoxNzMyNzkyNDQzfQ.hABmE73LfUgcbBOGzuKUFfGLBwsktokW81IU6MXwQR4', 3),
(3, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyQWRtaXNzaW9uQWRtaW4iOiJhZG1pbjExMDIxOTk1MDM3IiwiYWNjZXNzQXJyYXkiOlsicmxsSGF2ZXEiXSwiZXhwIjoxNzMyNzkyNTY2fQ.STCUMryefBsDnJVqMwwuEtNPZCUz7IhsaJcS5QYcmX0', 4),
(4, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyQWRtaXNzaW9uQWRtaW4iOiJhZG1pbjAyMDEyMDAwMDQzIiwiYWNjZXNzQXJyYXkiOlsiRnoxWWVSZ3YiXSwiZXhwIjoxNzMyNzkyNTk5fQ.4o5ONofLH4pf7oGPj2lF4OKOwatkVgfDbGVB32L2Ruo', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TokenUserApplicant`
--

CREATE TABLE `TokenUserApplicant` (
  `id_token_user_applicant` int NOT NULL,
  `token` varchar(512) DEFAULT NULL,
  `id_user_applicant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TypesAdmissionTests`
--

CREATE TABLE `TypesAdmissionTests` (
  `id_type_admission_tests` int NOT NULL,
  `name_type_admission_tests` varchar(100) NOT NULL,
  `status_type_admission_tests` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `TypesAdmissionTests`
--

INSERT INTO `TypesAdmissionTests` (`id_type_admission_tests`, `name_type_admission_tests`, `status_type_admission_tests`) VALUES
(1, 'Pruebas Psicológicas Específicas de Arquitectura (PPEA)', 1),
(2, 'Prueba de Aptitud Académica (PAA)', 1),
(3, 'Prueba de Aprovechamiento Matemático (PAM)', 1),
(4, 'Prueba de Conocimientos de las Ciencias Naturales y de la Salud (PCCNS)', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Undergraduates`
--

CREATE TABLE `Undergraduates` (
  `id_undergraduate` int NOT NULL,
  `name_undergraduate` varchar(100) NOT NULL,
  `id_department` int NOT NULL,
  `status_undergraduate` tinyint(1) NOT NULL,
  `duration_undergraduate` float(2,1) NOT NULL,
  `mode_undergraduate` enum('Presencial','Virtual','Distancia') NOT NULL,
  `study_plan_undergraduate` longblob
) ;

--
-- Volcado de datos para la tabla `Undergraduates`
--

INSERT INTO `Undergraduates` (`id_undergraduate`, `name_undergraduate`, `id_department`, `status_undergraduate`, `duration_undergraduate`, `mode_undergraduate`, `study_plan_undergraduate`) VALUES
(1, 'Arquitectura', 13, 1, 5.0, 'Presencial', NULL),
(2, 'Ingeniería en Sistemas', 9, 1, 5.0, 'Presencial', NULL),
(3, 'Licenciatura en Letras', 18, 1, 5.0, 'Presencial', NULL),
(4, 'Licenciatura en Matemática', 12, 1, 4.0, 'Presencial', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UndergraduatesRegionalCenters`
--

CREATE TABLE `UndergraduatesRegionalCenters` (
  `id_undergraduate_Regional_Center` int NOT NULL,
  `id_undergraduate` int NOT NULL,
  `id_regionalcenter` int NOT NULL,
  `status_undergraduate_Regional_Center` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `UndergraduatesRegionalCenters`
--

INSERT INTO `UndergraduatesRegionalCenters` (`id_undergraduate_Regional_Center`, `id_undergraduate`, `id_regionalcenter`, `status_undergraduate_Regional_Center`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 2, 2, 1),
(4, 2, 4, 1),
(5, 2, 5, 1),
(6, 2, 6, 1),
(7, 3, 1, 1),
(8, 3, 2, 1),
(9, 4, 1, 1),
(10, 4, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UndergraduateTypesAdmissionTests`
--

CREATE TABLE `UndergraduateTypesAdmissionTests` (
  `id_undergraduate_type_admission_tests` int NOT NULL,
  `id_type_admission_tests` int NOT NULL,
  `id_undergraduate` int NOT NULL,
  `required_rating` decimal(5,2) NOT NULL,
  `status_undergraduate_type_admission_tests` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `UndergraduateTypesAdmissionTests`
--

INSERT INTO `UndergraduateTypesAdmissionTests` (`id_undergraduate_type_admission_tests`, `id_type_admission_tests`, `id_undergraduate`, `required_rating`, `status_undergraduate_type_admission_tests`) VALUES
(1, 1, 1, 400.00, 1),
(2, 2, 1, 900.00, 1),
(3, 3, 2, 400.00, 0),
(4, 2, 2, 900.00, 1),
(5, 2, 3, 700.00, 1),
(6, 2, 4, 700.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersAdmissionsAdministrator`
--

CREATE TABLE `UsersAdmissionsAdministrator` (
  `id_user_admissions_administrator` int NOT NULL,
  `username_user_admissions_administrator` varchar(50) NOT NULL,
  `password_user_admissions_administrator` varchar(100) NOT NULL,
  `status_user_admissions_administrator` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `UsersAdmissionsAdministrator`
--

INSERT INTO `UsersAdmissionsAdministrator` (`id_user_admissions_administrator`, `username_user_admissions_administrator`, `password_user_admissions_administrator`, `status_user_admissions_administrator`) VALUES
(1, 'admin_nouser', 'MiContraseña1+', 0),
(2, 'admin08011990021', 'Apple1*B', 1),
(3, 'admin07031987016', 'Cat4*Dog', 1),
(4, 'admin11021995037', 'Happy7+T', 1),
(5, 'admin02012000043', 'Book2-Tea', 1),
(6, 'admin19041985058', 'Tree9+Fly', 1),
(7, 'admin05011993066', 'Light8-Now', 1),
(8, 'admin15031989071', 'Star5*Moon', 1),
(9, 'admin_user', 'BienHecho2-*', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersApplicants`
--

CREATE TABLE `UsersApplicants` (
  `id_user_applicant` int NOT NULL,
  `username_user_applicant` varchar(50) NOT NULL,
  `password_user_applicant` varchar(100) NOT NULL,
  `status_user_applicant` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `AcademicYear`
--
ALTER TABLE `AcademicYear`
  ADD PRIMARY KEY (`id_academic_year`);

--
-- Indices de la tabla `AcceptanceAdmissionProcess`
--
ALTER TABLE `AcceptanceAdmissionProcess`
  ADD PRIMARY KEY (`id_acceptance_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `AccessControl`
--
ALTER TABLE `AccessControl`
  ADD PRIMARY KEY (`id_access_control`);

--
-- Indices de la tabla `AccessControlRoles`
--
ALTER TABLE `AccessControlRoles`
  ADD PRIMARY KEY (`id_role`,`id_access_control`),
  ADD KEY `id_access_control` (`id_access_control`);

--
-- Indices de la tabla `AdmissionProcess`
--
ALTER TABLE `AdmissionProcess`
  ADD PRIMARY KEY (`id_admission_process`),
  ADD KEY `id_academic_year` (`id_academic_year`);

--
-- Indices de la tabla `AdmissionTestAdmissionProcess`
--
ALTER TABLE `AdmissionTestAdmissionProcess`
  ADD PRIMARY KEY (`id_admission_test_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `ApplicantAcceptance`
--
ALTER TABLE `ApplicantAcceptance`
  ADD PRIMARY KEY (`id_applicant_acceptance`),
  ADD KEY `id_notification_application_resolution` (`id_notification_application_resolution`),
  ADD KEY `id_applicant` (`id_applicant`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `Applicants`
--
ALTER TABLE `Applicants`
  ADD PRIMARY KEY (`id_applicant`),
  ADD UNIQUE KEY `email_applicant` (`email_applicant`);

--
-- Indices de la tabla `ApplicantType`
--
ALTER TABLE `ApplicantType`
  ADD PRIMARY KEY (`id_aplicant_type`);

--
-- Indices de la tabla `Applications`
--
ALTER TABLE `Applications`
  ADD PRIMARY KEY (`id_admission_application_number`),
  ADD KEY `id_applicant` (`id_applicant`),
  ADD KEY `id_aplicant_type` (`id_aplicant_type`),
  ADD KEY `idregional_center` (`idregional_center`),
  ADD KEY `regionalcenter_admissiontest_applicant` (`regionalcenter_admissiontest_applicant`),
  ADD KEY `id_admission_process` (`id_admission_process`),
  ADD KEY `intendedprimary_undergraduate_applicant` (`intendedprimary_undergraduate_applicant`),
  ADD KEY `intendedsecondary_undergraduate_applicant` (`intendedsecondary_undergraduate_applicant`);

--
-- Indices de la tabla `CheckApplicantApplications`
--
ALTER TABLE `CheckApplicantApplications`
  ADD PRIMARY KEY (`id_check_applicant_applications`),
  ADD KEY `id_applicant` (`id_applicant`),
  ADD KEY `id_admission_application_number` (`id_admission_application_number`),
  ADD KEY `admissions_administrator_check_applicant_applications` (`admissions_administrator_check_applicant_applications`);

--
-- Indices de la tabla `CheckErrorsApplicantApplications`
--
ALTER TABLE `CheckErrorsApplicantApplications`
  ADD PRIMARY KEY (`id_check_errors_applicant_applications`),
  ADD KEY `id_check_applicant_applications` (`id_check_applicant_applications`);

--
-- Indices de la tabla `ConfirmationEmailApplicants`
--
ALTER TABLE `ConfirmationEmailApplicants`
  ADD PRIMARY KEY (`id_confirmation_email_applicant`);

--
-- Indices de la tabla `Departments`
--
ALTER TABLE `Departments`
  ADD PRIMARY KEY (`id_department`),
  ADD KEY `id_faculty` (`id_faculty`);

--
-- Indices de la tabla `DepartmentsRegionalCenters`
--
ALTER TABLE `DepartmentsRegionalCenters`
  ADD PRIMARY KEY (`id_department_Regional_Center`),
  ADD KEY `id_department` (`id_department`),
  ADD KEY `id_regionalcenter` (`id_regionalcenter`);

--
-- Indices de la tabla `DocumentValidationAdmissionProcess`
--
ALTER TABLE `DocumentValidationAdmissionProcess`
  ADD PRIMARY KEY (`id_document_validation_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `DownloadApplicantAdmittedInformationAdmissionProcess`
--
ALTER TABLE `DownloadApplicantAdmittedInformationAdmissionProcess`
  ADD PRIMARY KEY (`id_download_applicant_information_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `Faculties`
--
ALTER TABLE `Faculties`
  ADD PRIMARY KEY (`id_faculty`);

--
-- Indices de la tabla `InscriptionAdmissionProcess`
--
ALTER TABLE `InscriptionAdmissionProcess`
  ADD PRIMARY KEY (`id_inscription_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `NotificationsApplicationsResolution`
--
ALTER TABLE `NotificationsApplicationsResolution`
  ADD PRIMARY KEY (`id_notification_application_resolution`),
  ADD KEY `id_resolution_intended_undergraduate_applicant` (`id_resolution_intended_undergraduate_applicant`);

--
-- Indices de la tabla `NumberExtensions`
--
ALTER TABLE `NumberExtensions`
  ADD PRIMARY KEY (`id_number_extension`);

--
-- Indices de la tabla `NumberExtensionsRegionalCenters`
--
ALTER TABLE `NumberExtensionsRegionalCenters`
  ADD PRIMARY KEY (`id_number_extension_regional_center`),
  ADD KEY `id_number_extension` (`id_number_extension`),
  ADD KEY `id_regional_center` (`id_regional_center`);

--
-- Indices de la tabla `RatingApplicantsTest`
--
ALTER TABLE `RatingApplicantsTest`
  ADD PRIMARY KEY (`id_rating_applicant_test`),
  ADD KEY `id_admission_application_number` (`id_admission_application_number`),
  ADD KEY `id_type_admission_tests` (`id_type_admission_tests`);

--
-- Indices de la tabla `RectificationPeriodAdmissionProcess`
--
ALTER TABLE `RectificationPeriodAdmissionProcess`
  ADD PRIMARY KEY (`id_rectification_period_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `RegionalCenters`
--
ALTER TABLE `RegionalCenters`
  ADD PRIMARY KEY (`id_regional_center`);

--
-- Indices de la tabla `RegistrationRatingAdmissionProcess`
--
ALTER TABLE `RegistrationRatingAdmissionProcess`
  ADD PRIMARY KEY (`id_registration_rating_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `ResolutionIntendedUndergraduateApplicant`
--
ALTER TABLE `ResolutionIntendedUndergraduateApplicant`
  ADD PRIMARY KEY (`id_resolution_intended_undergraduate_applicant`),
  ADD KEY `id_admission_application_number` (`id_admission_application_number`),
  ADD KEY `intended_undergraduate_applicant` (`intended_undergraduate_applicant`);

--
-- Indices de la tabla `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indices de la tabla `RolesUsersAdmissionsAdministrator`
--
ALTER TABLE `RolesUsersAdmissionsAdministrator`
  ADD PRIMARY KEY (`id_user_admissions_administrator`,`id_role_admissions_administrator`),
  ADD KEY `id_role_admissions_administrator` (`id_role_admissions_administrator`),
  ADD KEY `id_regional_center` (`id_regional_center`);

--
-- Indices de la tabla `SendingNotificationsAdmissionProcess`
--
ALTER TABLE `SendingNotificationsAdmissionProcess`
  ADD PRIMARY KEY (`id_sending_notifications_admission_process`),
  ADD KEY `id_admission_process` (`id_admission_process`);

--
-- Indices de la tabla `TokenUserAdmissionAdmin`
--
ALTER TABLE `TokenUserAdmissionAdmin`
  ADD PRIMARY KEY (`id_token_user_admission_administrator`),
  ADD UNIQUE KEY `id_user_admissions_administrator` (`id_user_admissions_administrator`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `TokenUserApplicant`
--
ALTER TABLE `TokenUserApplicant`
  ADD PRIMARY KEY (`id_token_user_applicant`),
  ADD UNIQUE KEY `id_user_applicant` (`id_user_applicant`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `TypesAdmissionTests`
--
ALTER TABLE `TypesAdmissionTests`
  ADD PRIMARY KEY (`id_type_admission_tests`);

--
-- Indices de la tabla `Undergraduates`
--
ALTER TABLE `Undergraduates`
  ADD PRIMARY KEY (`id_undergraduate`),
  ADD KEY `id_department` (`id_department`);

--
-- Indices de la tabla `UndergraduatesRegionalCenters`
--
ALTER TABLE `UndergraduatesRegionalCenters`
  ADD PRIMARY KEY (`id_undergraduate_Regional_Center`),
  ADD KEY `id_undergraduate` (`id_undergraduate`),
  ADD KEY `id_regionalcenter` (`id_regionalcenter`);

--
-- Indices de la tabla `UndergraduateTypesAdmissionTests`
--
ALTER TABLE `UndergraduateTypesAdmissionTests`
  ADD PRIMARY KEY (`id_undergraduate_type_admission_tests`);

--
-- Indices de la tabla `UsersAdmissionsAdministrator`
--
ALTER TABLE `UsersAdmissionsAdministrator`
  ADD PRIMARY KEY (`id_user_admissions_administrator`),
  ADD UNIQUE KEY `username_user_admissions_administrator` (`username_user_admissions_administrator`);

--
-- Indices de la tabla `UsersApplicants`
--
ALTER TABLE `UsersApplicants`
  ADD PRIMARY KEY (`id_user_applicant`),
  ADD UNIQUE KEY `username_user_applicant` (`username_user_applicant`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `AcademicYear`
--
ALTER TABLE `AcademicYear`
  MODIFY `id_academic_year` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2025;

--
-- AUTO_INCREMENT de la tabla `AcceptanceAdmissionProcess`
--
ALTER TABLE `AcceptanceAdmissionProcess`
  MODIFY `id_acceptance_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `AdmissionProcess`
--
ALTER TABLE `AdmissionProcess`
  MODIFY `id_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `AdmissionTestAdmissionProcess`
--
ALTER TABLE `AdmissionTestAdmissionProcess`
  MODIFY `id_admission_test_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ApplicantAcceptance`
--
ALTER TABLE `ApplicantAcceptance`
  MODIFY `id_applicant_acceptance` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ApplicantType`
--
ALTER TABLE `ApplicantType`
  MODIFY `id_aplicant_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `Applications`
--
ALTER TABLE `Applications`
  MODIFY `id_admission_application_number` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `CheckApplicantApplications`
--
ALTER TABLE `CheckApplicantApplications`
  MODIFY `id_check_applicant_applications` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `CheckErrorsApplicantApplications`
--
ALTER TABLE `CheckErrorsApplicantApplications`
  MODIFY `id_check_errors_applicant_applications` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ConfirmationEmailApplicants`
--
ALTER TABLE `ConfirmationEmailApplicants`
  MODIFY `id_confirmation_email_applicant` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Departments`
--
ALTER TABLE `Departments`
  MODIFY `id_department` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `DepartmentsRegionalCenters`
--
ALTER TABLE `DepartmentsRegionalCenters`
  MODIFY `id_department_Regional_Center` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `DocumentValidationAdmissionProcess`
--
ALTER TABLE `DocumentValidationAdmissionProcess`
  MODIFY `id_document_validation_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `DownloadApplicantAdmittedInformationAdmissionProcess`
--
ALTER TABLE `DownloadApplicantAdmittedInformationAdmissionProcess`
  MODIFY `id_download_applicant_information_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `Faculties`
--
ALTER TABLE `Faculties`
  MODIFY `id_faculty` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `InscriptionAdmissionProcess`
--
ALTER TABLE `InscriptionAdmissionProcess`
  MODIFY `id_inscription_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `NotificationsApplicationsResolution`
--
ALTER TABLE `NotificationsApplicationsResolution`
  MODIFY `id_notification_application_resolution` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `NumberExtensions`
--
ALTER TABLE `NumberExtensions`
  MODIFY `id_number_extension` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `NumberExtensionsRegionalCenters`
--
ALTER TABLE `NumberExtensionsRegionalCenters`
  MODIFY `id_number_extension_regional_center` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RatingApplicantsTest`
--
ALTER TABLE `RatingApplicantsTest`
  MODIFY `id_rating_applicant_test` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `RectificationPeriodAdmissionProcess`
--
ALTER TABLE `RectificationPeriodAdmissionProcess`
  MODIFY `id_rectification_period_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `RegionalCenters`
--
ALTER TABLE `RegionalCenters`
  MODIFY `id_regional_center` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `RegistrationRatingAdmissionProcess`
--
ALTER TABLE `RegistrationRatingAdmissionProcess`
  MODIFY `id_registration_rating_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ResolutionIntendedUndergraduateApplicant`
--
ALTER TABLE `ResolutionIntendedUndergraduateApplicant`
  MODIFY `id_resolution_intended_undergraduate_applicant` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `SendingNotificationsAdmissionProcess`
--
ALTER TABLE `SendingNotificationsAdmissionProcess`
  MODIFY `id_sending_notifications_admission_process` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `TokenUserAdmissionAdmin`
--
ALTER TABLE `TokenUserAdmissionAdmin`
  MODIFY `id_token_user_admission_administrator` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `TokenUserApplicant`
--
ALTER TABLE `TokenUserApplicant`
  MODIFY `id_token_user_applicant` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TypesAdmissionTests`
--
ALTER TABLE `TypesAdmissionTests`
  MODIFY `id_type_admission_tests` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `Undergraduates`
--
ALTER TABLE `Undergraduates`
  MODIFY `id_undergraduate` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `UndergraduatesRegionalCenters`
--
ALTER TABLE `UndergraduatesRegionalCenters`
  MODIFY `id_undergraduate_Regional_Center` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `UndergraduateTypesAdmissionTests`
--
ALTER TABLE `UndergraduateTypesAdmissionTests`
  MODIFY `id_undergraduate_type_admission_tests` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `UsersAdmissionsAdministrator`
--
ALTER TABLE `UsersAdmissionsAdministrator`
  MODIFY `id_user_admissions_administrator` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `UsersApplicants`
--
ALTER TABLE `UsersApplicants`
  MODIFY `id_user_applicant` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `AcceptanceAdmissionProcess`
--
ALTER TABLE `AcceptanceAdmissionProcess`
  ADD CONSTRAINT `AcceptanceAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `AccessControlRoles`
--
ALTER TABLE `AccessControlRoles`
  ADD CONSTRAINT `AccessControlRoles_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `Roles` (`id_role`),
  ADD CONSTRAINT `AccessControlRoles_ibfk_2` FOREIGN KEY (`id_access_control`) REFERENCES `AccessControl` (`id_access_control`);

--
-- Filtros para la tabla `AdmissionProcess`
--
ALTER TABLE `AdmissionProcess`
  ADD CONSTRAINT `AdmissionProcess_ibfk_1` FOREIGN KEY (`id_academic_year`) REFERENCES `AcademicYear` (`id_academic_year`);

--
-- Filtros para la tabla `AdmissionTestAdmissionProcess`
--
ALTER TABLE `AdmissionTestAdmissionProcess`
  ADD CONSTRAINT `AdmissionTestAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `ApplicantAcceptance`
--
ALTER TABLE `ApplicantAcceptance`
  ADD CONSTRAINT `ApplicantAcceptance_ibfk_1` FOREIGN KEY (`id_notification_application_resolution`) REFERENCES `NotificationsApplicationsResolution` (`id_notification_application_resolution`),
  ADD CONSTRAINT `ApplicantAcceptance_ibfk_2` FOREIGN KEY (`id_applicant`) REFERENCES `Applicants` (`id_applicant`),
  ADD CONSTRAINT `ApplicantAcceptance_ibfk_3` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `Applications`
--
ALTER TABLE `Applications`
  ADD CONSTRAINT `Applications_ibfk_1` FOREIGN KEY (`id_applicant`) REFERENCES `Applicants` (`id_applicant`),
  ADD CONSTRAINT `Applications_ibfk_2` FOREIGN KEY (`id_aplicant_type`) REFERENCES `ApplicantType` (`id_aplicant_type`),
  ADD CONSTRAINT `Applications_ibfk_3` FOREIGN KEY (`idregional_center`) REFERENCES `RegionalCenters` (`id_regional_center`),
  ADD CONSTRAINT `Applications_ibfk_4` FOREIGN KEY (`regionalcenter_admissiontest_applicant`) REFERENCES `RegionalCenters` (`id_regional_center`),
  ADD CONSTRAINT `Applications_ibfk_5` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`),
  ADD CONSTRAINT `Applications_ibfk_6` FOREIGN KEY (`intendedprimary_undergraduate_applicant`) REFERENCES `Undergraduates` (`id_undergraduate`),
  ADD CONSTRAINT `Applications_ibfk_7` FOREIGN KEY (`intendedsecondary_undergraduate_applicant`) REFERENCES `Undergraduates` (`id_undergraduate`);

--
-- Filtros para la tabla `CheckApplicantApplications`
--
ALTER TABLE `CheckApplicantApplications`
  ADD CONSTRAINT `CheckApplicantApplications_ibfk_1` FOREIGN KEY (`id_applicant`) REFERENCES `Applicants` (`id_applicant`),
  ADD CONSTRAINT `CheckApplicantApplications_ibfk_2` FOREIGN KEY (`id_admission_application_number`) REFERENCES `Applications` (`id_admission_application_number`),
  ADD CONSTRAINT `CheckApplicantApplications_ibfk_3` FOREIGN KEY (`admissions_administrator_check_applicant_applications`) REFERENCES `UsersAdmissionsAdministrator` (`id_user_admissions_administrator`);

--
-- Filtros para la tabla `CheckErrorsApplicantApplications`
--
ALTER TABLE `CheckErrorsApplicantApplications`
  ADD CONSTRAINT `CheckErrorsApplicantApplications_ibfk_1` FOREIGN KEY (`id_check_applicant_applications`) REFERENCES `CheckApplicantApplications` (`id_check_applicant_applications`);

--
-- Filtros para la tabla `Departments`
--
ALTER TABLE `Departments`
  ADD CONSTRAINT `Departments_ibfk_1` FOREIGN KEY (`id_faculty`) REFERENCES `Faculties` (`id_faculty`);

--
-- Filtros para la tabla `DepartmentsRegionalCenters`
--
ALTER TABLE `DepartmentsRegionalCenters`
  ADD CONSTRAINT `DepartmentsRegionalCenters_ibfk_1` FOREIGN KEY (`id_department`) REFERENCES `Departments` (`id_department`),
  ADD CONSTRAINT `DepartmentsRegionalCenters_ibfk_2` FOREIGN KEY (`id_regionalcenter`) REFERENCES `RegionalCenters` (`id_regional_center`);

--
-- Filtros para la tabla `DocumentValidationAdmissionProcess`
--
ALTER TABLE `DocumentValidationAdmissionProcess`
  ADD CONSTRAINT `DocumentValidationAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `DownloadApplicantAdmittedInformationAdmissionProcess`
--
ALTER TABLE `DownloadApplicantAdmittedInformationAdmissionProcess`
  ADD CONSTRAINT `DownloadApplicantAdmittedInformationAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `InscriptionAdmissionProcess`
--
ALTER TABLE `InscriptionAdmissionProcess`
  ADD CONSTRAINT `InscriptionAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `NotificationsApplicationsResolution`
--
ALTER TABLE `NotificationsApplicationsResolution`
  ADD CONSTRAINT `NotificationsApplicationsResolution_ibfk_1` FOREIGN KEY (`id_resolution_intended_undergraduate_applicant`) REFERENCES `ResolutionIntendedUndergraduateApplicant` (`id_resolution_intended_undergraduate_applicant`);

--
-- Filtros para la tabla `NumberExtensionsRegionalCenters`
--
ALTER TABLE `NumberExtensionsRegionalCenters`
  ADD CONSTRAINT `NumberExtensionsRegionalCenters_ibfk_1` FOREIGN KEY (`id_number_extension`) REFERENCES `NumberExtensions` (`id_number_extension`),
  ADD CONSTRAINT `NumberExtensionsRegionalCenters_ibfk_2` FOREIGN KEY (`id_regional_center`) REFERENCES `RegionalCenters` (`id_regional_center`);

--
-- Filtros para la tabla `RatingApplicantsTest`
--
ALTER TABLE `RatingApplicantsTest`
  ADD CONSTRAINT `RatingApplicantsTest_ibfk_1` FOREIGN KEY (`id_admission_application_number`) REFERENCES `Applications` (`id_admission_application_number`),
  ADD CONSTRAINT `RatingApplicantsTest_ibfk_2` FOREIGN KEY (`id_type_admission_tests`) REFERENCES `TypesAdmissionTests` (`id_type_admission_tests`);

--
-- Filtros para la tabla `RectificationPeriodAdmissionProcess`
--
ALTER TABLE `RectificationPeriodAdmissionProcess`
  ADD CONSTRAINT `RectificationPeriodAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `RegistrationRatingAdmissionProcess`
--
ALTER TABLE `RegistrationRatingAdmissionProcess`
  ADD CONSTRAINT `RegistrationRatingAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `ResolutionIntendedUndergraduateApplicant`
--
ALTER TABLE `ResolutionIntendedUndergraduateApplicant`
  ADD CONSTRAINT `ResolutionIntendedUndergraduateApplicant_ibfk_1` FOREIGN KEY (`id_admission_application_number`) REFERENCES `Applications` (`id_admission_application_number`),
  ADD CONSTRAINT `ResolutionIntendedUndergraduateApplicant_ibfk_2` FOREIGN KEY (`intended_undergraduate_applicant`) REFERENCES `Undergraduates` (`id_undergraduate`);

--
-- Filtros para la tabla `RolesUsersAdmissionsAdministrator`
--
ALTER TABLE `RolesUsersAdmissionsAdministrator`
  ADD CONSTRAINT `RolesUsersAdmissionsAdministrator_ibfk_1` FOREIGN KEY (`id_user_admissions_administrator`) REFERENCES `UsersAdmissionsAdministrator` (`id_user_admissions_administrator`),
  ADD CONSTRAINT `RolesUsersAdmissionsAdministrator_ibfk_2` FOREIGN KEY (`id_role_admissions_administrator`) REFERENCES `Roles` (`id_role`),
  ADD CONSTRAINT `RolesUsersAdmissionsAdministrator_ibfk_3` FOREIGN KEY (`id_regional_center`) REFERENCES `RegionalCenters` (`id_regional_center`);

--
-- Filtros para la tabla `SendingNotificationsAdmissionProcess`
--
ALTER TABLE `SendingNotificationsAdmissionProcess`
  ADD CONSTRAINT `SendingNotificationsAdmissionProcess_ibfk_1` FOREIGN KEY (`id_admission_process`) REFERENCES `AdmissionProcess` (`id_admission_process`);

--
-- Filtros para la tabla `TokenUserAdmissionAdmin`
--
ALTER TABLE `TokenUserAdmissionAdmin`
  ADD CONSTRAINT `TokenUserAdmissionAdmin_ibfk_1` FOREIGN KEY (`id_user_admissions_administrator`) REFERENCES `UsersAdmissionsAdministrator` (`id_user_admissions_administrator`);

--
-- Filtros para la tabla `TokenUserApplicant`
--
ALTER TABLE `TokenUserApplicant`
  ADD CONSTRAINT `TokenUserApplicant_ibfk_1` FOREIGN KEY (`id_user_applicant`) REFERENCES `UsersApplicants` (`id_user_applicant`);

--
-- Filtros para la tabla `Undergraduates`
--
ALTER TABLE `Undergraduates`
  ADD CONSTRAINT `Undergraduates_ibfk_1` FOREIGN KEY (`id_department`) REFERENCES `Departments` (`id_department`);

--
-- Filtros para la tabla `UndergraduatesRegionalCenters`
--
ALTER TABLE `UndergraduatesRegionalCenters`
  ADD CONSTRAINT `UndergraduatesRegionalCenters_ibfk_1` FOREIGN KEY (`id_undergraduate`) REFERENCES `Undergraduates` (`id_undergraduate`),
  ADD CONSTRAINT `UndergraduatesRegionalCenters_ibfk_2` FOREIGN KEY (`id_regionalcenter`) REFERENCES `RegionalCenters` (`id_regional_center`);

--
-- Filtros para la tabla `UsersApplicants`
--
ALTER TABLE `UsersApplicants`
  ADD CONSTRAINT `UsersApplicants_ibfk_1` FOREIGN KEY (`username_user_applicant`) REFERENCES `Applicants` (`id_applicant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
