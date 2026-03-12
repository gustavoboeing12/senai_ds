<?php
session_start();
require_once 'conexao.php';

// Verifica se tem permissão de ADM ou secretaria
if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Buscar motivos de inatividade
$sql_motivos = "SELECT * FROM motivo_inatividade ORDER BY descricao";
$stmt_motivos = $pdo->prepare($sql_motivos);
$stmt_motivos->execute();
$motivos = $stmt_motivos->fetchAll(PDO::FETCH_ASSOC);

// Buscar dados do usuário por ID(GET)
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];
    $sql_usuario = "SELECT * FROM usuario WHERE id_usuario = :id";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    
    if(!$usuario) {
        echo "<script>alert('Usuário não encontrado!');window.location.href='gestao_usuario.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID inválido!');window.location.href='gestao_usuario.php';</script>";
    exit();
}

// // Verifica se o formulario foi enviado, se tem algo e se o metodo é igual a POST(Processa inativação)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inativar_usuario'])) {
    $id_motivo = $_POST['id_motivo'];
    $observacao = trim($_POST['observacao']);
    
    // Atualiza no banco o status do usuário
    $sql = "UPDATE usuario SET status = 'Inativo', id_motivo_inatividade = :motivo, data_inatividade = CURDATE(), observacao_inatividade = :obs WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':motivo', $id_motivo, PDO::PARAM_INT);
    $stmt->bindParam(':obs', $observacao);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "<script>alert('Usuário inativado com sucesso!');window.location.href='gestao_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao inativar usuário!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inativar Usuário - Lego Mania</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-person-x me-2"></i>Inativar Usuário</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Usuário:</strong> <?= htmlspecialchars($usuario['nome_usuario']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                        
                        <form method="POST" action="inativar_usuario.php?id=<?= $id_usuario ?>">
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
                                <label for="observacao" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3" placeholder="Detalhes adicionais sobre a inativação"></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="gestao_usuario.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" name="inativar_usuario" class="btn btn-danger">Confirmar Inativação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>