<?php
require_once "../controller/AnimalController.php";
require_once "../enum/EnumAnimal.php";
require_once "../utils/routeUtils.php";
require_once "../utils/httpMethodsUtils.php";

header("Content-type: application/json");

$animalController = new AnimalController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case PUT:
        $id = getIdNaRequisicao();
        $body = getBody();
        echo 'PUT Animal';
        break;
    
    case POST:
        $body = getBody();
        $validacao = validaBody($body);
        if($validacao->erro){
            echo json_encode($validacao);
            break;
        }
        $resposta = $animalController->cadastrarAnimal($body);
        echo json_encode($resposta);
        break;
    
    case GET:
        echo json_encode($animalController->buscarTodos());
        break;
    
    case DELETE:
        $id = getIdNaRequisicao();
        echo json_encode($animalController->excluirAnimal($id));
        break;

    default:
        echo http_response_code ( 400 );
        break;
}

function validaBody($animal) {
    $erro = false;
    $mensagens = [];

    if(empty($animal->nome)) {
        $erro = true;
        array_push($mensagens, ERRO_NOME_OBRIGATORIO);
    }
    if(empty($animal->idade)) {
        $erro = true;
        array_push($mensagens, ERRO_IDADE_OBRIGATORIO);
    }
    if(empty($animal->porte)) {
        $erro = true;
        array_push($mensagens, ERRO_PORTE_OBRIGATORIO);
    }
    if(empty($animal->sexo)) {
        $erro = true;
        array_push($mensagens, ERRO_SEXO_OBRIGATORIO);
    }
    if(empty($animal->instituicao)) {
        $erro = true;
        array_push($mensagens, ERRO_INSTITUICAO_OBRIGATORIO);
    }
    if(empty($animal->especie)) {
        $erro = true;
        array_push($mensagens, ERRO_ESPECIE_OBRIGATORIO);
    }
    if(empty($animal->castrado)) {
        $erro = true;
        array_push($mensagens, ERRO_CASTRADO_OBRIGATORIO);
    }

    $validacao = new stdClass();
    $validacao->erro = $erro;
    $validacao->mensagens = $mensagens;
    return $validacao;
}

?>