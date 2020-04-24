<?php
//assign variables for connection
$dsn = 'mysql:host=localhost;dbname=wrxvm3';
$username = 'root';
$password = '';
	//try connecting
    try {
        $db = @ new PDO($dsn, $username, $password);
        } //error handling
        catch (PDOException $e) {
        echo 'DSN Connection Failed: ' . $e->getMessage();
        exit();
    }
?>