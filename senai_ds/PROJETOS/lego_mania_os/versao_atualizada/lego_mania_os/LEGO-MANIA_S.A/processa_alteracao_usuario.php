<?php
session_start();
require ('conexao.php');
require_once ('permissoes.php');

if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Verifica se o formulario foi enviado e se o metodo for igual a POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = !empty($_POST['nova_senha'])? 
    password_hash($_POST['nova_senha'],PASSWORD_DEFAULT): null;

    // Atualiza os dados do usuário
    if($nova_senha){
        $sql = "UPDATE usuario SET nome=:nome, email=:email, id_perfil=:id_perfil, senha=:senha
                WHERE id_usuario = :id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':senha',$nova_senha,PDO::PARAM_INT);
    } else{
        $sql = "UPDATE usuario SET nome=:nome, email=:email, id_perfil=:id_perfil
                WHERE id_usuario = :id";
        $stmt = $pdo -> prepare($sql);
    }
    // Protege e encapsula
    $stmt -> bindParam(':nome',$nome);
    $stmt -> bindParam(':email',$email);
    $stmt -> bindParam(':id_perfil',$id_perfil);
    $stmt -> bindParam(':id',$id_usuario);

    // Alerta de atualizado ou erro ao atualizar
    if($stmt -> execute()){
        echo "<script>alert('Usuário atualizado com sucesso!');window.location.href='buscar_usuario.php';</script>";
    } else{
        echo "<script>alert('Erro ao atualizar usuário!');window.location.href='alterar_usuario.php?id=$id_usuario';</script>";
    }
}

?>