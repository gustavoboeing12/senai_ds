<?php
   require("conecta.php");

   // Obtém o d da image, da url, garantindo que seja um numero inteiro
   $id_imagem = isset($_GET['id'])? intval($_GET['id']):0;

   // Verifica se o id é válido (maior que zero)
   if($id_imagem > 0){
     // Cria o query segura usando o prepare statement
     $queryExclusao = "DELETE FROM imagens WHERE codigo = ?";

     // Prepara a query
     $stmt = $conexao -> prepare($queryExclusao);
     // Define o id com um inteiro
     $stmt -> bind_param("i", $id_imagem);

     // Executa a exclusão
     if($stmt -> execute()){
        echo "Imagem excluida com sucesso!";
     } else{
        die("Erro ao excluir a imagem: ".$stmt -> error);
     }

     // Fecha a consulta
     $stmt -> close();
   } else{
     echo "id inválido";
   }

   // Redireciona para a index.php e garante que o script pare
   header("location: index.php");
   exit();
?>