<?php
function generateAccountNumber($connection) {
    $year = date("Y");
    $complement = rand(1000000, 9999999);
    $accountNumber = $year.$complement;

    $querySelect = "SELECT id_student FROM Students;";
    $stmtSelect = $connection->query($querySelect);

    while ($row = $stmtSelect->fetch_array(MYSQLI_ASSOC)) {
        if ($accountNumber == $row) {
            $accountNumber = generateAccountNumber($connection);
            break;
        }
    }

    return $accountNumber;
}
?>