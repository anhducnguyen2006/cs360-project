<?php
$server = 'cray.cs.gettysburg.edu';
$dbase = 'f26_ajn';
$user = 'nguynh01';
$pass = 'nguynh01';

$dsn = "mysql:host=$server;dbname=$dbase;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $db = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $db = null;
}