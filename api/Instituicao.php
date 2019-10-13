<?php
require_once "../controller/InstituicaoController.php";
require_once "../enum/EnumInstituicao.php";
require_once "../utils/routeUtils.php";
require_once "../utils/httpMethodsUtils.php";

header("Content-type: application/json");

$InstituicaoController = new InstituicaoController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case PUT:
        // $id = getIdNaRequisicao();
        // $body = getBody();
        // echo 'PUT Animal';
        break;
    
    case POST:
        // $body = getBody();
        // $validacao = validaBody($body);
        // if($validacao->erro){
        //     echo json_encode($validacao);
        //     break;
        // }
        // $resposta = $animalController->cadastrarAnimal($body);
        // echo json_encode($resposta);
        break;
    
    case GET:
        if(isBuscarTodos()) {
            echo json_encode($InstituicaoController->GetInstituicao(0));
            break;
        }
        $id = getIdNaRequisicao();
        echo json_encode($InstituicaoController->GetInstituicaoPorId($id));
        break;
    
    case DELETE:
        // $id = getIdNaRequisicao();
        // echo json_encode($animalController->excluirAnimal($id));
        break;

    default:
        echo http_response_code ( 400 );
        break;
}

function isBuscarTodos() {
    if(getIdNaRequisicao() == 'Instituicao.php') {
        return true;
    }
    return false;
}

$acao = $_GET["acao"];
if($acao == "CriarInstituicao"){
    $postdata = file_get_contents("php://input");
    $instituicao = json_decode($postdata);
    $dados = $instituicao->dados[0];
    
    $NomeInstituicao = $dados->nome;
    $Telefone = $dados->telefone;
    $Email = $dados->email;
    $TipoInstituicao = $dados->tipo;
    $Cidade = $dados->cidade;
    echo json_encode($InstituicaoController->CriarInstituicao($NomeInstituicao, $Telefone, $Email, $TipoInstituicao, $Cidade));
}

if($acao == "DeletarInstituicao"){
    $postdata = file_get_contents("php://input");
    $instituicao = json_decode($postdata);
    $dados = $instituicao->dados[0];
    $InstituicaoPK = $dados->COD_INSTITUICAO;
    
    echo json_encode($InstituicaoController->ExcluirInstituicao($InstituicaoPK));
}

if($acao == "AlterarInstituicao"){
   $InstituicaoPK = 3;
   $NomeInstituicao =  "Teste Protetor";
   $Telefone = "(31)5555-5555";
   $Email = "fdsfsldfdfdf@gmail.com";
   $TipoInstituicao = "P"; 
    echo json_encode($InstituicaoController->AlterarInstituicao($InstituicaoPK, $NomeInstituicao, $Telefone, $Email, $TipoInstituicao));
}

if($acao == "GetInstituicao"){
    $pagina = $_GET["Pagina"];
    echo json_encode($InstituicaoController->GetInstituicao($pagina));
}

if($acao == "GetCidades"){
    echo json_encode($InstituicaoController->GetCidades());
}

?>
