<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}
?>
