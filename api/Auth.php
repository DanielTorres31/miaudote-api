<?php
require_once "../controller/AuthController.php";
header("Content-type: application/json");

$AuthController = new AuthController();

$acao = $_GET["acao"];
if($acao == "CriarSessao"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    echo json_encode($AuthController->CriarSessao($email, $senha));
}

if($acao == "ChecarSessao"){
    echo json_encode($AuthController->ChecarSessao());
}

if($acao == "EncerrarSessao"){
    echo json_encode($AuthController->EncerrarSessao());
}