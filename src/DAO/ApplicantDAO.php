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


    // Método para insertar un nuevo aspirante
    public function insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status) {
        // Preparar la consulta SQL de inserción
        $query = "INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, status_applicant) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("issssssssi", $id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                echo json_encode(["message" => "Se ha cargado la información correctamente."]);
            } else {
                echo json_encode(["error" => "Error en la carga: " . $stmt->error]);
            }

            // Cerramos el statement
            $stmt->close();
        } else {
            echo json_encode(["error" => "Error en la preparación de la consulta: " . $this->connection->error]);
        }
    }

    
    public function insertApplication($id_admission_process, $id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant, $status_application )  {
        // Preparar la consulta SQL de inserción
        $query = "INSERT INTO Applications (id_admission_process, id_applicant, id_aplicant_type, secondary_certificate_applicant, idregional_center, regionalcenter_admissiontest_applicant, intendedprimary_undergraduate_applicant, intendedsecondary_undergraduate_applicant, status_application) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("iiisiiiii", $id_admission_process, $id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant, $status_application);

            // Ejecutamos la consulta
            $stmt->execute();
            /*if ( ) {
                echo json_encode(["message" => "Se ha cargado la solicitud correctamente."]);
            } else {
                echo json_encode(["error" => "Error en la carga de la solicitud: " . $stmt->error]);
            }*/

            // Cerramos el statement
            $stmt->close();
        } else {
            echo json_encode(["error" => "Error en la preparación de la consulta: " . $this->connection->error]);
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
         
            error_log("Error en la consulta SP_ASPIRANTS_DATA_VIEW: " . $this->connection->error);
        }
    
        // Retornamos el array, sea con datos o vacío
        return $applicationsData;
    }

    // Método para cerrar la conexión 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}