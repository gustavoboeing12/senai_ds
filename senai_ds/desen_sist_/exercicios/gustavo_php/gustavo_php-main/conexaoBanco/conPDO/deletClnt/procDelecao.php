<?php
   require_once "../conPDO.php";

   if($_SERVER["REQUEST_METHOD"] == "POST"){
    $conexao = conectarBanco();

    $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

    if(!$id){
        die(' <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h4>Erro: ID inválido.</h4>
                <a href="deletarClnt.php" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </body>
    </html>');
    }
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $conexao -> prepare($sql);
    $stmt -> bram(":id", $id,PDO::PARAM_INT);

    try{
        $stmt -> execute();
        echo ' <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h4>Cliente excluído com sucesso.</h4>
                <a href="deletarClnt.php" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </body>
    </html>';
    } catch (PDOException $e){
        error_log("Erro ao excluir cliente: ". $e->getMessage());
        echo ' <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h4>Erro: Erro ao excluir cliente.</h4>
                <a href="deletarClnt.php" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </body>
    </html>';
    }
}
?>