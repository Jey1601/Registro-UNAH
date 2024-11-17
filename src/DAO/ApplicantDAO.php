<?php


Class ApplicantDAO{


    public function __construct(string $host, string $username, string $password, string $dbName)
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($host, $username, $password, $dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

       
    }

     // Método para obtener los aspirantes
     public function getApplicants() {
        $applicants = [];
    
        // Ejecutamos la consulta
        $result = $this->connection->query("SELECT * FROM Applicants;");

        // Verificamos si la consulta fue exitosa
        if ($result) {
            // Recorremos los resultados y los agregamos al array $regionalCenters
            while ($row = $result->fetch_assoc()) {
                // Añadimos cada fila al array
                $applicants[] = $row;
            }
        } else {
            // Si hubo un error con la consulta
            printf("Error in query: %s\n", $this->connection->error);
        }

        return $applicants;
    }

    

    public function createInscription($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant) {
        // Iniciar una transacción
        $this->connection->begin_transaction();
    
        try {
            // Verificamos si el aspirante ya tenía información guardada anteriormente
            if ($this->isCreated($id_applicant)) {
                // Si está creado, debemos verificar que no tenga una solicitud activa
                if ($this->hasActiveApplication($id_applicant)) {
                    // Si tiene una solicitud activa no le permitimos actualizar información ni crear una nueva
                    echo json_encode(["message" => "Usted ya está inscrito en el proceso actual"]);
                } else {
                    // Si no tiene una solicitud activa actualizamos su información
                    if (!$this->updateApplicant($id_applicant, $email, $phone_number, $address, $status)) {
                        echo json_encode(["error" => "Ha ocurrido un error en la actualización de la información"]);
                  
                    }
                    // Creamos la nueva solicitud
                    if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {
                        echo json_encode(["error" => "Ha ocurrido un error al crear la solicitud"]);
                    }
                }
            } else {
                // Si no está creado hacemos el insert
                if (!$this->insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status)) {
  
                    echo json_encode(["error" => "Ha ocurrido un error al guardar la información del aspirante"]);
                }
                // Creamos la nueva solicitud
                if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {
         
                    echo json_encode(["error" => "Ha ocurrido un error al crear la solicitud"]);
                }
            }
    
            // Si todo fue exitoso, confirmamos la transacción
            $this->connection->commit();
            echo json_encode(["message" => "Inscripción creada exitosamente"]);
        } catch (Exception $e) {
            // En caso de error, revertimos la transacción
            $this->connection->rollback();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }



    // Método para insertar un nuevo aspirante
    Private function insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status) {
        
       
        // Preparar la consulta SQL de inserción
        $query = "INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, status_applicant) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("issssssssi", $id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
               return true;
            } else {
                return false;
            }

            // Cerramos el statement
            $stmt->close();
        } else {
            echo json_encode(["error" => "Error en la preparación de la consulta insertApplicant: " . $this->connection->error]);
        }
    }



    Private function updateApplicant( $id_applicant, $email, $phone_number, $address, $status){
        // Preparar la consulta SQL de  Actualización, solo actualizamos campos email, phone, y address 
        $query = "UPDATE Applicants 
                  SET  email_applicant = ?,
                       phone_number_applicant = ?, 
                       address_applicant = ?, 
                       status_applicant = ?)
                  WHERE id_applicant = ?";
        
        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("sssis", $email, $phone_number, $address, $status, $id_applicant);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
               return true;
            } else {
               return false;
            }

            // Cerramos el statement
            $stmt->close();
        } else {
            echo json_encode(["error" => "Error en la preparación de la consulta updateApplicant: " . $this->connection->error]);
        }

    }    


    private function createApplication( $id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant) {
        // Extraer el proceso de admisión activo
        $id_admission_process = $this->getAdmissionProcess();
        if (!$id_admission_process) {
            echo json_encode(["error" => "No hay un proceso de admisión activo." . $this->connection->error]);
   
            return false; // No se puede continuar sin un proceso activo
        }
    
        $status_application = 1; // Nueva solicitud, siempre activa
    
        // Consulta de inserción
        $query = "INSERT INTO Applications 
                  (id_admission_process, id_applicant, id_aplicant_type, secondary_certificate_applicant, idregional_center, regionalcenter_admissiontest_applicant, intendedprimary_undergraduate_applicant, intendedsecondary_undergraduate_applicant, status_application) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Preparar la consulta
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular parámetros
            $stmt->bind_param(
                "iiisiiiii", 
                $id_admission_process, 
                $id_applicant, 
                $id_aplicant_type, 
                $secondary_certificate_applicant, 
                $id_regional_center, 
                $regionalcenter_admissiontest_applicant, 
                $intendedprimary_undergraduate_applicant, 
                $intendedsecondary_undergraduate_applicant, 
                $status_application
            );
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Éxito
            } else {
                // Registrar error en la ejecución
                echo json_encode(["error" => "Error en la creación de la solicitud: " . $stmt->error]);
              
                $stmt->close();
                return false;
            }
        } else {
            // Registrar error en la preparación
           
            echo json_encode(["error" => "Error en la preparación de la consulta createApplication: " . $this->connection->error]);     
            return false;
        }
    }

    private function getAdmissionProcess() {
        $query = "SELECT id_admission_process FROM AdmissionProcess WHERE current_status_admission_process = 1";
    
        // Ejecutar la consulta
        $result = $this->connection->query($query);
    
        if ($result) {
            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                //Se devuelve el primer resultado
                $row = $result->fetch_assoc();
                return $row['id_admission_process'];
            } else {
                // No se encontraron procesos de admisión activos
                return null;
            }
        } else {
            // Registrar error y devolver null
            echo json_encode(["error" => "Error en la consulta getAdmissionProcess: " . $this->connection->error]);
     
            return null;
        }
    }



    // Método para obtener los información de los aspirantes que será visa por el administrador de admisiones
    public function viewData() {
        $applicationsData = [];
        
     
        if ($result = $this->connection->query("CALL SP_ASPIRANTS_DATA_VIEW();")) {
            // Si la consulta fue exitosa
            while ($row = $result->fetch_assoc()) {
                $applicationsData[] = $row;
            }
        } else {
            echo json_encode(["error" => "Error en la consulta SP_ASPIRANTS_DATA_VIEW: " . $this->connection->error]);
      
        }
    
        // Retornamos el array, sea con datos o vacío
        return $applicationsData;
    }


    private function isCreated($id_applicant){
        $query = "SELECT id_applicant FROM Applicants WHERE id_applicant = ?";
    
        // Preparar la consulta para evitar inyecciones SQL
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param("s", $id_applicant);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();
    
                // Verificar si hay filas
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // El registro existe
                } else {
                    $stmt->close();
                    return false; // El registro no existe
                }
            } else {
                // Registrar el error de ejecución
                echo json_encode(["error" => "Error al ejecutar la consulta isCreated:  " . $stmt->error]);
            
                $stmt->close();
                return false;
            }
        } else {
            // Registrar el error al preparar la consulta
            echo json_encode(["error" => "Error al preparar la consulta isCreated: "  . $this->connection->error]);
            

            return false;
        }
    }

    private function hasActiveApplication($id_applicant) {
        $query = "SELECT * FROM Applications WHERE id_applicant = ? AND status_application = 1";
    
        // Preparar la consulta para evitar inyecciones SQL
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param("s", $id_applicant);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();
    
                // Verificar si hay filas
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // Existe una aplicación activa
                } else {
                    $stmt->close();
                    return false; // No hay aplicaciones activas
                }
            } else {
                // Registrar el error de ejecución
               echo json_encode(["error" => "Error al ejecutar la consulta hasActiveApplication:"   . $stmt->error]);
                $stmt->close();
                return false;
            }
        } else {
            // Registrar el error al preparar la consulta
           // echo json_encode(["error" => "Error al preparar la consulta hasActiveApplication: "   . $this->connection->error]);

            return false;
        }
    }
    

    // Método para cerrar la conexión 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}