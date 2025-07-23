<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array06 Dicionário em PHP</title>
</head>
<body>
     <?php
         echo "<br/>";
         $amazonProducts = array(
         array("Código" => "Livro", "Descrição" => "Livros", "Preço" => 50),
         array("Código" => "DVDs", "Descrição" => "Filmes", "Preço" => 15),
         array("Código" => "CDs", "Descrição" => "Música", "Preço" => 20),
         );
         for ($linha = 0; $linha < 3; $linha++){ ?>
         <p>
             | <?= $amazonProducts[$linha]["Código"] ?> 
             | <?= $amazonProducts[$linha]["Descrição"] ?> 
             | <?= $amazonProducts[$linha]["Preço"] ?> 
         </p>
     <?php
         }
      ?>
</body>
</html>