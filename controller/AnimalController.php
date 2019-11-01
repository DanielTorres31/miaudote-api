<?php
require_once "../enum/EnumAnimal.php";
require_once "../utils/retornoUtils.php";

header("Content-type: application/json");


class AnimalController {

    public function cadastrarAnimal($animal) {
        require_once "Conexao.php";        
        
        try {
            $stmt = $conn->prepare("INSERT INTO ANIMAL(NOM_ANIMAL, DES_OBSERVACAO, IND_IDADE, IND_PORTE_ANIMAL, 
                                    IND_SEXO_ANIMAL, INSTITUICAO_COD_INSTITUICAO, 
                                    ESPECIE_COD_ESPECIE, DAT_CADASTRO, IND_CASTRADO, DES_VACINA, DES_TEMPERAMENTO) 
                                    VALUES (:nom_animal, :des_observacao, :ind_idade, :ind_porte_animal, :ind_sexo_animal, 
                                    :cod_instituicao, :cod_especie, now(), :ind_castrado, :vacina, :temperamento)");
        
            $stmt->bindParam(':nom_animal', $animal->NOM_ANIMAL);
            $stmt->bindParam(':des_observacao', $animal->DES_OBSERVACAO);
            $stmt->bindParam(':ind_idade', $animal->IND_IDADE);
            $stmt->bindParam(':ind_porte_animal', $animal->IND_PORTE_ANIMAL);
            $stmt->bindParam(':ind_sexo_animal', $animal->IND_SEXO_ANIMAL);
            $stmt->bindParam(':cod_instituicao', $animal->INSTITUICAO_COD_INSTITUICAO);
            $stmt->bindParam(':cod_especie', $animal->ESPECIE_COD_ESPECIE);
            $stmt->bindParam(':ind_castrado', $animal->IND_CASTRADO);
            $stmt->bindParam(':vacina', $animal->DES_VACINA);
            $stmt->bindParam(':temperamento', $animal->DES_TEMPERAMENTO);
            
            $stmt->execute();
            
            $id = $this->buscarUltimoId();
            
            $this->uploadImagem($id, $animal->BIN_FOTO);
            
            return criaRetornoSucesso(SUCESSO_ANIMAL_CRIADO);
        } catch(PDOException $e){
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_CRIADO);
        }
       
        $conn = null;
    }
    
    public function excluirAnimal($id) {
        require_once "Conexao.php";
       
        try{
            $stmt = $conn->prepare("UPDATE ANIMAL
                                    SET IND_EXCLUIDO = 'T'
                                    WHERE COD_ANIMAL = :id");
            
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        
            return criaRetornoSucesso(SUCESSO_ANIMAL_EXCLUIDO);
        } catch(PDOException $e) {
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_EXCLUIDO);
        }
        
        $conn = null;
    }
    
    public function adotarAnimal($id) {
        require_once "Conexao.php";
        
        try{
            $stmt = $conn->prepare("UPDATE ANIMAL
                                    SET IND_ADOTADO = 'T',
                                        DAT_ADOCAO = NOW()
                                    WHERE COD_ANIMAL = :id");
            
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        
            return criaRetornoSucesso(SUCESSO_ANIMAL_ADOTADO);
        } catch (PDOException $e) {
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_ADOTADO);
        }
        
        $conn=null;
    }
    
    public function editarAnimal($animal) {
        require_once "Conexao.php";
        
        try {
            $stmt = $conn->prepare("UPDATE `ANIMAL` 
                                    SET `NOM_ANIMAL`=:nom_animal,`DES_OBSERVACAO`=:des_observacao,`IND_IDADE`=:des_idade,`IND_PORTE_ANIMAL`=:des_porte,
                                    `IND_SEXO_ANIMAL`=:des_sexo,
                                    `INSTITUICAO_COD_INSTITUICAO`=:cod_instituicao,
                                    `ESPECIE_COD_ESPECIE`=:cod_especie,
                                    `IND_CASTRADO`=:castrado,
                                    `DES_VACINA`=:vacina,
                                    `DES_TEMPERAMENTO`=:temperamento
                                    WHERE COD_ANIMAL = :id");
            
            $stmt->bindParam(':id', $animal->COD_ANIMAL);
            $stmt->bindParam(':nom_animal', $animal->NOM_ANIMAL);
            $stmt->bindParam(':des_observacao', $animal->DES_OBSERVACAO);
            $stmt->bindParam(':des_idade', $animal->IND_IDADE);
            $stmt->bindParam(':des_porte', $animal->IND_PORTE_ANIMAL);
            $stmt->bindParam(':des_sexo', $animal->IND_SEXO_ANIMAL);
            $stmt->bindParam(':cod_instituicao', $animal->INSTITUICAO_COD_INSTITUICAO);
            $stmt->bindParam(':cod_especie', $animal->ESPECIE_COD_ESPECIE);
            $stmt->bindParam(':castrado', $animal->IND_CASTRADO);
            $stmt->bindParam(':vacina', $animal->DES_VACINA);
            $stmt->bindParam(':temperamento', $animal->DES_TEMPERAMENTO);

            $stmt-> execute();
            
            $this->updateImagem($animal->COD_ANIMAL, $animal->BIN_FOTO);

            return criaRetornoSucesso(SUCESSO_ANIMAL_ALTERADO);
        } catch (PDOException $e) {
            return criaRetornoErro(ERRO_ANIMAL_ALTERADO);
        }
         
        $conn=null;
    }
    
    public function buscarTodos() {
        require_once "Conexao.php";
        
        $sucesso=false;
        $mensagem=null;
        
        $retornarImagem = $_GET['retornarImagem'];
        
        if($retornarImagem == 'T') {

            $stmt = $conn->prepare("SELECT A.COD_ANIMAL, A.INSTITUICAO_COD_INSTITUICAO, A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
                A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE, F.NOM_FOTO, F.IND_FOTO_PRINCIPAL, F.TIP_FOTO, F.BIN_FOTO
                FROM ANIMAL A
				INNER JOIN FOTO F ON (A.COD_ANIMAL = F.ANIMAL_COD_ANIMAL)
                INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
                INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
                WHERE A.IND_ADOTADO = 'F'
                AND A.IND_EXCLUIDO = 'F'
                AND F.IND_FOTO_PRINCIPAL = 'T'
                ORDER BY A.NOM_ANIMAL");
                
                $stmt->execute();
        
                $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if(empty($lista)){
                    return criaRetornoErro(ERRO_NENHUM_ANIMAL);
                }
                
                return criaRetornoSucessoComDados($lista);

        }
        else {
                $stmt = $conn->prepare("SELECT A.COD_ANIMAL, A.INSTITUICAO_COD_INSTITUICAO, A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
                A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE
                FROM ANIMAL A 
                INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
                INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
                WHERE A.IND_ADOTADO = 'F'
                AND A.IND_EXCLUIDO = 'F'
                ORDER BY A.NOM_ANIMAL");
                
                $stmt->execute();
                
                $animais = array();
                
                while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                    $animais[] = $row;
                }

                if(empty($animais)){
                    return criaRetornoErro(ERRO_NENHUM_ANIMAL);
                }
        
                return criaRetornoSucessoComDados($animais);

        }


        $conn = null;
    }
    
    public function buscarPorId($id) {
        
        require_once "Conexao.php";
        
        $sucesso=false;
        $mensagem=null;
        
        $retornarImagem = $_GET['retornarImagem'];
        
        if($retornarImagem == 'T') {
            
            $stmt = $conn->prepare("SELECT A.COD_ANIMAL, A.INSTITUICAO_COD_INSTITUICAO, A.ESPECIE_COD_ESPECIE, A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
                A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE, F.NOM_FOTO, F.IND_FOTO_PRINCIPAL, F.TIP_FOTO, F.BIN_FOTO
                FROM ANIMAL A
				INNER JOIN FOTO F ON (A.COD_ANIMAL = F.ANIMAL_COD_ANIMAL)
                INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
                INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
                WHERE A.IND_ADOTADO = 'F'
                AND A.IND_EXCLUIDO = 'F'
                AND F.IND_FOTO_PRINCIPAL = 'T'
                AND A.COD_ANIMAL = :id
                ORDER BY A.NOM_ANIMAL");
                
                $stmt->bindParam(':id', $id);
                
                $stmt->execute();
                
                $animal = $stmt->fetch(PDO::FETCH_OBJ);
            
                if(empty($animal)){
                    return criaRetornoErro(ERRO_NENHUM_ANIMAL);
                }
                
                return criaRetornoSucessoComDados($animal);
                
        }
        else {
                $stmt = $conn->prepare("SELECT A.COD_ANIMAL, A.INSTITUICAO_COD_INSTITUICAO, A.ESPECIE_COD_ESPECIE, A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
                A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE
                FROM ANIMAL A 
                INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
                INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
                WHERE A.IND_ADOTADO = 'F'
                AND A.IND_EXCLUIDO = 'F'
                AND A.COD_ANIMAL = :id
                ORDER BY A.NOM_ANIMAL");
                
                $stmt->bindParam(':id',$id);
                
                $stmt->execute();

                $animal = $stmt->fetch(PDO::FETCH_OBJ);
        
                if(empty($animal)){
                    return criaRetornoErro(ERRO_NENHUM_ANIMAL);
                }
        
                return criaRetornoSucessoComDados($animal);

        }
        
        $conn = null;
        
    }
    
    public function buscarAdotados() {
        require_once "Conexao.php";
        
        $stmt = $conn->prepare("SELECT A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
                A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE
                FROM ANIMAL A 
                INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
                INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
                WHERE A.IND_ADOTADO = 'T'
                ORDER BY A.NOM_ANIMAL");
                
        $stmt->execute();
        
        $animais = array();
        
        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
             $animais[] = $row;
      }

        if(empty($animais)){
            return criaRetornoErro(ERRO_NENHUM_ANIMAL);
        }
        
        return criaRetornoSucessoComDados($animais);
        
        $conn = null;
        
    }
    
    public function buscarImagens($id) {
        require_once "Conexao.php";
        
        $stmt = $conn->prepare("SELECT COD_FOTO_ANIMAL, IND_FOTO_PRINCIPAL, TIP_FOTO, BIN_FOTO, ANIMAL_COD_ANIMAL FROM FOTO WHERE ANIMAL_COD_ANIMAL = :id");
        
        $stmt->bindParam(':id', $id);
                
        $stmt->execute();
        
        $animais = array();
        
        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
             $animais[] = $row;
        }

        if(empty($animais)){
            return criaRetornoErro(ERRO_ANIMAL_FOTO);
        }
        
        return criaRetornoSucessoComDados($animais);
        
        $conn = null;
    }
    
    public function uploadImagem($id, $imagem) {
        include "Conexao.php";
        
        try {
            
            $stmt = $conn -> prepare("INSERT INTO `FOTO`(`TIP_FOTO`, `BIN_FOTO`, `IND_FOTO_PRINCIPAL`, `ANIMAL_COD_ANIMAL`) 
                                VALUES (:tipo, :binario, 'T', :id)");
                                
        $tipo = substr($imagem, 11, 3);
        if($tipo == "jpe") {
            $tipo = substr($imagem, 11, 4);    
        } 
        
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':binario', $imagem);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();
        } catch(PDOException $e){
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_CRIADO);
        }
       
        $conn = null;
    
    }

    public function updateImagem($id, $imagem) {
        include "Conexao.php";
        
        try {
            
            $stmt = $conn -> prepare("UPDATE `FOTO` SET `TIP_FOTO` = :tipo, `BIN_FOTO` = :binario WHERE ANIMAL_COD_ANIMAL = :id");
                                
        $tipo = substr($imagem, 11, 3);
        if($tipo == "jpe") {
            $tipo = substr($imagem, 11, 4);    
        } 
        
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':binario', $imagem);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();
        } catch(PDOException $e){
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_CRIADO);
        }
       
        $conn = null;
    
    }
    
    public function buscarUltimoId() {
        include "Conexao.php";
                
        try {
            $stmt = $conn->prepare("SELECT MAX(COD_ANIMAL) MAXIMO FROM ANIMAL");
            $stmt->execute();
            
            $id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $id = $id[0]["MAXIMO"];
            
            return $id;
        } catch(PDOException $e){
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_CRIADO);
        }
        
        $conn = null;
    }
    
    public function filtro($filtro) {
        require_once "Conexao.php";
        
        try {
            $sql = "SELECT A.COD_ANIMAL, A.INSTITUICAO_COD_INSTITUICAO, A.ESPECIE_COD_ESPECIE, A.NOM_ANIMAL, A.IND_IDADE, A.IND_PORTE_ANIMAL, A.IND_SEXO_ANIMAL, A.IND_CASTRADO, A.DAT_CADASTRO, 
            A.DES_OBSERVACAO, A.DES_VACINA, A.DES_TEMPERAMENTO, I.NOM_INSTITUICAO, E.DES_ESPECIE, F.NOM_FOTO, F.IND_FOTO_PRINCIPAL, F.TIP_FOTO, F.BIN_FOTO
            FROM ANIMAL A
            INNER JOIN FOTO F ON (A.COD_ANIMAL = F.ANIMAL_COD_ANIMAL)
            INNER JOIN INSTITUICAO I ON  (A.INSTITUICAO_COD_INSTITUICAO = I.COD_INSTITUICAO)
            INNER JOIN ESPECIE E ON (E.COD_ESPECIE = A.ESPECIE_COD_ESPECIE)
            WHERE A.IND_ADOTADO = 'F'
            AND A.IND_EXCLUIDO = 'F'
            AND F.IND_FOTO_PRINCIPAL = 'T' ";

            if(@$filtro->nome != null) {
                $sql = $sql . "AND A.NOM_ANIMAL LIKE '%{$filtro->nome}%' ";
            }

            if(@$filtro->porte != null) {
                $sql = $sql . "AND A.IND_PORTE_ANIMAL IN (:porte) ";
            }

            if(@$filtro->especie != null) {
                $sql = $sql . "AND A.ESPECIE_COD_ESPECIE IN (:especie) ";
            }

            if(@$filtro->sexo != null) {
                $sql = $sql . "AND A.IND_SEXO_ANIMAL IN (:sexo) ";
            }

            if(@$filtro->idade != null) {
                $sql = $sql . "AND A.IND_IDADE IN (:idade) ";
            }

            $sql = $sql . "ORDER BY A.COD_ANIMAL";
            echo $sql;
            $stmt = $conn->prepare($sql);
            
            // if(@$filtro->nome != null) {
            //     $stmt->bindParam(':nome', $filtro->nome);
            // }

            if(@$filtro->porte != null) {
                $stmt->bindParam(':porte', $filtro->porte);
            }

            if(@$filtro->especie != null) {
                $stmt->bindParam(':especie', $filtro->especie);
            }

            if(@$filtro->sexo != null) {
                $stmt->bindParam(':sexo', $filtro->sexo);
            }

            if(@$filtro->idade != null) {
                $stmt->bindParam(':idade', $filtro->idade);
            }
            
            $stmt->execute();
            
            $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if(empty($lista)){
                return criaRetornoErro(ERRO_NENHUM_ANIMAL);
            }
    
            return criaRetornoSucessoComDados($lista);
            
            $conn = null;
                
        } catch(PDOException $e) {
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_ANIMAL_FILTRO);
        }
        
    }
    
}

?>