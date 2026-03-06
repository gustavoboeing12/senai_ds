<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Função em PHP</title>
</head>
<body>
     <?php
        // Index 0123456789012345
        $name = "Stefanie Hatcher"; // Nome
        $length = strlen($name); // Mostra a quantidade de letras do nome
        $cmp = strcmp($name, "Brian Le"); // Compara duas strings pra ver se são iguais.
        $index = strpos($name, "e"); //  Procura onde uma string aparece dentro de outra.
        $first = substr($name, 9, 5); // Traz a partir do nono caracter as cinco letras depois
        $name = strtoupper($name); // Deixa o nome com letras maiúsculas

        echo "$name<br>";
        echo "$length<br>";
        echo "$cmp<br>";
        echo "$index<br>";
        echo "$first<br>";
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>