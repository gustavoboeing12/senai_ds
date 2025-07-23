<?php
    foreach(file("livros.txt") as $livros){
        list($titulo, $autor) = explode(",", $livros);
?>
<p> Título: <?= $titulo ?>, Autor: <?= $autor ?> </p>
<?php
   }
?>