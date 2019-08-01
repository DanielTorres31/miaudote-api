<?php
require_once "../enum/EnumMensagem.php";

class MensagemController {

    public function EnviarMensagem($p_NomUsuario, $p_Email, $p_Mensagem){
        require_once "Conexao.php";
        $erro = false;
        $mensagem = null;
        
        if(empty($p_NomUsuario)){
            $erro = true;
            $mensagem = ERRO_NOME_USUARIO;
        }elseif(empty($p_Email)){
            $erro = true;
            $mensagem = ERRO_EMAIL_OBRIGATORIO;
        }elseif(empty($p_Mensagem)){
            $erro = true;
            $mensagem = ERRO_MENSAGEM_OBRIGATORIO;
        }
        
        if($erro){
            return array("sucesso"=>false,
            "mensagem"=>$mensagem);
        }
        
        try{
            $stmt = $conn->prepare("
            INSERT INTO `miaudote`.`MENSAGEM` (
            `NOM_VISITANTE`,
            `DES_EMAIL`,
            `DES_MENSAGEM`,
            `DES_STATUS`
            )
            VALUES (
            :nome, :email, :mensagem, :status
            )
            ");
            $status = MENSAGEM_INATIVA;
            $stmt->bindParam(':nome', $p_NomUsuario);
            $stmt->bindParam(':email', $p_Email);
            $stmt->bindParam(':mensagem', $p_Mensagem);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            
            return array("sucesso"=>true,
            "mensagem"=>SUCESSO_MENSAGEM);
            
        }catch(Exception $ex){
            return array("sucesso"=>false,
            "mensagem"=>ERRO_MENSAGEM);
        }
        $conn = null;
    }
}