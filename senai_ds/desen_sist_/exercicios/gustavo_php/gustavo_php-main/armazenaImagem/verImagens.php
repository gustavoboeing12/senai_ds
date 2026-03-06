<?php
   error_reporting(E_ALL);
   ini_set('display_errors',1);
   //Limpa qualquer saída inesperada antes do header
   ob_clean();

   // Inclui a conexão com o banco de dados
   require("conecta.php");

   // Obtém o id da imagem da url, garantindo que seja um número inteiro
   $id_imagem = isset($_GET['id'])? intval($_GET['id']):0;

   //  Cria a consulta para buscar a imagem no banco de dados
   $querySelecionaPorCodigo = "SELECT imagem,tipo_imagem 
                               FROM imagens WHERE codigo = ?";

   // Usa prepare statement para maior segurança
   $stmt = $conexao -> prepare($querySelecionaPorCodigo);
   $stmt -> bind_param("i",$id_imagem);
   $stmt -> execute();
   $resultado = $stmt -> get_result();

   // Verifica se a imagem existe no banco de dados
   if($resultado -> num_rows > 0){
     $imagem = $resultado -> fetch_object();

     // Define o tipo correto da imagem(fallback para jpeg caso esteja vazio)
     $tipoimagem = !empty($imagem -> tipo_imagem)? $imagem -> tipo_imagem : "imagem/jpeg";
     header("content-type: ".$tipoimagem);

     // Exibe a imagem armazenada no banco de dados
     echo $imagem -> imagem;
   } else{
     echo "Imagem não encontrada";
   }

   // Fecha a consulta
   $stmt = close();
?>
