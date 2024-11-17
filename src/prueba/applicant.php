<?php
include_once "../Registro-UNAH-ladingpage/src/util/jwt.php";

class ApplicantDAO {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbName = 'Registro_UNAH';
    private $connection;

    public function __construct()
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

        return $this->connection;
    }

    public function validateApplicant(string $numID, int $numReq) {
        if (isset($numID) && isset($numReq)) {
            //Busca al aspirante
            $query = "SELECT id_applicant, id_admission_applicantion_number FROM Applicants INNER JOIN Applications ON Applicants.id_applicant = Applications.id_applicant WHERE Applicants.id_applicant = ? AND Applications.id_applicantion_number = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('si', $numID, $numReq);
            $stmt->execute();
            $result = $stmt->get_result(); //Obtiene resultado de la consulta a la BD

            if($result->num_rows > 0) { //Verifica que la consulta no esté vacía, si lo está es que el aspirante no está registrado
                $payload = [
                    'applicantID' => $numID,
                    'numAdmissionRequest' => $numReq
                ];
                $newToken = JWT::generateToken($payload);
                
                $response = [
                    'httpCode' => http_response_code(200),
                    'message' => 'Credential validation successful.',
                    'token' => $newToken
                ];
            } else {
                $response = [
                    'httpCode' => http_response_code(401),
                    'message' => 'User and/or request number not found.',
                    'token' => null
                ];
            }

        } else {
            $response = [
                'httpCode' => http_response_code(401),
                'message' => 'Credentials not received.',
                'token' => null
            ];
        }

        return $response;
    }

    public function getApplicantsInfoCSV(){
        $query = "CALL SP_APPLICANT_DATA();";
        $applicants = $this->connection->execute_query($query);

        $csvHeaders = ["id_applicant", "first_name_applicant", "second_name_applicant", "third_name_applicant", "first_last_name_applicant", "second_last_name_applicant", "email_applicant", "phone_number_applicant", "status_applicant"];

        //Crear un stream en memoria para el archivo CSV
        $csvFile = fopen('php://temp', '+r');

        //Escribir las cabeceras del CSV
        fputcsv($csvFile, $csvHeaders);

        //Llenado de datos del CSV
        foreach ($applicants as $applicant) {
            foreach ($csvHeaders as $header) {
                fputcsv($csvFile, $applicant["$header"]);
            }
        }

        //Volver al inicio del archivo para que pueda ser enviado
        rewind($csvFile);

        //Leer el contenido del archivo CSV en memoria
        $csvContent =  stream_get_contents($csvFile);

        //Cerrar el stream
        fclose($csvFile);

        return $csvContent;
    }

}
?>