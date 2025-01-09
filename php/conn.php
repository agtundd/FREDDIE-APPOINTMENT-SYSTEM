<?php
$host = 'localhost';
$dbname = 'mrfreddie_repairshop';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
