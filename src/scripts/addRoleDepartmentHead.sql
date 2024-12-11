USE unah_registration;

/*1) IDENTIFICA EL ID DEL DEPARTAMENTO AL QUE QUIERES ASIGNAR EL DOCENTE COMO JEFE DE DEPARTAMENTO*/

select * from `Departments`;

/*2) IDENTIFICA EL ID DEL  DOCENTE QUE SERÁ NUEVO  JEFE DE DEPARTAMENTO*/
select * from `Professors` where email_professor = 'fernandoespinalguevara@gmail.com';

/*3) DESACTIVA EL STATUS DEL ANTERIOR JEFE DE DEPARTAMENTO SI EXISTE*/
select * from `DepartmentHead`;

UPDATE `DepartmentHead` set status_department_head = 0 where id_department = 9;
--DESACTIVA SU HORARIO TAMBIÉN
update `DepartmentHeadWorkingHours` set status_department_head_working_hours=0 where id_department_head = 1;

--DESACTIVA SU ROL DE JEFE DE DEPARTAMENTO
update  `RolesUsersProfessor` set status_role_professor=0 where id_user_professor =1  and id_role_professor =13;
/*4) SELECCIONA LAS OBLIGACIONES DEL JEFE DE DEPARTAMENTO*/
    
select * from `DepartmentHeadObligations`;

/*5) INSERTA EL NUEVO JEFE DE DEPARTAMENTO*/
insert into `DepartmentHead` (id_professor, id_department,id_department_head_obligations,status_department_head) VALUES(10,9,1,1);


/*6) BUSCA EL HORARIO A ASIGNAR AL JEFE DE DEPARTAMENTO*/
select * from `WorkingHours`;

/*7) BUSCA EL ID DEL JEFE DE DEPARTAMENTO RENCIEN CREADO*/
select * from `DepartmentHead` ORDER BY id_department_head DESC LIMIT 1;


/*8) CREA SU HORARIO*/
insert into `DepartmentHeadWorkingHours` (id_department_head,id_working_hour,status_department_head_working_hours) values (2,1,1);

/*9) ASIGNA LOS ROLE DE JEFE DE DEPARTAMENTO AL DOCENTE (ID 13)*/

select * from Roles;
--OBTEN EL ID DEL USUARIO DEL PROFESOR
select * from `UsersProfessors` inner join `Professors` on `UsersProfessors`.username_user_professor = `Professors`.id_professor where id_professor = 10;
INSERT INTO `RolesUsersProfessor` (id_user_professor,id_role_professor, status_role_professor) values(2,13, 1);

