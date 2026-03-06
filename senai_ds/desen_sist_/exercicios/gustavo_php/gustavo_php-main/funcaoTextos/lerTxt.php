<?php
    //Pega os textos do arquivo txt e joga na variável $texto
    $texto = file_get_contents("texto.txt");
    // Função para ler o texto (nl2br) do $texto e imprima (echo)
    echo nl2br($texto);
    echo "<br/>";
    // Imprime a mensagem e diz qual tipo de dado é e o tamanho
    var_dump($texto);
?>