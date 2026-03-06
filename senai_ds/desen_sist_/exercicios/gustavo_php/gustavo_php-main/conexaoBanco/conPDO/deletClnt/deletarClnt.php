<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../inicio.css"/>
</head>
<body>
     <header>
        <h1>Menu de Opções</h1>
        <a href="../index.html" class="btn">Início</a>
        <a href="../atualClnt/atualizarClnt.php" class="btn">Atualizar cliente</a>
        <a href="deletarClnt.php" class="btn">Excluir cliente</a>
        <a href="../inserClnt/inserirClnt.php" class="btn">Cadastrar cliente</a>
        <a href="../listClnt/listarCltn.php" class="btn">Listar cliente</a>
        <a href="../pesqClnt/pesqClnt.php" class="btn">Pesquisar cliente</a>
    </header>

    <div class="container mt-5">
       <h2>Excluir cliente</h2>
       <form action="procDelecao.php" method="POST" class="mb-4">
         <div class="mb-3">
            <label for="id" class="form-label">ID do cliente:</label>
            <input type="number" class="form-control" id="id" name="id" required>
        </div>
        <button type="submit" class="btn btn-primary">Excluir cliente</button>
    </div>

</body>
</html>