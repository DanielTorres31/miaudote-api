<?php
require_once "../enum/EnumAuth.php";
require_once "../enum/EnumUsuario.php";

class AuthController {
   
 public function CriarSessao($p_Email, $p_Senha) {
        include "Conexao.php";
        
        $stmt = $conn->prepare("SELECT DES_SENHA, DES_TIPO_USUARIO, IND_EXCLUIDO FROM USUARIO WHERE DES_EMAIL= :Email AND IND_EXCLUIDO='N'");
        $stmt->bindParam(':Email', $p_Email);
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
             @$senha = $row->DES_SENHA;
             @$tipo = $row->DES_TIPO_USUARIO;
             @$excluido = $row->IND_EXCLUIDO;
        }
        
        if (@$senha == sha1($p_Senha) && $excluido !== UsuarioExcluido) {
            session_start();
            @$_SESSION["email"] = $p_Email;
            @$_SESSION["senha"] = $senha;

            return array("mensagem" => SUCESSO_LOGIN,
                "sucesso" => true,
                "tipo"=>$tipo);
        } else {
            return array("mensagem" => ERRO_LOGIN,
                "sucesso" => false);
        }
        
        $conn = null;
    }
    
     public function ChecarSessao(){
        require_once "Conexao.php";
        
        session_start();
        @$email = $_SESSION["email"];
        @$senha = $_SESSION["senha"];
        
        if(empty($email) || empty($senha)){
            return array("sucesso"=>false,
                        "mensagem"=>SESSAO_INVALIDA);
        }
        
        $stmt = $conn->prepare("SELECT DES_SENHA, COD_USUARIO, DES_TIPO_USUARIO, NOM_USUARIO, DES_EMAIL, IND_EXCLUIDO FROM USUARIO WHERE DES_EMAIL = :Email");
        $stmt->bindParam(':Email', $email);
        $stmt->execute();

       while($row = $stmt->fetch(PDO::FETCH_OBJ)){
          $SenhaCorreta = $row->DES_SENHA;
          $CodigoUsuario = $row->COD_USUARIO;
          $tipo = $row->DES_TIPO_USUARIO;
          $email = $row->DES_EMAIL;
          $nome = $row->NOM_USUARIO;
          $excluido = $row->IND_EXCLUIDO;
       }

    
        if($senha !== $SenhaCorreta || $excluido == UsuarioExcluido){
            return array("sucesso"=>false,
                        "mensagem"=>SESSAO_INVALIDA);
        }
        
        return array("sucesso"=>true,
                    "data"=>array(
                            "NOM_USUARIO"=>$nome,
                            "DES_EMAIL"=>$email,
                            "TIPO"=>$tipo,
                            "CODIGO_USUARIO"=>$CodigoUsuario
                        ));
        $conn->close();
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
            return array("sucesso"=>false,
                        "mensagem"=>ERRO_NAO_POSSUI_PERMISSAO);
        }

    }
}