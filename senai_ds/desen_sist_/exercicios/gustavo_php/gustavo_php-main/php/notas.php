<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booleano em PHP para notas</title>
</head>
<body>
     <?php
         //Declara variável numérica
         $notaAluno = 9.0;
         //Se a nota do aluno for maior que sete
         if($notaAluno >= 7.0){
            echo "O aluno está aprovado!";
         //Senão, se a nota do aluno é menor que sete E maior ou igual a quatro
         } elseif ($notaAluno < 7.0 && $notaAluno>=6.0){
            echo "O aluno está de recuperação!";
         //E se não for nenhuma das demais
         } else{
            echo "O aluno está reprovado!";
         }
     ?>


     <center>
        <address>
              Gustavo Fratoni Boeing
        </address>
     </center>
</body>
</html>