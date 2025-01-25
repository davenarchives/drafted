<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '####'; 
$dbname = '####'; 
$user = '#####'; 
$pass = '#####'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
