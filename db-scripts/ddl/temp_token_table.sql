--TABLA RELACIONAL ENTRE UN TOKEN GENERADO Y UN USUARIO ADMINISTRADOR DE ADMISIONES
CREATE TABLE TokenUserAdmissionAdmin (
    id_token_user_admission_administrator INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_admissions_administrator INT NOT NULL,
    FOREIGN KEY (id_user_admissions_administrator) REFERENCES UsersAdmissionsAdministrator(id_user_admissions_administrator),
    ON UPDATE CASCADE
);

--TABLA RELACIONAL ENTRE UN TOKEN GENERADO Y UN USUARIO ASPIRANTE
CREATE TABLE TokenUserApplicant (
    id_token_user_applicant INT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(512) UNIQUE,
    id_user_applicant INT NOT NULL,
    FOREIGN KEY (id_user_applicant) REFERENCES UsersApplicants(id_user_applicant)
);