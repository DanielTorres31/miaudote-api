<?php
require_once "../controller/AuthController.php";
require_once "../utils/routeUtils.php";
require_once "../utils/httpMethodsUtils.php";

header("Content-type: application/json");

$AuthController = new AuthController();
$method = $_SERVER['REQUEST_METHOD'];
$acao = $_GET["acao"];

switch ($method) {
    case POST:
        if($acao == "CriarSessao"){
            $login = getBody();
            echo json_encode($AuthController->CriarSessao($login));
        }
        break;
    
    case GET:
        if($acao == "ChecarSessao"){
            echo json_encode($AuthController->ChecarSessao());
        }
        
        if($acao == "EncerrarSessao"){
            echo json_encode($AuthController->EncerrarSessao());
        }
        break;
    
    default:
        echo http_response_code ( 400 );
        break;
}



