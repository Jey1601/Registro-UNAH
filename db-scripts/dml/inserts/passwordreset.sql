CREATE TABLE PasswordResetRequestsStudents (
    id_reset_request_student INT AUTO_INCREMENT PRIMARY KEY,
    id_user_student INT NOT NULL,
    token_id_student INT NOT NULL,
    token_expiry_student DATETIME NOT NULL,
    used_token_student BOOLEAN DEFAULT FALSE,
    request_student_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user_student) REFERENCES UsersStudents(id_user_student),
    FOREIGN KEY (token_id_student) REFERENCES TokenUserStudent(id_token_user_student)
);


CREATE TABLE PasswordResetRequestsProfessors (
    id_reset_request_professor INT AUTO_INCREMENT PRIMARY KEY,
    id_user_professor INT NOT NULL,
    token_id_professor INT NOT NULL,
    token_expiry_professor DATETIME NOT NULL,
    used_token_professor BOOLEAN DEFAULT FALSE,
    request_professor_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user_professor) REFERENCES UsersProfessors(id_user_professor),
    FOREIGN KEY (token_id_professor) REFERENCES TokenUserProfessor(id_token_user_professor)
);