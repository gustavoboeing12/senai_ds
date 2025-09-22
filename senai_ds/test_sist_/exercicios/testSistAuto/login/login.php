<?php
   // Definir os dados de login (Futura,emte será via BD)
   $usuario_correto = "admin";
   $senha_correta = "123456";

   // Dados do formulário
   $usuario = $_POST['username'] ?? '';
   $senha = $_POST['password'] ?? '';

   // Verifica se estão corretas
   if($usuario === $usuario_correto && $senha === $senha_correta){
      header("Location: ../index/index.html");
      exit;
   } else{
      // Redireciona de volta com erro
      header("Location: login.html?error=1");
      exit;
   }
?>