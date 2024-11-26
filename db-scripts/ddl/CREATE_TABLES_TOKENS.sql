--TABLA RELACIONAL ENTRE UN TOKEN GENERADO Y UN USUARIO ADMINISTRADOR DE ADMISIONES
USE unah_registration;

CREATE TABLE TokenUserAdmissionAdmin (
    id_token_user_admission_administrator INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_admissions_administrator INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_admissions_administrator) REFERENCES UsersAdmissionsAdministrator(id_user_admissions_administrator)
);

--TABLA RELACIONAL ENTRE UN TOKEN GENERADO Y UN USUARIO ASPIRANTE
CREATE TABLE TokenUserApplicant (
    id_token_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_applicant INT UNIQUE NOT NULL,
    FOREIGN KEY (id_user_applicant) REFERENCES UsersApplicants(id_user_applicant)
);