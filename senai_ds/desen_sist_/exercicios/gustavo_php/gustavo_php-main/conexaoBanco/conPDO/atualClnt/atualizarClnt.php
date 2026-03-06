<?php
    require_once "../conPDO.php";

    $conexao = conectarBanco();

    //Obtendo o ID via GET
    $id_cliente = $_GET['id'] ?? null;
    $cliente = null;
    $msgErro = "";

    function buscarClientePorId ($id_cliente, $conexao){
        $stmt = $conexao -> prepare("SELECT id_cliente, nome, endereco, telefone, email
                                     FROM cliente WHERE id_cliente = :id");
        $stmt -> bindParam(":id", $id_cliente, PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
    }

    if($id_cliente && is_numeric($id_cliente)){
        $cliente = buscarClientePorId($id_cliente, $conexao);
        if(!$cliente){
            $msgErro = "Erro: Cliente não encontrado";
        }
    } else{
        $msgErro = "Digite o ID do cliente para bsucar os dados";
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../inicio.css"/>
    <script>
        function habilitarEdicao(campo){
            document.getElementById(campo).removeAttribute("readonly");
        }
    </script>
</head>
<body>
    <header>
        <h1>Menu de Opções</h1>
        <a href="../index.html" class="btn">Início</a>
        <a href="atualizarClnt.php" class="btn">Atualizar cliente</a>
        <a href="../deletClnt/deletarClnt.php" class="btn">Excluir cliente</a>
        <a href="../inserClnt/inserirClnt.php" class="btn">Cadastrar cliente</a>
        <a href="../listClnt/listarCltn.php" class="btn">Listar cliente</a>
        <a href="../pesqClnt/pesqClnt.php" class="btn">Pesquisar cliente</a>
    </header>

    <div class="container mt-5">
        <h2 class="mb-4">Atualizar Cliente</h2>

        <?php if($msgErro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($msgErro) ?></div>
            <form action="atualizarClnt.php" method="GET" class="mb-4">
                <div class="mb-3">
                    <label for="id" class="form-label">ID do cliente:</label>
                    <input type="number" class="form-control" id="id" name="id" required>
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        <?php else: ?>
            <form action="procAtualizacao.php" method="POST" class="border p-4 rounded-3 bg-light">
                <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="<?= htmlspecialchars($cliente['nome']) ?>" 
                           readonly onclick="habilitarEdicao('nome')">
                </div>

                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" 
                           value="<?= htmlspecialchars($cliente['endereco']) ?>" 
                           readonly onclick="habilitarEdicao('endereco')">
                </div>

                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" 
                           value="<?= htmlspecialchars($cliente['telefone']) ?>" 
                           readonly onclick="habilitarEdicao('telefone')">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($cliente['email']) ?>" 
                           readonly onclick="habilitarEdicao('email')">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">Atualizar cliente</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
</body>
</html>
