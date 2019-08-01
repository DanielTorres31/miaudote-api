<?php
require_once "../controller/MensagemController.php";
header("Content-type: application/json");

$MensagemController = new MensagemController();

$acao = $_GET["acao"];
if($acao == "EnviarMensagem"){
    $NomUsuario = "Henrique";
    $Email = "henrique@gmail.com";
    $MensagemUsuario = "teste mensagem";
    
    echo json_encode($MensagemController->EnviarMensagem($NomUsuario, $Email, $MensagemUsuario));
}