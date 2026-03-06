<?php
    function conectarBanco(){
        $dsn = "mysql:host=localhost;dbname=empresa;charset=utf8";
        $usuario = "root";
        $senha = "";

        try{
            $conexao = new PDO($dsn, $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            return $conexao;
        } catch (PDOException $e){
            error_log("Erro ao conectar no banco:". $e->getMessage());
            // Log sem expor erro ao usuário
            die('
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h4>Erro ao conectar ao banco.</h4>
                <a href="../index.html" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </body>
    </html>
    ');
        }
    }
?>