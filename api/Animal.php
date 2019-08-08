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
        $id = getIdNaRequisicao($_SERVER['REQUEST_URI']);
        echo 'PUT Animal';
        break;
    
    case POST:
        $body = file_get_contents("php://input");
        $animal = json_decode($body);
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
        echo 'DELETE Animal';
        break;

    default:
        echo http_response_code ( 400 );
        break;
}

function validaBody($animal) {
    $erro = false;
    $mensagem = null;

    $animal->nome;
    $animal->observacao;
    $animal->idade;
    $animal->porte;
    $animal->sexo;
    $animal->instituicao;
    $animal->especie;
    $animal->castrado;
    $animal->vacina;
    $animal->temperamento;
    $animal->foto;
    
    if(empty($animal->nome)) {
        $erro = true;
        $mensagem = ERRO_NOME_OBRIGATORIO;
    }

    $validacao = new stdClass();
    $validacao->erro = $erro;
    $validacao->mensagem = $mensagem;
    return $validacao;
}

?>