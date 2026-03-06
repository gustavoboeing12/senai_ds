<?php
    // Habilita relatório detalhado de erros no mysql
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    /*
      Função para conectar ao banco de dados
      Retorna um objeto de conexão MySQLi ou interrompe o script em caso de erro.
    */
    function conectadb(){
        // Configuração do banco de dados
        $endereco = "localhost";  // Endereço do banco
        $usuario = "root";  // Nome do usuário do banco de dados
        $senha = "";  // Senha do banco de dados
        $banco = "empresa";  // Nome do banco de dados
    

    try{
        // Criação da conexão
        $con = new mysqli($endereco, $usuario, $senha, $banco);

        // Definição de conjunto de caracteres para evitar problemas de acentuaçãi
        $con -> set_charset("utf8mb4");
        // Retorna o objeto de conexão
        return $con;
    } catch (Exception $e){
        // Exibe uma mensagem de erro e encerra o script
        die("Erro na conexão:".$e->getMessage());
    }
    }
?>