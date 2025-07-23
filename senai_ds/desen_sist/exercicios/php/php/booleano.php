<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booleano em PHP</title>
</head>
<body>
     <?php
         //Declara variável numérica
         $umidade = 91;
         // Teste se $umidade for maior que 90. Retorna um booleano
         $vaiChover = ($umidade > 90);
         // Se $vaiChover é verdadeiro, faça...
         if($vaiChover){
            echo "Vai chover com toda certeza absoluta da terra!";
         } 
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>