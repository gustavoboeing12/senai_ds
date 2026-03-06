<?php
    $endereco = "localhost";  // Endereço do banco
    $usuario = "root";  // Nome do usuário do banco de dados
    $senha = "";  // Senha do banco de dados
    $banco = "armazenaImagem";  // Nome do banco de dados

    $conexao = new mysqli($endereco,$usuario,$senha,$banco);

    // Verificar se houve erro de conexão

    if($conexao -> connect_error){
        die("Falha na conexão".$conexao -> connect_error);
    }
?>