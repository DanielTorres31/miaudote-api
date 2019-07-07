<?php
require_once "../controller/Mensagem.php";
header("Content-type: application/json");

$Mensagem = new Mensagem();

$acao = $_GET["acao"];
if($acao == "EnviarMensagem"){
    $NomUsuario = "Henrique";
    $Email = "henrique@gmail.com";
    $MensagemUsuario = "teste mensagem";
    
    echo json_encode($Mensagem->EnviarMensagem($NomUsuario, $Email, $MensagemUsuario));
}