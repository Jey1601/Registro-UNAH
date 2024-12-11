USE unah_registration;

/*1) IDENTIFICA EL ID DE LA CARRERA AL QUE QUIERES ASIGNAR EL DOCENTE COMO COORDINADOR*/

select * from `Undergraduates`;

/*2) IDENTIFICA EL ID DEL  DOCENTE QUE SERÁ NUEVO  COORDINADOR*/
select * from `Professors` where email_professor = 'jfeg1601@gmail.com';

/*3) DESACTIVA EL STATUS DEL ANTERIOR COORDINADOR SI EXISTE*/
select * from `AcademicCoordinator` where id_undergraduate = 9;

UPDATE `AcademicCoordinator` set status_academic_coordinator = 0 where id_undergraduate = 9;


--DESACTIVA SU HORARIO TAMBIÉN
update `AcademicCoordinatorWorkingHours` set status_academic_coordinator_working_hours=0 where id_academic_coordinator = 1;

--DESACTIVA SU ROL DE COORDINADOR (16 ROLE DE COORDINADOR)
update  `RolesUsersProfessor` set status_role_professor=0 where id_user_professor =1  and id_role_professor =16;
/*4) SELECCIONA LAS OBLIGACIONES DEL COORDINADOR*/
select * from `AcademicCoordinatorObligations`;


/*5) INSERTA EL NUEVO COORDINADOR*/
insert into `AcademicCoordinator` (id_professor,id_undergraduate,id_academic_coordinator_obligations,status_academic_coordinator) VALUES(11,2,1,1);


/*6) BUSCA EL HORARIO A ASIGNAR AL COORDINADOR*/
select * from `WorkingHours`;

/*7) BUSCA EL ID DEL COORDINADOR RENCIEN CREADO*/
select * from `AcademicCoordinator` ORDER BY id_academic_coordinator DESC LIMIT 1;


/*8) CREA SU HORARIO*/
insert into `AcademicCoordinatorWorkingHours` (id_academic_coordinator,id_working_hour,status_academic_coordinator_working_hours) values (2,1,1);

/*9) ASIGNA LOS ROLE DE COORDINADOR AL DOCENTE (ID 13)*/

select * from Roles;
--OBTEN EL ID DEL USUARIO DEL PROFESOR
select * from `UsersProfessors` inner join `Professors` on `UsersProfessors`.username_user_professor = `Professors`.id_professor where id_professor = 11;
INSERT INTO `RolesUsersProfessor` (id_user_professor,id_role_professor, status_role_professor) values(3,16, 1);

