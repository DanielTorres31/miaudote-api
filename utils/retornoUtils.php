<?php

function criaRetornoSucesso($mensagem) {
    $retorno = new stdClass();
    $retorno->sucesso = true;
    $retorno->mensagem = $mensagem;
    return $retorno;
}

function criaRetornoSucessoComDados($dados) {
    $retorno = new stdClass();
    $retorno->sucesso = true;
    $retorno->data = $dados;
    return $retorno;
}

function criaRetornoErro($mensagem) {
    $erro = new stdClass();
    $erro->erro = true;
    $erro->mensagem = $mensagem;
    return $erro;
}

?>