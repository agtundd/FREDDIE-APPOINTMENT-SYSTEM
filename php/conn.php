<?php
$host = 'localhost';
$dbname = 'freddie_repairshop';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
