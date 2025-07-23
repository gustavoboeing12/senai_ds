<?php
   //Variável com string
   $linha = "Vamos adicionar mais";
   // Adiciona ao arquivo "texto.txt", o que está na variável $linha
   // e o FILE_APPEND adiciona, sem apagar o resto do arquivo
   file_put_contents("texto.txt", $linha, FILE_APPEND);
?>