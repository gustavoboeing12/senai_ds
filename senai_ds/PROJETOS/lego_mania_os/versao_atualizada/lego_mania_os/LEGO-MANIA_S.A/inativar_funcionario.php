<?php
session_start();
require_once 'conexao.php';

// Verifica se tem permissão de ADM ou de secretaria
if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Buscar motivos de inatividade 
$sql_motivos = "SELECT * FROM motivo_inatividade ORDER BY descricao";
$stmt_motivos = $pdo->prepare($sql_motivos);
$stmt_motivos->execute();
$motivos = $stmt_motivos->fetchAll(PDO::FETCH_ASSOC);

// Buscar dados do funcionário por ID
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_funcionario = $_GET['id'];
    $sql_funcionario = "SELECT * FROM funcionario WHERE id_funcionario = :id";
    $stmt_funcionario = $pdo->prepare($sql_funcionario);
    $stmt_funcionario->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
    $stmt_funcionario->execute();
    $funcionario = $stmt_funcionario->fetch(PDO::FETCH_ASSOC);
    
    if(!$funcionario) {
        echo "<script>alert('Funcionário não encontrado!');window.location.href='gestao_funcionario.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID inválido!');window.location.href='gestao_funcionario.php';</script>";
    exit();
}

// Verifica se o formulario foi enviado, se tem algo e se o metodo é igual a POST(Processa inativação)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inativar_funcionario'])) {
    $id_motivo = $_POST['id_motivo'];
    $observacao = trim($_POST['observacao']);
    
    // Altera no banco status do funcionario
    $sql = "UPDATE funcionario SET status = 'Inativo', id_motivo_inatividade = :motivo, data_inatividade = CURDATE(), observacao_inatividade = :obs WHERE id_funcionario = :id";
    // Protege e encapsula
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':motivo', $id_motivo, PDO::PARAM_INT);
    $stmt->bindParam(':obs', $observacao);
    $stmt->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "<script>alert('Funcionário inativado com sucesso!');window.location.href='gestao_funcionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao inativar funcionário!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inativar Funcionário - Lego Mania</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-person-x me-2"></i>Inativar Funcionário</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Funcionário:</strong> <?= htmlspecialchars($funcionario['nome_funcionario']) ?></p>
                        <p><strong>CPF:</strong> <?= htmlspecialchars($funcionario['cpf_funcionario']) ?></p>
                        
                        <form method="POST" action="inativar_funcionario.php?id=<?= $id_funcionario ?>">
                            <div class="mb-3">
                                <label for="id_motivo" class="form-label">Motivo da Inativação *</label>
                                <!-- Seleciona o motivo da inatividade -->
                                <select class="form-select" id="id_motivo" name="id_motivo" required>
                                    <option value="">Selecione um motivo</option>
                                    <?php foreach($motivos as $motivo): ?>
                                        <option value="<?= $motivo['id_motivo'] ?>"><?= htmlspecialchars($motivo['descricao']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <!-- Descreve a observação da inatividade -->
                                <label for="observacao" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3" placeholder="Detalhes adicionais sobre a inativação"></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="gestao_funcionario.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" name="inativar_funcionario" class="btn btn-danger">Confirmar Inativação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>