<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     <?php
         $age = 16;
         // Concatenação pelos pontos
         print "You are " . $age . " years old.<br>"; 
         // Aspas duplas permitem a variável entrar
         print "You are $age years old.<br>"; 
         // Aspas simples printam exatamente o que digitar.
         print 'You are $age years old.<br>'
     ?>
     <?php
        //Aplicando informações acima
        $cidade = "Curitiba";
        $estado = "PR";
        $idade = 325;
        $frase_capital = "A cidade de $cidade é a capital do $estado";
        $frase_idade = "$cidade tem mais de $idade anos";
        echo "<h3>$frase_capital</h3>";
        echo "<h4>$frase_idade</h4>";
     ?>
     <?php
       // Não encontra a idade por ter string colada(th)
       print "Today is your $ageth birthday.<br>";
       // Separa a variável da string (th)
       print "Today is your {$age}th birthday.<br>";
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>