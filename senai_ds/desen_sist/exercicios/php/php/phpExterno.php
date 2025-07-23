<?php
         if(isset($_GET['nome'])&& isset($_GET['idade'])){
            echo "Recebido o cliente". $_GET['nome'];
            echo "Que tem ".$_GET['idade']. " anos.";
         }
     ?>