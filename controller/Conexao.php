<?php

require_once "../utils/logger.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "miaudote";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
    }
catch(PDOException $e)
    {
        logApp('Erro ao conectar ao banco de dados', $ex);
        echo "Connection failed: " . $e->getMessage();
    }
