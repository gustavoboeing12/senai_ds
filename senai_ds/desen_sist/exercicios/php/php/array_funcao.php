<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array07 Função em PHP</title>
</head>
<body>
     <?php
         //Cria variavel
         //Rand sorteia um número entre(nesse caso) 0 e 5
         $sorteio = rand(0,5);
         //Printa o resultado acima
         echo "Sorteado: $sorteio <hr/>";
         //Pega o $vetor e mescla os arrays em um só
         $vetor = array_merge(range(0,10),
                              
                              range($sorteio,10,2),
                              array($sorteio));
         print_r($vetor);
         echo "<hr/>";
         shuffle($vetor);
         print_r($vetor);
         echo "<hr/>"
     ?>
</body>
</html>