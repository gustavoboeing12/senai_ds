<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>if else em PHP</title>
</head>
<body>
     <?php
         // Declara nome com string e depois com valor nulo
         $nome = "Paula Fernandes";
         $nome = NULL;
         // Se o nome tem algum valor que não nulo(isset), printe:
         if(isset($nome)){
            print "This line isn't going to be reached.";
         }
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>