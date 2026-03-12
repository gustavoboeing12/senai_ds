<?php
session_start();
require_once 'conexao.php';

// Verificar permissões
if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Buscar dados do usuário
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = $_GET['id'];
    $sql_cliente = "SELECT * FROM cliente WHERE id_cliente = :id";
    $stmt_cliente = $pdo->prepare($sql_cliente);
    $stmt_cliente->bindParam(':id', $id_cliente, PDO::PARAM_INT);
    $stmt_cliente->execute();
    $cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
    
    if(!$cliente) {
        echo "<script>alert('Cliente não encontrado!');window.location.href='gestao_cliente.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID inválido!');window.location.href='gestao_cliente.php';</script>";
    exit();
}

// Processar inativação
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inativar_cliente'])) {
    $observacao = trim($_POST['observacao']);
    
    // Atualiza no banco o status do cliente e observação da inatividade
    $sql = "UPDATE cliente SET status = 'Inativo', data_inatividade = CURDATE(), observacao_inatividade = :obs 
            WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':obs', $observacao);
    $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "<script>alert('Cliente inativado com sucesso!');window.location.href='gestao_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao inativar cliente!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inativar cliente - Lego Mania</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-person-x me-2"></i>Inativar cliente</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Usuário:</strong> <?= htmlspecialchars($cliente['nome_cliente']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
                        
                        <form method="POST" action="inativar_cliente.php?id=<?= $id_cliente ?>"> 
                            <div class="mb-3">
                                <label for="observacao" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3" placeholder="Detalhes adicionais sobre a inativação"></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="gestao_usuario.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" name="inativar_cliente" class="btn btn-danger">Confirmar Inativação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>