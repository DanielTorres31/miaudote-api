<?php

require_once "../controller/UsuarioController.php";
require_once "../utils/routeUtils.php";
require_once "../utils/httpMethodsUtils.php";
require_once "../utils/retornoUtils.php";

header("Content-type: application/json");

$UsuarioController = new UsuarioController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case PUT:
        $body = getBody();
        $validacao = validaBody($body);
        if($validacao->erro){
            http_response_code ( 400 );
            echo json_encode($validacao);
            break;
        }
        echo json_encode($animalController->editarAnimal($body));
        break;
    
    case POST:
        $body = getBody();        
        $validacao = validaBody($body);
        if($validacao->erro){
            http_response_code ( 400 );
            echo json_encode($validacao);
            break;
        }
        $resposta = $UsuarioController->CriarUsuario($body);
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

function validaBody($usuario) {
    $erro = false;
    $mensagens = [];
    
    // if ($UsuarioController->isEmailJaExiste($usuario->DES_EMAIL)) {
    //     return criaRetornoErro(ERRO_EMAIL_EXISTE_USUARIO);
    // }

    if(@$usuario->NOM_USUARIO == null) {
        $erro = true;
        array_push($mensagens, ERRO_NOME_OBRIGATORIO_USUARIO);
    }
    if(@$usuario->DES_SENHA == null) {
        $erro = true;
        array_push($mensagens, ERRO_SENHA_OBRIGATORIA_USUARIO);
    }
    if(@$usuario->DES_CONF_SENHA == null) {
        $erro = true;
        array_push($mensagens, ERRO_CONF_SENHA_OBRIGATORIA_USUARIO);
    }
    if(@$usuario->NOM_USUARIO == null) {
        $erro = true;
        array_push($mensagens, ERRO_NOME_OBRIGATORIO);
    }
    if(@$usuario->DES_EMAIL == null) {
        $erro = true;
        array_push($mensagens, ERRO_EMAIL_OBRIGATORIO_USUARIO);
    }
    if ($usuario->DES_SENHA !== $usuario->DES_CONF_SENHA) {
        $erro = true;
        array_push($mensagens, ERRO_REPETIR_SENHA_USUARIO);
    }
    
    $validacao = new stdClass();
    $validacao->erro = $erro;
    $validacao->mensagens = $mensagens;
    return $validacao;
}

function isBuscarTodos() {
    if(getIdNaRequisicao() == 'Usuario.php') {
        return true;
    }
    return false;
}





// $acao = $_GET["acao"];
// if ($acao == "CriarUsuario") {
//     $postdata = file_get_contents("php://input");
//     $usuario = json_decode($postdata);
//     $dados = $usuario->dados[0];

//     $NomeUsuario = $dados->nome;
//     $Email = $dados->email;
//     $Senha = $dados->senha;
//     $SenhaRepetida = $dados->repetirSenha;
//     $TipoUsuario = "C";

    //  $NomeUsuario = "Henrique";
    //  $Email = "henrique@gmail.com";
    //  $Senha = "123";
    //  $SenhaRepetida = "123";
    //  $TipoUsuario = "A";

//     echo json_encode($UsuarioController->CriarUsuario($NomeUsuario, $Email, $TipoUsuario, $Senha, $SenhaRepetida));
// }

// if ($acao == "GetUsuarios") {
//     $pagina = $_GET["Pagina"];
//     echo json_encode($UsuarioController->GetUsuarios($pagina));
// }

// if ($acao == "GetUsuarioPorPK") {
//     $usuarioPK = $_GET["COD_USUARIO"];
//     echo json_encode($UsuarioController->GetUsuarioPorPK($usuarioPK));
// }

// if ($acao == "DeletarUsuario") {
//     $postdata = file_get_contents("php://input");
//     $usuario = json_decode($postdata);
//     $dados = $usuario->dados[0];
    
//     $usuarioPK = $dados->COD_USUARIO;
//     echo json_encode($UsuarioController->DeletarUsuario($usuarioPK));
// }

// if ($acao == "AlterarSenhaUsuario") {
//     $postdata = file_get_contents("php://input");
//     $usuario = json_decode($postdata);
//     $dados = $usuario->dados[0];
    
//     $UsuarioPK = $dados->COD_USUARIO;
//     $SenhaAntiga = $dados->DES_SENHA_ANTIGA;
//     $Senha = $dados->DES_SENHA;
//     $SenhaRepetida = $dados->DES_SENHA_REPETIDA;
    
//     echo json_encode($UsuarioController->AlterarSenhaUsuario($UsuarioPK, $SenhaAntiga, $Senha, $SenhaRepetida));
// }
