<?php
include_once 'ClassSectionsDaysDAO.php';

Class  ClassSectionsDAO{
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
            printf("Conexion Fallida en ClassSectionsDAO: %s\n", $error->getMessage());
        }  
    }

    /**
     * Crea una nueva sección de clase si no existe una sección duplicada en el mismo periodo, salón y horario,
     * y si el profesor no está asignado a otra clase en el mismo horario.
     *
     * @param int    $id_class                                  ID de la clase
     * @param int    $id_dates_academic_periodicity_year        ID del periodo académico
     * @param int    $id_classroom_class_section                ID del salón y sección de clase
     * @param int    $id_academic_schedules                     ID del horario académico
     * @param int    $id_professor_class_section                ID del profesor y sección de clase
     * @param int    $numberof_spots_available_class_section    Número de espacios disponibles en la sección
     * @param bool   $status_class_section                      Estado de la sección de clase (activo/inactivo)
     *
     * @return array Resultado de la operación (mensaje de éxito o advertencia)
     * @throws Exception Si ocurre algún error en la ejecución de los procedimientos almacenados
     * @throws InvalidArgumentException Si los parámetros proporcionados no son válidos
     * 
     * @author Alejandro Moya 20211020462
     * @created 07/12/2024
     */
    public function createClassSection(
        $id_class,
        $id_dates_academic_periodicity_year,
        $id_classroom_class_section,
        $id_academic_schedules,
        $id_professor_class_section,
        $numberof_spots_available_class_section,
        $status_class_section
    ) {
        if (
            !is_int($id_dates_academic_periodicity_year) ||
            !is_int($id_classroom_class_section) ||
            !is_int($id_academic_schedules) ||
            !is_int($id_professor_class_section) ||
            !is_int($numberof_spots_available_class_section) ||
            !is_int($status_class_section)
        ) {
            throw new InvalidArgumentException("Parámetros inválidos en: createClassSection().");
        }
        try {
            $stmtOne = $this->connection->prepare("CALL GET_CLASS_SECTION_ID(?, ?, ?, ?)");
            $stmtOne->bind_param("iiii", $id_dates_academic_periodicity_year, $id_classroom_class_section, $id_academic_schedules, $id_class);
            $stmtOne->execute();
            $resultOne = $stmtOne->get_result();
            $stmtOne->close();
            
            if ($resultOne->num_rows <= 0) {
                // Verificar si el profesor ya tiene una clase en el mismo horario
                $stmtTwo = $this->connection->prepare("CALL GET_CLASS_SECTION_BY_PROFESSOR_AND_SCHEDULE(?, ?)");
                $stmtTwo->bind_param("ii", $id_professor_class_section, $id_academic_schedules);
                $stmtTwo->execute();
                $resultTwo = $stmtTwo->get_result();
                $stmtTwo->close();

                if ($resultTwo->num_rows <= 0) {
                    // Insertar la nueva sección de clase
                    $stmtInsert = $this->connection->prepare("CALL INSERT_CLASS_SECTION(?, ?, ?, ?, ?, ?, ?,@new_id)");
                    $stmtInsert->bind_param("iiiiiii", $id_class, $id_dates_academic_periodicity_year, $id_classroom_class_section, $id_academic_schedules, $id_professor_class_section, $numberof_spots_available_class_section, $status_class_section);
                    $stmtInsert->execute();
                    $result = $this->connection->query("SELECT @new_id AS new_id");
                    $newClassSectionId = $result->fetch_assoc()['new_id'];
                    return [
                        "status" => "success",
                        "message" => "Sección de clase creada exitosamente.",
                        "idClassSection"  =>$newClassSectionId
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "El catedratico ya está asignado a una clase en este horario."
                    ];
                }
            } else {
                return [
                    "status" => "warning",
                    "message" => "Traslape con secciones ya creadas."
                ];
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            return [
                "status" => "error",
                "message" => "Error en createClassSection() al procesar la solicitud: " . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene las secciones de clases asociadas a un departamento y centro regional.
     *
     * Este método ejecuta el procedimiento almacenado `GET_CLASS_SECTION_BY_DEPARTMENT_AND_REGIONAL_CENTER` 
     * y devuelve los resultados mapeados correctamente. Incluye manejo de excepciones adecuado 
     * y verifica que los parámetros de entrada sean válidos.
     *
     * @param int $department_id ID del departamento.
     * @param int $regional_center_id ID del centro regional.
     * 
     * @return array Un arreglo con las secciones de clases, cada una como un arreglo asociativo. Y el mensaje de exito.
     * 
     * @throws InvalidArgumentException Si los parámetros no son enteros.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 07/12/2024
     */
    public function getClassSectionByDepartmentHead($department_id, $regional_center_id) {
        // Verificar que los parámetros sean enteros válidos
        if (!is_int($department_id) || !is_int($regional_center_id)) {
            throw new InvalidArgumentException("Parámetros inválidos en: getClassSectionByDepartmentHead().");
        }

        // Preparar la consulta para llamar al procedimiento almacenado
        try {
            $stmt = $this->connection->prepare("CALL GET_CLASS_SECTION_BY_DEPARTMENT_AND_REGIONAL_CENTER(?, ?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("ii", $department_id, $regional_center_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GET_CLASS_SECTION_BY_DEPARTMENT_AND_REGIONAL_CENTER: " . $stmt->error);
            }
            $sections = [];
            while ($row = $result->fetch_assoc()) {
                $sections[] = [
                    'id_class_section' => $row['id_class_section'],
                    'id_class' => $row['id_class'],
                    'name_class' => $row['name_class'],
                    'id_dates_academic_periodicity_year' => $row['id_dates_academic_periodicity_year'],
                    'classroom_name' => $row['classroom_name'],
                    'numberof_spots_available_class_section' => $row['numberof_spots_available_class_section'],
                    'professor_name' => $row['professor_name'],
                    'start_timeof_classes' => $row['start_timeof_classes'],
                    'end_timeof_classes' => $row['end_timeof_classes']
                ];
            }
            $stmt->close(); 
            return [
                "status" => "success",
                "data" => $sections
            ];
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_CLASS_SECTION_BY_DEPARTMENT_AND_REGIONAL_CENTER: " . $e->getMessage());
        }
    }


    /**
     * Actualiza el número de spots disponibles en una sección de clase existente.
     * 
     * Este método llama al procedimiento almacenado `UPDATE_SPOTS_AVAILABLE` para actualizar
     * el número de spots disponibles en una sección de clase, identificada por su ID.
     *
     * @param int $idClassSection ID de la sección de clase cuya cantidad de spots se actualizará.
     * @param int $newSpotsNumber Nuevo número de spots disponibles en la sección de clase.
     *
     * @return array Resultado de la operación (mensaje de éxito o error).
     * @throws Exception Si ocurre algún error en la ejecución del procedimiento almacenado.
     * @throws InvalidArgumentException Si los parámetros proporcionados no son válidos.
     * 
     * @author Alejandro Moya 20211020462
     * @created 07/12/2024
     */
    public function updateSpotsAvailableClassSection($idClassSection, $newSpotsNumber) {
        if (!is_int($idClassSection) || !is_int($newSpotsNumber)) {
            throw new InvalidArgumentException("Parámetros inválidos en: updateSpotsAvailableClassSection().");
        }
    
        try {
            $stmt = $this->connection->prepare("CALL UPDATE_SPOTS_AVAILABLE(?, ?)");
            $stmt->bind_param("ii", $idClassSection, $newSpotsNumber);
            $stmt->execute();
            $stmt->close();
            return [
                "status" => "success",
                "message" => "Número de spots actualizado exitosamente."
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Error en updateSpotsAvailableClassSection() al procesar la solicitud: " . $e->getMessage()
            ];
        }
    }

    /**
     * Elimina una sección de clase si cumple con las siguientes condiciones:
     * 1. Posee menos de 15 estudiantes matriculados.
     * 2. Ninguno de los estudiantes matriculados está por egresar (progreso > 85%).
     * 
     * Además, registra la justificación en el historial de secciones eliminadas y desactiva
     * tanto la matrícula de los estudiantes como la sección de la clase.
     *
     * @param int    $idClassSection ID de la sección de clase.
     * @param int    $department_id  ID del departamento responsable.
     * @param string $justification  Justificación para eliminar la sección.
     *
     * @return array Resultado de la operación (mensaje de éxito o advertencia).
     * @throws Exception Si ocurre un error durante la ejecución de procedimientos almacenados.
     * @throws InvalidArgumentException Si los parámetros proporcionados no son válidos.
     * 
     * @author Alejandro Moya
     * @created 07/12/2024
     */
    public function deleteClassSectionsByDepartmentHead($idClassSection, $department_id, $justification, $usernameProfessor) {
        if (!is_int($idClassSection) || !is_int($department_id) || !is_int($usernameProfessor) || !is_string($justification) || empty($justification)) {
            throw new InvalidArgumentException("Parámetros inválidos en: deleteClassSectionsByDepartmentHead().");
        }
        try {
            // Obtener los estudiantes matriculados en la sección
            $stmtOne = $this->connection->prepare("CALL GET_ENROLLMENT_CLASS_SECTION_IDS(?)");
            $stmtOne->bind_param("i", $idClassSection);
            $stmtOne->execute();
            $resultOne = $stmtOne->get_result();
            $stmtOne->close();

            // Verificar si hay menos de 15 estudiantes matriculados
            if ($resultOne->num_rows < 15) {
                $canDelete = true;
                while ($student = $resultOne->fetch_assoc()) {
                    $idStudent = $student['id_student'];

                    // Verificar el progreso académico de cada estudiante
                    $stmtTwo = $this->connection->prepare("CALL GET_UNDERGRADUATE_PROGRESS(?)");
                    $stmtTwo->bind_param("i", $idStudent);
                    $stmtTwo->execute();
                    $resultTwo = $stmtTwo->get_result();
                    $stmtTwo->close();

                    $progressData = $resultTwo->fetch_assoc();
                    if ($progressData['progressPercentage'] > 85) {
                        $canDelete = false;
                        break;
                    }
                }

                if ($canDelete) {
                    //obtener el id del jefe del departamento
                    $stmtGET = $this->connection->prepare("CALL GET_DEPARTMENTHEAD_BY_USERNAMEPROFESSOR_IDDEPARTMENT(?,?)");
                    $stmtGET->bind_param("ii", $usernameProfessor, $department_id);
                    $stmtGET->execute();
                    $resultGET =  $stmtGET->get_result();
                    $stmtGET->close();
                    $resultGET = $resultGET->fetch_assoc();
                    $idDepartmentHead = $resultGET['id_department_head'];
                    echo json_encode($idDepartmentHead);

                    // Registrar la eliminación de la sección
                    $stmtInsert = $this->connection->prepare("CALL INSERT_CLASS_SECTION_CANCELLED(?, ?, ?)");
                    $stmtInsert->bind_param("iis", $idClassSection, $idDepartmentHead, $justification);
                    $stmtInsert->execute();
                    $stmtInsert->close();

                    // Desactivar la matrícula de cada estudiante para esa clase
                    $stmtUpdate = $this->connection->prepare("CALL UPDATE_ENROLLMENT_STATUS(?)");
                    $stmtUpdate->bind_param("i", $idClassSection);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();

                    // Desactivar la seccion de clase. Su estado en false. 
                    $stmtUpdateOne = $this->connection->prepare("CALL UPDATE_CLASS_SECTION_STATUS(?)");
                    $stmtUpdateOne->bind_param("i", $idClassSection);
                    $stmtUpdateOne->execute();
                    $stmtUpdateOne->close();

                    // Desactivar la relaciones entre la seccion de la clase y cada día que se imparte. Su estado en false. 
                    $stmtUpdateTwo = $this->connection->prepare("CALL UPDATE_CLASS_SECTION_DAYS_STATUS(?)");
                    $stmtUpdateTwo->bind_param("i", $idClassSection);
                    $stmtUpdateTwo->execute();
                    $stmtUpdateTwo->close();

                     // Desactivar la seccion de clase para el profesor.  
                     $stmtUpdateTree = $this->connection->prepare("CALL UPDATE_CLASS_SECTION_PROFESSOR_STATUS(?)");
                     $stmtUpdateTree->bind_param("i", $idClassSection);
                     $stmtUpdateTree->execute();
                     $stmtUpdateTree->close();

                    return [
                        "status" => "success",
                        "message" => "Sección de clase eliminada correctamente."
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "Se ha encontrado un estudiante por egresar. No es posible eliminar la sección."
                    ];
                }
            } else {
                return [
                    "status" => "warning",
                    "message" => "La sección de la clase ya posee más de 15 estudiantes matriculados. No es posible eliminar la sección."
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Error en deleteClassSectionsByDepartmentHead() al procesar la solicitud: " . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene las secciones de clases disponibles para la clase seleccionada por  un estudiante.
     *
     * Este método ejecuta el procedimiento almacenado `GET_ACTIVE_CLASS_SECTIONS_FOR_STUDENT` 
     * y devuelve los resultados mapeados correctamente. Incluye manejo de excepciones adecuado 
     * y verifica que los parámetros de entrada sean válidos.
     *
     * @param string $student_id ID del estudiante.
     * @param int $class_id ID de la clase.
     * 
     * @return array Un arreglo con las secciones de clases, cada una como un arreglo asociativo. Y el mensaje de éxito.
     * 
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function getClassSectionsForStudent($student_id, $class_id) {
        if (!is_string($student_id) || !is_int($class_id)) {
            throw new InvalidArgumentException("Parámetros inválidos en: getClassSectionsForStudent().");
        }
        try {
            $stmtOne = $this->connection->prepare("CALL VERYFY_STUDENT_PREREQUISITES(?, ?)");
            if ($stmtOne === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmtOne->bind_param("is", $class_id, $student_id);
            $stmtOne->execute();
            $result = $stmtOne->get_result();
            if ($result) {
                $row = $result->fetch_assoc();
                $stmtOne->close();
                if ($row['message'] == 'El estudiante cumple con los requisitos.') {
                    try {
                        $stmt = $this->connection->prepare("CALL GET_ACTIVE_CLASS_SECTIONS_FOR_STUDENT(?, ?)");
                        if ($stmt === false) {
                            throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
                        }
                        $stmt->bind_param("si", $student_id, $class_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result === false) {
                            throw new mysqli_sql_exception("Error al obtener el resultado en GET_ACTIVE_CLASS_SECTIONS_FOR_STUDENT: " . $stmt->error);
                        }
                        if ($result === null || $result->num_rows === 0) {
                            return [
                                "status" => "warning",
                                "message" => "No hay secciones Para Esta clase, actualmente."
                            ];
                            $stmt->close();
                            exit; 
                        }
                        $sections = [];
                        
                        while ($row = $result->fetch_assoc()) {
                            //
                            $ClassSectionsDaysDAO = new  ClassSectionsDaysDAO();
                            $resultDays = $ClassSectionsDaysDAO -> getClassSectionDays($row['id_class_section']);
                            $sections[] = [
                                'id_class' => $row['clasId'],
                                'clase' => $row['class_name'], 
                                'id_class_section' => $row['id_class_section'],
                                'Dias' => $resultDays['days'],
                                'HI' => $row['start_time'], 
                                'HF' => $row['end_time'], 
                                'Aula' => $row['classroom_name'], 
                                'Docente' => $row['professor_names']
                            ];
                        }            
                        $stmt->close(); 
                        return [
                            "status" => "success",
                            "data" => $sections
                        ];
                    } catch (mysqli_sql_exception $e) {
                        throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_ACTIVE_CLASS_SECTIONS_FOR_STUDENT: " . $e->getMessage());
                    }
                    
                }else{
                    return [
                        "status" => "warning",
                        "message" => "No has aprobado todas las clases requeridas para esta clase."
                    ];
                }
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado VERYFY_STUDENT_PREREQUISITES: " . $e->getMessage());
        }
    }

    /**
     * Obtiene el número de espacios disponibles en una sección de clase.
     *
     * Este método llama al procedimiento almacenado `GetAvailableSpots` para obtener la información.
     * Valida los parámetros de entrada y maneja posibles errores durante la ejecución de la consulta.
     *
     * @param int $classSectionId ID de la sección de clase.
     * 
     * @return array Respuesta con información sobre el éxito o fallo de la operación.
     * - 'success' (bool): Indica si la operación fue exitosa.
     * - 'availableSpots' (int|null): Número de espacios disponibles (o null si no se encuentra la sección).
     * - 'message' (string): Mensaje descriptivo del resultado de la operación.
     * 
     * @throws InvalidArgumentException Si el parámetro proporcionado no es válido.
     * @throws Exception Si ocurre un error en la conexión o en la ejecución del procedimiento almacenado.
     * 
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function getAvailableSpots(int $classSectionId): array{
        if (!is_int($classSectionId)) {
            throw new InvalidArgumentException("Parámetros inválidos en: getAvailableSpots().");
        }
        try {
            $query = "CALL GET_AVAILABLE_SPOTS(?, @availableSpots);";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . $this->connection->error);
            }
            $stmt->bind_param('i', $classSectionId);

            if (!$stmt->execute()) {
                throw new Exception('Error al ejecutar el procedimiento almacenado: ' . $stmt->error);
            }
            $stmt->close();
            $result = $this->connection->query("SELECT @availableSpots AS availableSpots;");
            if ($result && $row = $result->fetch_assoc()) {
                return [
                    'success' => true,
                    'availableSpots' => (int) $row['availableSpots'],
                    'message' => 'Número de espacios disponibles obtenido exitosamente.'
                ];
            }
            return [
                'success' => false,
                'availableSpots' => null,
                'message' => 'No se pudo obtener el número de espacios disponibles.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'availableSpots' => null,
                'message' => 'Error al obtener los espacios disponibles: ' . $e->getMessage()
            ];
        }
    }



    // Método para cerrar la conexión (opcional), 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>


