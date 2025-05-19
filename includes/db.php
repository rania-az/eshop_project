<?php
$mysqli = new mysqli('localhost', 'root', '', 'student_store');
if ($mysqli->connect_error) {
    die('Σφάλμα σύνδεσης: ' . $mysqli->connect_error);
}
?>