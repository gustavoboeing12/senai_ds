<?php
   require_once "../conPDO.php";

   $conexao = conectarBanco();

   $busca = $_GET['busca'] ?? '';

   if(!$busca){
?>
 <!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesquisar Cliente</title>
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
        <a href="../listClnt/listarCltn.php" class="btn">Listar cliente</a>
        <a href="pesqClnt.php" class="btn">Pesquisar cliente</a>
     </header>

   <div class="container mt-5">
   <h2>Pesquisar cliente</h2>
     <form action = "pesqClnt.php" method="GET" class="mb-4">
     <div class="mb-3">
      <label for="busca" class="form-label">Digite o ID ou Nome:</label>
      <input type="text" class="form-control" id="busca" name="busca" required>
     </div>
      <button type="submit" class="btn btn-primary">Pesquisar</button>
     </form>
   </div>

<?php
   exit;
   }
   if(is_numeric($busca)){
    $stmt = $conexao -> prepare("SELECT id_cliente, nome, endereco, telefone, email
                                  FROM cliente WHERE id_cliente = :id");
    $stmt -> bindParam(":id", $busca, PDO::PARAM_INT);
   } else{
     $stmt = conexao -> prepare("SELECT id_cliente, nome, telefone, email
                                 FROM cliente WHERE nome LIKE :nome");
     $buscaNome = "%$busca%";
     $stmt -> bindParam(":nome",$buscaNome, PDO::PARAM_STR);
   }
   
   $stmt -> execute();
   $clientes = $stmt -> fetchAll();
   
   if(!$clientes){
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
                <h4>Erro: Nenhum cliente encontrado.</h4>
                <a href="pesqClnt.php" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </body>
    </html>
    ');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesquisar Cliente</title>
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
        <a href="../listClnt/listarCltn.php" class="btn">Listar cliente</a>
        <a href="pesqClnt.php" class="btn">Pesquisar cliente</a>
     </header>

     <table border="1"  class="table table-dark">
       <tr>
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">Endereço</th>
          <th scope="col">Telefone</th>
          <th scope="col">E-mail</th>
          <th scope="col">Ação</th>
       </tr>
    <?php foreach($clientes as $cliente): ?>
       <tr>
           <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
           <td><?= htmlspecialchars($cliente['nome']) ?></td>
           <td><?= htmlspecialchars($cliente['endereco']) ?></td>
           <td><?= htmlspecialchars($cliente['telefone']) ?></td>
           <td><?= htmlspecialchars($cliente['email']) ?></td>
           <td>
               <a href="atualizarClnt.php?id=<?= $cliente['id_cliente'] ?>">Editar</a>
           </td>
       </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>
