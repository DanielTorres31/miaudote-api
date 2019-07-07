<?php

error_reporting(0);
ini_set("display_errors", 0);

require_once "../enum/EnumUsuario.php";

class Usuario {

    public function CriarUsuario($p_NomeUsuario, $p_EmailUsuario, $p_TipoUsuario, $p_DesSenha, $p_DesSenhaRepetida) {
        require_once "Usuario.php";
        require_once "Conexao.php";

        $Usuario = new Usuario();

        $erro = false;
        $mensagem = null;

        if (empty($p_NomeUsuario)) {
            $erro = true;
            $mensagem = ERRO_NOME_OBRIGATORIO;
        } elseif (empty($p_EmailUsuario)) {
            $erro = true;
            $mensagem = ERRO_EMAIL_OBRIGATORIO;
        } elseif (empty($p_DesSenha)) {
            $erro = true;
            $mensagem = ERRO_SENHA_OBRIGATORIA;
        } elseif ($p_DesSenhaRepetida !== $p_DesSenha) {
            $erro = true;
            $mensagem = ERRO_REPETIR_SENHA;
        } elseif ($Usuario->VerificarEmail($p_EmailUsuario)) {
            $erro = true;
            $mensagem = ERRO_EMAIL_EXISTE;
        }

        if ($erro) {
            return array("sucesso" => false,
                "mensagem" => $mensagem);
        }

        $Excluido = UsuarioNaoExcluido;
        $senha = sha1($p_DesSenha);
        try {
            $stmt = $conn->prepare("INSERT INTO `USUARIO`(`DES_EMAIL`, `DES_SENHA`, `DES_TIPO_USUARIO`, `NOM_USUARIO`, `IND_EXCLUIDO`) VALUES (:email, :senha, :tipoUsuario, :nomeusuario, :excluido)");
            $stmt->bindParam(':email', $p_EmailUsuario);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':tipoUsuario', $p_TipoUsuario);
            $stmt->bindParam(':nomeusuario', $p_NomeUsuario);
            $stmt->bindParam(':excluido', $Excluido);
            $stmt->execute();

            return array("mensagem" => SUCESSO_USUARIO_CRIADO,
                "sucesso" => true);
        } catch (Exception $ex) {
            return array("mensagem" => ERRO_USUARIO_CRIADO . "Erro:" . $ex,
                "sucesso" => false);
        }

        $conn = null;
    }

    public function GetUsuarios($p_Pagina) {
        require_once "Conexao.php";
        $QTD_Exibida = 5;

        if (empty($p_Pagina) || $p_Pagina < 1) {
            $p_Pagina = 1;
        }


        $stmt = $conn->prepare("SELECT COUNT(COD_USUARIO) AS QTD_USUARIO FROM USUARIO WHERE IND_EXCLUIDO='N' ");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $QTD_Usuario = $row->QTD_USUARIO;
        }

        $Num_Paginas = ceil($QTD_Usuario / $QTD_Exibida);

        $inicio = ($QTD_Exibida * $p_Pagina) - $QTD_Exibida;
        $excluido = UsuarioNaoExcluido;
        $stmt = $conn->prepare("SELECT COD_USUARIO, NOM_USUARIO, DES_EMAIL FROM USUARIO WHERE IND_EXCLUIDO=:excluido ORDER BY COD_USUARIO DESC LIMIT :inicio, :QtdExibida");
        $stmt->bindParam(':excluido', $excluido);
        $stmt->bindValue(':inicio', (int) $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':QtdExibida', (int) $QTD_Exibida, PDO::PARAM_INT);
        $stmt->execute();

        $usuarios = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $usuarios[] = $row;
        }

        if (empty($usuarios)) {
            return array("sucesso" => false,
                "mensagem" => ERRO_NENHUM_USUARIO);
        }

        return array("sucesso" => true,
            "TotalRegistros" => (int) $QTD_Usuario,
            "QuantidadePaginas" => $Num_Paginas,
            "data" => $usuarios);

        $conn = null;
    }

    public function VerificarEmail($p_EmailUsuario) {
        include "Conexao.php";

        $stmt = $conn->prepare("SELECT COUNT(COD_USUARIO) AS QTD_EMAIL, IND_EXCLUIDO FROM USUARIO WHERE DES_EMAIL=:email AND IND_EXCLUIDO='N'");
        $stmt->bindParam(':email', $p_EmailUsuario);
        $stmt->execute();


        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $QTD_Email = $row->QTD_EMAIL;
            $Excluido = $row->IND_EXCLUIDO;
        }

        if ($QTD_Email > 0)
            return true;
        else
            return false;

        $conn = null;
    }

    public function DeletarUsuario($p_UsuarioPK) {
        include "Conexao.php";

        try {
            $stmt = $conn->prepare("UPDATE `miaudote`.`USUARIO` SET `IND_EXCLUIDO` = 'S' WHERE `USUARIO`.`COD_USUARIO` = :codUsuario");
            $stmt->bindValue(':codUsuario', (int) $p_UsuarioPK, PDO::PARAM_INT);
            $stmt->execute();

            return array("sucesso" => true,
                "mensagem" => SUCESSO_USUARIO_EXCLUIDO);
        } catch (Exception $ex) {
            return array("sucesso" => false,
                "mensagem" => ERRO_USUARIO_EXCLUIDO);
        }
        $conn = null;
    }

    public function AlterarDadosUsuario($p_UsuarioPK, $p_NomeUsuario, $p_EmailUsuario) {
        include "Conexao.php";

        try {
            $stmt = $conn->prepare("UPDATE `miaudote`.`USUARIO` SET `NOM_USUARIO` = :NomeUsuario, `DES_EMAIL` = :Email WHERE `USUARIO`.`COD_USUARIO` = :codUsuario");
            $stmt->bindParam(':NomeUsuario', $p_NomeUsuario);
            $stmt->bindParam(':Email', $p_EmailUsuario);
            $stmt->bindValue(':codUsuario', (int) $p_UsuarioPK, PDO::PARAM_INT);
            $stmt->execute();

            return array("sucesso" => true,
                "mensagem" => SUCESSO_ALTERAR_USUARIO);
        } catch (Exception $ex) {
            return array("sucesso" => false,
                "mensagem" => ERRO_ALTERAR_USUARIO);
        }

        $conn = null;
    }

    public function AlterarSenhaUsuario($p_UsuarioPK, $p_SenhaAntiga, $p_Senha, $p_SenhaRepetida) {
        include "Conexao.php";
        include "Auth.php";
        $Auth = new Auth();
        $sessao = $Auth->ChecarSessao();
        if ($sessao["TIPO"] !== Administrador) {
            $p_UsuarioPK = $sessao["COD_USUARIO"];
            $stmt = $conn->prepare("SELECT DES_SENHA, DES_TIPO_USUARIO, IND_EXCLUIDO FROM USUARIO WHERE COD_USUARIO= :CodUsuario");
            $stmt->bindParam(':CodUsuario', $p_UsuarioPK);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                @$senhaAntigaCorreta = $row->DES_SENHA;
            }

            $SenhaAntiga = sha1($p_SenhaAntiga);
            if ($senhaAntigaCorreta !== $SenhaAntiga) {
                return array("sucesso" => true,
                    "mensagem" => ERRO_SENHA_ANTIGA_INCORRETA);
            }
        }

        if ($p_Senha !== $p_SenhaRepetida) {
            return array("sucesso" => true,
                "mensagem" => ERRO_REPETIR_SENHA);
        }

        $senha = sha1($p_Senha);
        try {
            $stmt = $conn->prepare("UPDATE  `miaudote`.`USUARIO` SET  `DES_SENHA` = :Senha WHERE  `USUARIO`.`COD_USUARIO` = :CodUsuario");
            $stmt->bindValue(':CodUsuario', (int) $p_UsuarioPK, PDO::PARAM_INT);
            $stmt->bindParam(':Senha', $senha);
            $stmt->execute();

            return array("sucesso" => true,
                "mensagem" => SUCESSO_ALTERAR_USUARIO);
        } catch (Exception $ex) {
            return array("sucesso" => false,
                "mensagem" => ERRO_ALTERAR_USUARIO);
        }
        $conn = null;
    }

}
