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
        echo 'PUT Animal';
        break;
    
    case POST:
        $body = getBody();
        $validacao = validaBody($animal);
        if($validacao->erro){
            echo json_encode($validacao);
            break;
        }
        $resposta = $animalController->cadastrarAnimal($animal);
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
    $mensagem = null;

    if(empty($animal->nome)) {
        $erro = true;
        $mensagem = ERRO_NOME_OBRIGATORIO;
    }

    if(empty($animal->idade)) {
        $erro = true;
        $mensagem = ERRO_IDADE_OBRIGATORIO;
    }

    if(empty($animal->porte)) {
        $erro = true;
        $mensagem = ERRO_PORTE_OBRIGATORIO;
    }

    if(empty($animal->sexo)) {
        $erro = true;
        $mensagem = ERRO_SEXO_OBRIGATORIO;
    }

    if(empty($animal->instituicao)) {
        $erro = true;
        $mensagem = ERRO_INSTITUICAO_OBRIGATORIO;
    }

    if(empty($animal->especie)) {
        $erro = true;
        $mensagem = ERRO_ESPECIE_OBRIGATORIO;
    }

    if(empty($animal->castrado)) {
        $erro = true;
        $mensagem = ERRO_CASTRADO_OBRIGATORIO;
    }

    $validacao = new stdClass();
    $validacao->erro = $erro;
    $validacao->mensagem = $mensagem;
    return $validacao;
}

?>