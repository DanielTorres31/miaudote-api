<?php

require_once "../controller/UsuarioController.php";
header("Content-type: application/json");

$UsuarioController = new UsuarioController();

$acao = $_GET["acao"];
if ($acao == "CriarUsuario") {
    $postdata = file_get_contents("php://input");
    $usuario = json_decode($postdata);
    $dados = $usuario->dados[0];

    $NomeUsuario = $dados->nome;
    $Email = $dados->email;
    $Senha = $dados->senha;
    $SenhaRepetida = $dados->repetirSenha;
    $TipoUsuario = "C";

    //  $NomeUsuario = "Henrique";
    //  $Email = "henrique@gmail.com";
    //  $Senha = "123";
    //  $SenhaRepetida = "123";
    //  $TipoUsuario = "A";

    echo json_encode($UsuarioController->CriarUsuario($NomeUsuario, $Email, $TipoUsuario, $Senha, $SenhaRepetida));
}

if ($acao == "GetUsuarios") {
    $pagina = $_GET["Pagina"];
    echo json_encode($UsuarioController->GetUsuarios($pagina));
}

if ($acao == "GetUsuarioPorPK") {
    $usuarioPK = $_GET["COD_USUARIO"];
    echo json_encode($UsuarioController->GetUsuarioPorPK($usuarioPK));
}

if ($acao == "DeletarUsuario") {
    $postdata = file_get_contents("php://input");
    $usuario = json_decode($postdata);
    $dados = $usuario->dados[0];
    
    $usuarioPK = $dados->COD_USUARIO;
    echo json_encode($UsuarioController->DeletarUsuario($usuarioPK));
}

if ($acao == "AlterarSenhaUsuario") {
    $postdata = file_get_contents("php://input");
    $usuario = json_decode($postdata);
    $dados = $usuario->dados[0];
    
    $UsuarioPK = $dados->COD_USUARIO;
    $SenhaAntiga = $dados->DES_SENHA_ANTIGA;
    $Senha = $dados->DES_SENHA;
    $SenhaRepetida = $dados->DES_SENHA_REPETIDA;
    
    echo json_encode($UsuarioController->AlterarSenhaUsuario($UsuarioPK, $SenhaAntiga, $Senha, $SenhaRepetida));
}
