<?php
require_once "../enum/EnumAuth.php";
require_once "../enum/EnumUsuario.php";
require_once "../utils/retornoUtils.php";

class AuthController {
   
 public function CriarSessao($login) {
        include "Conexao.php";
        
        $stmt = $conn->prepare("SELECT DES_SENHA, DES_TIPO_USUARIO, IND_EXCLUIDO FROM USUARIO WHERE DES_EMAIL= :Email AND IND_EXCLUIDO='N'");
        $stmt->bindParam(':Email', $login->email);
        $stmt->execute();
        
        $infoUsuario = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (@$infoUsuario->DES_SENHA == sha1($login->senha)) {
            session_start();
            @$_SESSION["email"] = $login->email;
            @$_SESSION["senha"] = $login->senha;

            return criaRetornoSucesso(SUCESSO_LOGIN);
        } else {
            http_response_code ( 500 );
            return criaRetornoErro(ERRO_LOGIN);
        }
        
        $conn = null;
    }
    
     public function ChecarSessao(){
        require_once "Conexao.php";
        
        session_start();
        @$email = $_SESSION["email"];
        @$senha = $_SESSION["senha"];
        
        if(empty($email) || empty($senha)){
            http_response_code ( 500 );
            return criaRetornoErro(SESSAO_INVALIDA);
        }
        
        $stmt = $conn->prepare("SELECT DES_SENHA, COD_USUARIO, DES_TIPO_USUARIO, NOM_USUARIO, DES_EMAIL, IND_EXCLUIDO FROM USUARIO WHERE DES_EMAIL = :Email");
        $stmt->bindParam(':Email', $email);
        $stmt->execute();

        $infoUsuario = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(sha1($senha) !== $infoUsuario->DES_SENHA || $infoUsuario->IND_EXCLUIDO == UsuarioExcluido) {
            http_response_code ( 500 );
            return criaRetornoErro(SESSAO_INVALIDA);
        }
        
        return criaRetornoSucessoComDados($this->GetDadosSessao($infoUsuario));
        
        $conn = null;
    }
    
    public function GetDadosSessao($infoUsuario) {
        $sessao = new stdClass();
        
        $sessao->COD_USUARIO = $infoUsuario->COD_USUARIO;
        $sessao->NOM_USUARIO = $infoUsuario->NOM_USUARIO;
        $sessao->DES_EMAIL = $infoUsuario->DES_EMAIL;
        $sessao->DES_TIPO_USUARIO = $infoUsuario->DES_TIPO_USUARIO;

        return $sessao;
    }
    
    public function EncerrarSessao(){
        session_start();
        unset($_SESSION["email"]);
        unset($_SESSION["senha"]);
        
        return array("sucesso"=>true,
                    "mensagem"=>SUCESSO_ENCERRAR_SESSAO);
    }
    

    public function ChecarPermissao($p_PermissaoNecessaria){
        require_once "Auth.php";
        $AuthController = new AuthController();
        
        $sessao = $AuthController->ChecarSessao();
        if(!$sessao["sucesso"]){
            return $sessao;
        }
        
        if($sessao["data"]["tipo"] !== $p_PermissaoNecessaria){
            http_response_code ( 500 );
            return array("sucesso"=>false,
                        "mensagem"=>ERRO_NAO_POSSUI_PERMISSAO);
        }

    }
}