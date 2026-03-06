<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casos em PHP</title>
</head>
<body> 
     <?php
         //Cria string
         $s = "Lâmpada";
         //Inicia os casos com base na variável entre (), o $s = Lâmpada
         switch($s){
            //Se for 'Casa, faça:
            case "Casa":
                print "A casa é amarela";
                //Break: Saia do switch
                break;
            //O resto segue a mesma lógica
            case "Árvore":
                print "A árvore é bonita";
                break;
            case "Lâmpada":
                print "João apagou a lâmpada";
                break;
            //Se nenhum dos casos acima se aplicar
            default:
                print "Você não selecionou";
                break;
         }
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>