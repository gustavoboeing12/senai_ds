<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array01 em PHP</title>
</head>
<body>
     <?php
         //Cria uma lista(array) com várias strings
         //Tendo cada posição um índice começando do 0
         $dias = array("Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado",);
         //Imprima o array $dias mas somente no índice 1(segundo valor: Segunda)
         echo $dias[1]."<br/>";
         // Exibe de forma legível o conteúdo de arrays ou objetos.
         print_r($dias);
         echo "<br/>";
         //Mostra detalhes completos (tipo, tamanho e valor) de uma ou mais variáveis.
         var_dump($dias);
     ?>
</body>
</html>