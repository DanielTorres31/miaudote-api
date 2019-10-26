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
            http_response_code ( 400 );
            echo json_encode($validacao);
            break;
        }
        $resposta = $animalController->cadastrarAnimal($body);
        echo json_encode($resposta);
        break;
    
    case GET:
        if(isBuscarTodos()) {
            echo json_encode($animalController->buscarTodos());
            break;
        } 
        $id = getIdNaRequisicao();
        echo json_encode($animalController->buscarPorId($id));
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
    
    if(@$animal->NOM_ANIMAL == null) {
        $erro = true;
        array_push($mensagens, ERRO_NOME_OBRIGATORIO);
    }
    if(@$animal->IND_IDADE == null) {
        $erro = true;
        array_push($mensagens, ERRO_IDADE_OBRIGATORIO);
    }
    if(@$animal->IND_PORTE_ANIMAL == null) {
        $erro = true;
        array_push($mensagens, ERRO_PORTE_OBRIGATORIO);
    }
    if(@$animal->IND_SEXO_ANIMAL == null) {
        $erro = true;
        array_push($mensagens, ERRO_SEXO_OBRIGATORIO);
    }
    if(@$animal->INSTITUICAO_COD_INSTITUICAO == null) {
        $erro = true;
        array_push($mensagens, ERRO_INSTITUICAO_OBRIGATORIO);
    }
    if(@$animal->ESPECIE_COD_ESPECIE == null) {
        $erro = true;
        array_push($mensagens, ERRO_ESPECIE_OBRIGATORIO);
    }
    if(@$animal->IND_CASTRADO == null) {
        $erro = true;
        array_push($mensagens, ERRO_CASTRADO_OBRIGATORIO);
    }

    $validacao = new stdClass();
    $validacao->erro = $erro;
    $validacao->mensagens = $mensagens;
    return $validacao;
}

function isBuscarTodos() {
    if(getIdNaRequisicao() == 'Animal.php') {
        return true;
    }
    return false;
}

?>