<?php
require_once "../controller/Instituicao.php";
require_once "../enum/EnumInstituicao.php";
header("Content-type: application/json");

$Instituicao = new Instituicao();

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
    echo json_encode($Instituicao->CriarInstituicao($NomeInstituicao, $Telefone, $Email, $TipoInstituicao, $Cidade));
}

if($acao == "DeletarInstituicao"){
    $postdata = file_get_contents("php://input");
    $instituicao = json_decode($postdata);
    $dados = $instituicao->dados[0];
    $InstituicaoPK = $dados->COD_INSTITUICAO;
    
    echo json_encode($Instituicao->ExcluirInstituicao($InstituicaoPK));
}

if($acao == "AlterarInstituicao"){
   $InstituicaoPK = 3;
   $NomeInstituicao =  "Teste Protetor";
   $Telefone = "(31)5555-5555";
   $Email = "fdsfsldfdfdf@gmail.com";
   $TipoInstituicao = "P"; 
    echo json_encode($Instituicao->AlterarInstituicao($InstituicaoPK, $NomeInstituicao, $Telefone, $Email, $TipoInstituicao));
}

if($acao == "GetInstituicao"){
    $pagina = $_GET["Pagina"];
    echo json_encode($Instituicao->GetInstituicao($pagina));
}

if($acao == "GetCidades"){
    echo json_encode($Instituicao->GetCidades());
}

