<?php
   require_once "conMysqli.php";

   // Estabelece a conexão
   $conexao = conectadb();

   // Define a consulta SQL para buscar clientes
   $sql = "SELECT id_cliente, nome, email FROM cliente";

   // Executa a consulta no banco
   $result = $conexao -> query($sql);

   // Verifica se há resultados na consultas;
   if($result -> num_rows > 0){
      // Itera sobre os resultados e exibe os dados
        while($linha = $result -> fetch_assoc()){
            echo "ID: " .$linha["id_cliente"]. "- Nome: ".$linha["nome"]. 
                 "- Email: ".$linha["email"]."</br>";
          }
       } else{
           echo "Nenhum cliente encontrado.";
       }

   // Fecha a conexão com o banco de dados
   $conexao -> close();
?>