<?php
include_once '../../../../src/DAO/util/mail.php';

$connection = new mysqli('localhost', 'root', '12345', 'unah_registration');

$mail = new mail();

echo $mail->sendRatings($connection, 'exam_results', 1000);

?>