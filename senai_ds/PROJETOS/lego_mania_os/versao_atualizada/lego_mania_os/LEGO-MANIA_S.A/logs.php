<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// Consulta para obter todos os logs na tabela(logs_acao)
$sql = "SELECT l.*, u.nome_usuario, p.nome_perfil
        FROM log_acao l
        JOIN usuario u ON l.id_usuario = u.id_usuario
        JOIN perfil p ON l.id_perfil = p.id_perfil
        ORDER BY l.data_hora DESC";

$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Logs do Sistema - Lego Mania</title>
    <style>
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }
        .badge-log {
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    
<div class="d-flex vh-100 bg-light">
   <!-- Sidebar -->
   <?php exibirMenu(); ?>

    <!-- Conteúdo principal -->
    <div class="flex-grow-1 d-flex flex-column">
        <!-- Header -->
        <nav class="navbar navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="btn btn-dark" id="menu-toggle"><i class="bi bi-list"></i></button>
                <!-- Botão voltar -->
                <button class="btn btn-outline-dark" style="position: absolute; margin-left: 60px;" onclick="history.back()">Voltar</button>
                <span class="navbar-brand mb-0 h1">
                    <small class="text-muted">Horário atual:</small>
                    <span id="liveClock" class="badge bg-secondary"></span>
                </span>
            </div>
        </nav>

        <!-- Conteúdo -->
        <div class="flex-grow-1 p-3" style="overflow-y: auto;">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Registros de Log do Sistema</h5>
                    <div>
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Atualizar
                        </button>
                        <?php if ($_SESSION['perfil'] == 1): ?>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmarLimpezaLogs()">
                                <i class="bi bi-trash"></i> Limpar Logs
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Usuário</th>
                                        <th>Perfil</th>
                                        <th>Ação</th>
                                        <th>Tabela</th>
                                        <th>ID Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)): ?>
                                        <?php foreach ($logs as $log): ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i:s', strtotime($log['data_hora'])) ?></td>
                                                <td><?= htmlspecialchars($log['nome_usuario']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary badge-log">
                                                        <?= htmlspecialchars($log['nome_perfil']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($log['acao']) ?></td>
                                                <td>
                                                    <span class="badge bg-info text-dark badge-log">
                                                        <?= htmlspecialchars($log['tabela_afetada']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($log['id_registro']): ?>
                                                        <span class="badge bg-primary badge-log">
                                                            #<?= htmlspecialchars($log['id_registro']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                                Nenhum registro de log encontrado
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($logs)): ?>
                    <div class="card-footer py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    Total de <?= count($logs) ?> registro(s) de log
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Alternar exibição do menu
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("sidebar").classList.toggle("d-none");
    });

    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR');
        document.getElementById('liveClock').textContent = timeString;
    }
    
    setInterval(updateClock, 1000);
    updateClock(); // Inicializa imediatamente

    function confirmarLimpezaLogs() {
    if (confirm('Tem certeza que deseja limpar TODOS os logs? Esta ação não pode ser desfeita e todos os registros serão permanentemente excluídos.')) {
        window.location.href = 'limpar_logs.php';
    }
}
</script>

</body>
</html>