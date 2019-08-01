<?php
require_once "../controller/AnimalController.php";
header("Content-type: application/json");

$AnimalController = new AnimalController();

$acao = $_GET["acao"];
if($acao == "CadastrarAnimal") {
    $postdata = file_get_contents("php://input");
	echo json_encode($postdata);
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $p_NomeAnimal = $dados->nome;
    $p_DesObservacao = $dados->observacao;
    //$p_Vacina = $dados->; não está passando os dados
    $p_Temperamento = $dados->temperamento;
    $p_Especie = $dados->especie;
    $p_foto = $dados->file;
    
    
    //Tratando campo Idade
    if($dados->idade == '1') {
        $p_IdadeAnimal = 'F';
    }
    elseif($dados->idade == '2') {
        $p_IdadeAnimal = 'A';
    }
    elseif($dados->idade == '3') {
        $p_IdadeAnimal = 'I';
    }
    
    //Tratando campo Porte
    if($dados->porte == '1'){
        $p_PorteAnimal = 'P';
    } 
    elseif($dados->porte == '2') {
        $p_PorteAnimal = 'M';
    }
    elseif($dados->porte == '3') {
       $p_PorteAnimal = 'G'; 
    }

    //Tratando campo Sexo
    if($dados->sexo == 'macho') {
        $p_Sexo = 'M';
    }
    elseif($dados->sexo == 'femea') {
        $p_Sexo = 'F';  
    }

    
    $p_Instituicao = $dados->instituicao;
    //Tratando campo Instituição
    // if($dados->instituicao == '1') {
    //     $p_Instituicao = '1';
    // }
    // elseif($dados->instituicao == '2') {
    //     $p_Instituicao = '2';
    // }

    //Tratando campo Castrado
    if($dados->castrado == 's') {
        $p_IndCastrado = 'T';
    }
    elseif($dados->castrado == 'n') {
        $p_IndCastrado = 'F';
    }
    
    // $p_NomeAnimal = "Bruce";
    // $p_DesObservacao = "tranquilo e carinhoso";
    // $p_IdadeAnimal = "5";
    // $p_PorteAnimal = "2";
    // $p_Sexo = "M";
    // $p_Instituicao = '1';
    // $p_Especie = '1';
    // $p_IndCastrado = "T";
    
    echo json_encode($AnimalController->cadastrarAnimal($p_NomeAnimal, $p_DesObservacao, $p_IdadeAnimal, $p_PorteAnimal, $p_Sexo, $p_Vacina, $p_Temperamento, $p_Instituicao, $p_Especie, $p_IndCastrado, $p_foto));
}

if($acao == "ExcluirAnimal") {
    
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados[0];
    $id = $dados->COD_ANIMAL;
    
    echo json_encode($AnimalController->excluirAnimal($id));
}

if($acao == "AdotarAnimal") {
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $id = $dados->id;
    
    echo json_encode($AnimalController->AdotarAnimal($id));
}

if($acao == "EditarAnimal") {
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $id = $dados->id;
    $p_NomeAnimal = $dados->nome;
    $p_DesObservacao = $dados->observacao;
    //$p_Vacina = $dados->; não está passando os dados
    $p_Temperamento = $dados->temperamento;
    $p_Especie = $dados->especie;
    $p_foto = $dados->file;
    
    
    //Tratando campo Idade
    if($dados->idade == '1') {
        $p_IdadeAnimal = 'F';
    }
    elseif($dados->idade == '2') {
        $p_IdadeAnimal = 'A';
    }
    elseif($dados->idade == '3') {
        $p_IdadeAnimal = 'I';
    }
    
    //Tratando campo Porte
    if($dados->porte == '1'){
        $p_PorteAnimal = 'P';
    } 
    elseif($dados->porte == '2') {
        $p_PorteAnimal = 'M';
    }
    elseif($dados->porte == '3') {
       $p_PorteAnimal = 'G'; 
    }

    //Tratando campo Sexo
    if($dados->sexo == 'macho') {
        $p_Sexo = 'M';
    }
    elseif($dados->sexo == 'femea') {
        $p_Sexo = 'F';  
    }

    
    $p_Instituicao = $dados->instituicao;
    //Tratando campo Instituição
    // if($dados->instituicao == '1') {
    //     $p_Instituicao = '1';
    // }
    // elseif($dados->instituicao == '2') {
    //     $p_Instituicao = '2';
    // }

    //Tratando campo Castrado
    if($dados->castrado == 's') {
        $p_IndCastrado = 'T';
    }
    elseif($dados->castrado == 'n') {
        $p_IndCastrado = 'F';
    }
    
    // $p_NomeAnimal = "Bruce";
    // $p_DesObservacao = "tranquilo e carinhoso";
    // $p_IdadeAnimal = "5";
    // $p_PorteAnimal = "2";
    // $p_Sexo = "M";
    // $p_Instituicao = '1';
    // $p_Especie = '1';
    // $p_IndCastrado = "T";
    
    echo json_encode($AnimalController->EditarAnimal($id, $p_NomeAnimal, $p_Observacao, $p_IdadeAnimal, $p_PorteAnimal, $p_Sexo, $p_Vacina, $p_Temperamento, $p_Instituicao, $p_Especie, $p_IndCastrado, $p_foto));
}

if($acao == "BuscarTodos") {
    echo json_encode($AnimalController->BuscarTodos());
}

if($acao == "BuscarPorId") {
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $id = $dados->id;
 
    echo json_encode($AnimalController->BuscarPorId($id));
}

if($acao == "BuscarAdotados") {
    
    echo json_encode($AnimalController->BuscarAdotados());
}

if($acao == "BuscarImagens") {
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $id = $dados->id;
    
    echo json_encode($AnimalController->BuscarImagens($id));
}

if($acao == "Filtro") {
    $postdata = file_get_contents("php://input");
    $animal = json_decode($postdata);
    $dados = $animal->dados;
    
    $nome = $animal->nome;
    $especie = $animal->especie;
    $porte = $animal->porte;
    $sexo = $animal->sexo;
    $idade = $animal->idade;
    
    echo json_encode ($AnimalController->filtro($nome, $especie, $porte, $sexo, $idade));
}

?>