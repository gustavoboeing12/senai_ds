<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabuada em PHP</title>
</head>
<body>
     <?php
     //Tabuada em php do 1 ao 10
         // Primeiro Para: variável que será multiplicada('1' x 2 = 2)
         for ($n = 1; $n <= 10; $n++){
            //Segundo Para: variável multiplicadora(1 x '2' = 2)
            for ($m = 2; $m <= 10; $m++){
                //Printa a estrutura da tabuada e seu resultado
                print "$n x $m = ".$n * $m. "<br/>";
          }
          //Espaça cada tabuada
          print "<br/>";
        }
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>