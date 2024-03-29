<?php
error_reporting(0);
ini_set("display_errors", 0);

require_once "../enum/EnumInstituicao.php";
require_once "../utils/retornoUtils.php";
require_once "../utils/logger.php";

class InstituicaoController {
    
    public function CriarInstituicao($instituicao){
        require_once "Conexao.php";
        $erro = false;
        $mensagem = null;
        
        try{
            $stmt = $conn->prepare("INSERT INTO `miaudote`.`INSTITUICAO` (`NOM_INSTITUICAO`, `NUM_TELEFONE`, `IND_TIPO_INSTITUICAO`, `DES_EMAIL`) 
                VALUES (:nome, :telefone, :tipo, :email)");

            $stmt->bindParam(':nome', $instituicao->NOM_INSTITUICAO);
            $stmt->bindParam(':telefone', $instituicao->NUM_TELEFONE);
            $stmt->bindParam(':tipo', $instituicao->IND_TIPO_INSTITUICAO);
            $stmt->bindParam(':email', $instituicao->DES_EMAIL);
            $stmt->execute();
            
            return criaRetornoSucesso(SUCESSO_INSTITUICAO_CADASTRADA);
        }catch(Exception $ex){
            http_response_code ( 500 );
            logApp(ERRO_INSTITUICAO_CADASTRADA, $ex);
            return criaRetornoErro(ERRO_INSTITUICAO_CADASTRADA);
        }
        
        $conn = null;
    }
    
    public function ExcluirInstituicao($p_InstituicaoPK){
        require_once "Conexao.php";
        
        try{
            $excluida = InstituicaoExcluida;
            $stmt = $conn->prepare("UPDATE  `miaudote`.`INSTITUICAO` SET  `IND_EXCLUIDO` =  :excluido WHERE  `INSTITUICAO`.`COD_INSTITUICAO` = :CodInstituicao");
            $stmt->bindParam(':excluido', $excluida);
            $stmt->bindParam(':CodInstituicao', $p_InstituicaoPK);
            $stmt->execute();
            
            return criaRetornoSucesso(SUCESSO_INSTITUICAO_EXCLUIDA);
        }catch(Exception $ex){
            http_response_code ( 500 );
            logApp(ERRO_INSTITUICAO_EXCLUIDA, $ex);
            return criaRetornoErro(ERRO_INSTITUICAO_EXCLUIDA);
        }
        $conn = null;
    }
    
    public function AlterarInstituicao($instituicao){
        require_once "Conexao.php";

        try{
            $excluida = InstituicaoExcluida;
            $stmt = $conn->prepare("
            UPDATE `miaudote`.`INSTITUICAO` SET `NOM_INSTITUICAO` = :nome,
            `NUM_TELEFONE` = :telefone,
            `IND_TIPO_INSTITUICAO` = :tipo,
            `DES_EMAIL` = :email 
            WHERE `INSTITUICAO`.`COD_INSTITUICAO` = :CodInstituicao");
            
            $stmt->bindParam(':CodInstituicao', $instituicao->COD_INSTITUICAO);
            $stmt->bindParam(':nome', $instituicao->NOM_INSTITUICAO);
            $stmt->bindParam(':telefone', $instituicao->NUM_TELEFONE);
            $stmt->bindParam(':tipo', $instituicao->IND_TIPO_INSTITUICAO);
            $stmt->bindParam(':email', $instituicao->DES_EMAIL);
            $stmt->execute();
            
            return criaRetornoSucesso(SUCESSO_ALTERACAO_INSTITUICAO);
        }catch(Exception $ex){
            http_response_code ( 500 );
            logApp(ERRO_ALTERACAO_INSTITUICAO, $ex);
            return criaRetornoErro(ERRO_ALTERACAO_INSTITUICAO);
        }
        $conn = null;
    }
    
    public function GetInstituicao(){
        
        require_once "Conexao.php";

        $stmt = $conn->prepare("SELECT INS.COD_INSTITUICAO, INS.NOM_INSTITUICAO, 
            INS.NUM_TELEFONE, INS.IND_TIPO_INSTITUICAO, INS.DES_EMAIL 
            FROM INSTITUICAO INS
            WHERE IND_EXCLUIDO='F' ORDER BY COD_INSTITUICAO DESC");

        $stmt->execute();
        
        $instituicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($instituicoes)){
            return criaRetornoErro(ERRO_NENHUMA_INSTITUICAO);
        }
        
        return criaRetornoSucessoComDados($instituicoes);
                    
        $conn = null;
    }
    
    public function GetInstituicaoPorId($id){
        
        require_once "Conexao.php";

        $stmt = $conn->prepare("SELECT INS.COD_INSTITUICAO, INS.NOM_INSTITUICAO, 
            INS.NUM_TELEFONE, INS.IND_TIPO_INSTITUICAO, INS.DES_EMAIL 
            FROM INSTITUICAO INS
            WHERE IND_EXCLUIDO='F' AND INS.COD_INSTITUICAO = :id");
        
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        
        $instituicao = $stmt->fetch(PDO::FETCH_OBJ);

        if(empty($instituicao)){
            return criaRetornoErro(ERRO_NENHUMA_INSTITUICAO);
        }
        
        return criaRetornoSucessoComDados($instituicao);
                    
        $conn = null;
    }
}
    