<?php
    require '../conPDO.php';

    $conexao = conectarBanco();
    $stmt = $conexao -> prepare("SELECT * FROM cliente");
    $stmt -> execute();
    $clientes = $stmt -> fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../inicio.css"/>
</head>
<body>
     <header>
        <h1>Menu de Opções</h1>
        <a href="../index.html" class="btn">Início</a>
        <a href="../atualClnt/atualizarClnt.php" class="btn">Atualizar cliente</a>
        <a href="../deletClnt/deletarClnt.php" class="btn">Excluir cliente</a>
        <a href="../inserClnt/inserirClnt.php" class="btn">Cadastrar cliente</a>
        <a href="listarCltn.php" class="btn">Listar cliente</a>
        <a href="../pesqClnt/pesqClnt.php" class="btn">Pesquisar cliente</a>
     </header>

     <h2> Lista de clientes </h2>
     <table border="1" class="table table-dark">
        <tr>
           <th scope="col">ID</th>
           <th scope="col">Nome</th>
           <th scope="col">Endereço</th>
           <th scope="col">Telefone</th>
           <th scope="col">E-mail</th>
        </tr>
    <?php foreach ($clientes as $cliente): ?>
        <tr>
           <td><?= htmlspecialchars($cliente["id_cliente"])?></td>
           <td><?= htmlspecialchars($cliente["nome"])?></td>
           <td><?= htmlspecialchars($cliente["endereco"])?></td>
           <td><?= htmlspecialchars($cliente["telefone"])?></td>
           <td><?= htmlspecialchars($cliente["email"])?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>