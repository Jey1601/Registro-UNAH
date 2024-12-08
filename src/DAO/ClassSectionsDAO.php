<?php


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
                    $stmtInsert = $this->connection->prepare("CALL INSERT_CLASS_SECTION(?, ?, ?, ?, ?, ?, ?)");
                    $stmtInsert->bind_param("iiiiiii", $id_class, $id_dates_academic_periodicity_year, $id_classroom_class_section, $id_academic_schedules, $id_professor_class_section, $numberof_spots_available_class_section, $status_class_section);
                    $stmtInsert->execute();
                    $newClassSectionId = $this->connection->insert_id;
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
    
    
    // Método para cerrar la conexión (opcional), 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>