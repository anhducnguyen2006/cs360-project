<?php  
$server = "cray.cs.gettysburg.edu"; 
$dbase  = "f26_nguyan06"; 
$user   = "nguyan06"; 
$pass   = "nguyan06"; 
$dsn    = "mysql:host=$server;dbname=$dbase";         // data source name 
$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]; 

try { 
    $db = new PDO($dsn, $user, $pass, $options); 
    print "Successfully connected to database\n"; 
} 
catch(PDOException $e) { 
    error_log($e->getMessage()); 
    printf("ERROR connecting to database: %s\n", $e->getMessage());
    exit(); 
}
