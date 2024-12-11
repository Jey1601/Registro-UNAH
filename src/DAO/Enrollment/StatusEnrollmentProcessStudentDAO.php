<?php

include_once "StudentGradesAveragesDAO.php";
include_once "DatesEnrollmentProcessDAO.php";
include_once "TypesEnrollmentConditionsDAO.php";
Class StatusEnrollmentProcessStudentDAO{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;
    
   
    public function __construct()
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }  
    }

    /**
     * Verifica si un estudiante cumple con los requisitos para matricularse en el día de hoy.
     *
     * Este método valida si el estudiante tiene un promedio adecuado para proceder con la matrícula,
     * según las condiciones definidas en el proceso de matrícula. Utiliza el promedio general o el promedio
     * por período, según lo especificado por las condiciones de matrícula. También valida los parámetros
     * de entrada y maneja errores potenciales.
     *
     * @param string $idStudent ID del estudiante que se quiere verificar.
     * 
     * @return array Respuesta con la información sobre el estado de la matrícula.
     * - 'status' (string): El estado de la operación:
     *    - 'success' si el estudiante puede matricularse.
     *    - 'warning' si el estudiante no cumple con los requisitos y no puede matricularse.
     * - 'message' (string, opcional): Mensaje descriptivo en caso de advertencia.
     * 
     * @throws InvalidArgumentException Si el parámetro de ID de estudiante no es válido.
     * @throws Exception Si ocurre un error en la conexión con la base de datos o en la ejecución de las consultas.
     * 
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function verifyEnrollmentStudent($idStudent){
        if (!is_string($idStudent)) {
            throw new InvalidArgumentException("Parametro de Id Usuario Estudiante inválido en: verifyEnrollmentStudent().");
            if (!$date || $date->format('Y-m-d') !== $actualDate) {
                throw new InvalidArgumentException("Parametro de fecha inválido en: verifyEnrollmentStudent().");
            }
        }
        $averagesStudent = new StudentGradesAveragesDAO();
        $resultAveragesStudent = $averagesStudent->getStudentGradesAverages($idStudent);

        if($resultAveragesStudent['status']=== "success"){
            $DatesEnrollmentProcessDAO = new DatesEnrollmentProcessDAO();
            $datesEnrollment = $DatesEnrollmentProcessDAO->getEnrollmentProcessByDate();
            if($datesEnrollment['status']=== "success"){
                $dataDatesEnrollment = $datesEnrollment['data'][0];
                $idTypeEnrollmentConditions = $dataDatesEnrollment['id_type_enrollment_conditions'];
                $TypesEnrollmentConditionsDAO = new TypesEnrollmentConditionsDAO();
                $typeEnrollmentCondition = $TypesEnrollmentConditionsDAO->getEnrollmentConditionDetailsById($idTypeEnrollmentConditions);
                if($typeEnrollmentCondition['status']=== "success"){
                    $dataTypeEnrollmentCondition = $typeEnrollmentCondition['data'][0];
                    $maximunType = $dataTypeEnrollmentCondition['id_type_enrollment_conditions'];
                    $StudentAverages = $resultAveragesStudent['data'][0];
                    if($dataTypeEnrollmentCondition['status_student_global_average'] == 1) {
                        $StudentAverageGlobal = $StudentAverages['global_grade_average_student'];
                        if($StudentAverageGlobal >= $dataTypeEnrollmentCondition['minimum_student_global_average'] && $StudentAverageGlobal <= $dataTypeEnrollmentCondition['maximum_student_global_average']){
                            return [
                                "status"  => "success"
                            ];
                        }else{
                            return [
                                "status"  => "warning",
                                "message"  => "No puede matricular el dia de hoy"
                            ];
                        }
                    }else{
                        $StudentAveragePeriod = $StudentAverages['period_grade_average_student'];
                        if($StudentAveragePeriod >= $dataTypeEnrollmentCondition['minimum_student_period_average'] && $StudentAveragePeriod <= $dataTypeEnrollmentCondition['maximum_student_period_average']){
                            return [
                                "status"  => "success"
                            ];
                        }else{
                            return [
                                "status"  => "warning",
                                "message"  => "No puede matricular el dia de hoy"
                            ];
                        }
                    }
                }else{
                    return $typeEnrollmentCondition;
                }
            }else{
                return $datesEnrollment;
            }
        }else{
            return $resultAveragesStudent; 
        }
    }
    
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}