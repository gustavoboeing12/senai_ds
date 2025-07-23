<?php
    $turma = "Turma de desenvolvimento";
    echo "Turma: ".$turma."<br/>";
    $turma1 = explode(" ", $turma);
    echo "turma1: "; print_r ($turma1); echo "<br/>";
    $turma2 = implode("...", $turma1);
    echo "turma2: $turma2 <br/>";
?>