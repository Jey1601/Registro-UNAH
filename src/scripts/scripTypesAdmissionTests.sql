USE unah_registration;

/*1) CREA UN NUEVO TIPO DE EXAMEN USANDO EL EJEMPLO QUE SE MUESTRA A CONTINUACIÓN*/
INSERT INTO TypesAdmissionTests (name_type_admission_tests, status_type_admission_tests)

/*SOLO DEBES SUSTITUIR EL NOMBRE DEL TIPO DE EXAMEN Y DECLARAR EL ESTADO DEL MISMO (ACTIVO O INACTIVO)*/
VALUES ('Prueba de Conocimientos de las Ciencias Naturales y de la Salud (PCCNS)', TRUE);

/*LA VARIABLE @new_examen_id RECUPERA EL ID DEL ÚLTIMO EXAMEN INSERTADO EN TypesAdmissionTests*/
SET @new_exam_id = LAST_INSERT_ID();

/*2) PARA RELACIONAR EL TIPO DE EXAMEN CON LAS DIFERENTES CARRERAS EXISTENTES PUEDES REVISAR LAS CARRERAS REGISTRADAS ACTUALMENTE CON LA
SIGUIENTE CONSULTA*/
SELECT * FROM undergraduates;

/* 3) LA SIGUIENTE CONSULTA PERMITE ASOCIAR EL EXAMEN CON LA CARRERA QUE SEA NECESARIA
SE REGISTRA EL EXAMEN RECIEN CREADO, EL ID DE LA CARRERA, EL MINIMO DE INDICE ACADEMICO 
PARA APROBAR EL EXAMEN, Y EL ESTADO DEL TIPO DE EXAMEN*/
INSERT INTO UndergraduateTypesAdmissionTests (id_type_admission_tests, id_undergraduate, required_rating, status_undergraduate_type_admission_tests)

VALUES
(@new_exam_id, 1, 70.00, TRUE),  -- Asociar examen a la carrera con ID 1
(@new_exam_id, 2, 75.00, TRUE),  -- Asociar examen a la carrera con ID 2
(@new_exam_id, 3, 80.00, TRUE),  -- Asociar examen a la carrera con ID 3
(@new_exam_id, 4, 85.00, TRUE);  -- Asociar examen a la carrera con ID 4

/*4) COMPRUEBA SI TODO SE REGISTRÓ CORRECTAMENTE*/
SELECT * FROM TypesAdmissionTests WHERE id_type_admission_tests = @new_exam_id;
SELECT * FROM UndergraduateTypesAdmissionTests WHERE id_type_admission_tests = @new_exam_id;
