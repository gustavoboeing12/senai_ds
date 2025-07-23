<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combinação em PHP</title>
</head>
<body>
     <?php
        //Função usada para definir fuso horário padrão
        date_default_timezone_set('America/Los_Angeles');
        //Atribui à variável $data_hoje a data atual no formato dia/mês/ano com dois dígitos.
        $data_hoje = date ("d/m/y", time());
     ?>
     <!--Parágrafo puxando a função php para a data-->
     <p align="center"> Hoje é dia <?php echo $data_hoje; ?> </p>

     
     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>