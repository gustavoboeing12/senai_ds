<?php
     $linhas = file("texto.txt", FILE_IGNORE_NEW_LINES);
     var_dump($linhas);
     foreach ($linhas as linha_num => $linha){
        echo "Linha #{$linha_num} : ".$linha." <br>";
     }
?>