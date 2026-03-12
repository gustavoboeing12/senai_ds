<?php
session_start();
require_once 'php/permissoes.php';
require_once 'conexao.php';

// Verificar permissões
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 4) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Buscar fornecedores
$fornecedores = [];
try {
    $stmt = $pdo->prepare("SELECT id_fornecedor, nome_fornecedor FROM fornecedor WHERE status = 'Ativo' ORDER BY nome_fornecedor");
    $stmt->execute();
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar fornecedores: " . $e->getMessage());
}

// Buscar peças do estoque
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);
    
    if (is_numeric($busca)) {
        $sql = "SELECT id_peca_est, nome_peca, descricao_peca, qtde, preco, qtde_minima, tipo, dt_cadastro, nome_funcionario, nome_fornecedor, peca_estoque.id_fornecedor
                FROM peca_estoque
                JOIN funcionario ON peca_estoque.id_funcionario = funcionario.id_funcionario
                JOIN fornecedor ON peca_estoque.id_fornecedor = fornecedor.id_fornecedor
                WHERE id_peca_est = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT id_peca_est, nome_peca, descricao_peca, qtde, preco, qtde_minima, tipo, dt_cadastro, nome_funcionario, nome_fornecedor, peca_estoque.id_fornecedor
                FROM peca_estoque
                JOIN funcionario ON peca_estoque.id_funcionario = funcionario.id_funcionario
                JOIN fornecedor ON peca_estoque.id_fornecedor = fornecedor.id_fornecedor
                WHERE nome_peca LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT id_peca_est, nome_peca, descricao_peca, qtde, preco, qtde_minima, tipo, dt_cadastro, nome_funcionario, nome_fornecedor, peca_estoque.id_fornecedor
            FROM peca_estoque
            JOIN funcionario ON peca_estoque.id_funcionario = funcionario.id_funcionario
            JOIN fornecedor ON peca_estoque.id_fornecedor = fornecedor.id_fornecedor";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$pecas_estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para status do estoque
function estoqueStatus($rawQtde): array {
    $qtde = (int)preg_replace('/[^\d\-]/', '', (string)$rawQtde);
    
    if ($qtde < 5) {
        return ['Baixo', 'bg-danger'];
    } elseif ($qtde <= 10) {
        return ['Médio', 'bg-warning text-dark'];
    } else {
        return ['Alto', 'bg-success'];
    }
}

// Estatísticas do estoque
$stmtIndicadores = $pdo->prepare("SELECT qtde FROM peca_estoque");
$stmtIndicadores->execute();
$pecas = $stmtIndicadores->fetchAll(PDO::FETCH_ASSOC);

$totalPecas = count($pecas);
$emEstoque = 0;
$estoqueMedio = 0;
$estoqueBaixo = 0;

foreach ($pecas as $peca) {
    $qtde = (int)$peca['qtde'];
    if ($qtde < 5) {
        $estoqueBaixo++;
    } elseif ($qtde <= 10) {
        $estoqueMedio++;
    } else {
        $emEstoque++;
    }
}

// Categorias
$categorias = ['hardware' => 0, 'perifericos' => 0, 'cabos' => 0, 'outros' => 0];
// Percorre uma array(peça no estoque) e define o tipo
foreach ($pecas_estoque as $peca) {
    $tipo = strtolower($peca['tipo']);
    if (isset($categorias[$tipo])) {
        $categorias[$tipo]++;
    }
}

// Filtros por categoria
if (!empty($_POST['categoria'])) {
    $pecas_estoque = array_filter($pecas_estoque, function($peca) {
        return $peca['tipo'] == $_POST['categoria'];
    });
}

// Filtra as peças no estoque conforme o status de quantidade selecionado no formulário.
if (!empty($_POST['status'])) {
    $pecas_estoque = array_filter($pecas_estoque, function($peca) {
        if ($_POST['status'] == 'baixo') return $peca['qtde'] < 5;
        if ($_POST['status'] == 'medio') return $peca['qtde'] >= 5 && $peca['qtde'] <= 10;
        if ($_POST['status'] == 'disponivel') return $peca['qtde'] > 10;
        return true;
    });
}

// Ordena o array de peças conforme o critério selecionado (nome ou estoque, ascendente ou descendente).
if (!empty($_POST['ordenacao'])) {
    if ($_POST['ordenacao'] == 'nome_desc') {
        usort($pecas_estoque, fn($a, $b) => strcmp($b['nome_peca'], $a['nome_peca']));
    } elseif ($_POST['ordenacao'] == 'nome_asc') {
        usort($pecas_estoque, fn($a, $b) => strcmp($a['nome_peca'], $b['nome_peca']));
    } elseif ($_POST['ordenacao'] == 'estoque_desc') {
        usort($pecas_estoque, fn($a, $b) => $b['qtde'] - $a['qtde']);
    } elseif ($_POST['ordenacao'] == 'estoque_asc') {
        usort($pecas_estoque, fn($a, $b) => $a['qtde'] - $b['qtde']);
    }
}

// Últimas peças adicionadas
$stmtUltimas = $pdo->prepare("SELECT nome_peca, tipo, qtde FROM peca_estoque ORDER BY dt_cadastro DESC LIMIT 5");
$stmtUltimas->execute();
$ultimasPecas = $stmtUltimas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Peças no Estoque - Lego Mania</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="js/validacoes_form.js"></script>
    <script src="js/ExportaRelatorios.js"></script>
    <script src="js/Exclusoes.js"></script>
    <script src="js/oculta.js"></script>

    <style>
        .card-dashboard {
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        }
        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .status-badge {
            font-size: 0.75rem;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa;
            z-index: 1000;
        }
        .sidebar-hidden {
            transform: translateX(-250px);
        }
        .main-content {
            margin-left: 250px;

        }
        .main-content-full {
            margin-left: 0;
        }

    </style>
</head>
<body class="bg-light">
    <div class="d-flex vh-100 bg-light">
        <!-- Sidebar -->
        <?php exibirMenu(); ?>

        <!-- Conteúdo principal -->
        <div class="flex-grow-1 d-flex flex-column main-content" style="padding:20px;">
            <!-- Header -->
            <nav class="navbar navbar-light bg-white shadow-sm mb-3">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="menu-toggle"><i class="bi bi-list"></i></button>
                    <!-- Botão Voltar -->
                    <button class="btn btn-outline-dark ms-2" style="transform: translateX(-340px);" onclick="history.back()">Voltar</button>
                    <span class="navbar-brand mb-0 h1">
                        <small class="text-muted">Horário atual:</small>
                        <span id="liveClock" class="badge bg-secondary"></span>
                    </span>
                </div>
            </nav>

            <div class="flex-grow-1 p-3">
                <div class="container-fluid">
                    <!-- Cabeçalho -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Relatório de Peças no Estoque</h5>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="generatePDF()">
                                <i class="bi bi-download me-1"></i> Exportar
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="window.location.href='cadastro_pecas.php'">
                                <i class="bi bi-plus-circle me-1"></i> Nova Peça
                            </button>
                        </div>
                    </div>

                    <!-- Cards de resumo -->
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2"><i class="bi bi-box-seam fs-1"></i></div>
                                    <h3><?= $totalPecas ?></h3>
                                    <p class="card-text text-muted">Total de Peças</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2"><i class="bi bi-check-circle fs-1"></i></div>
                                    <h3><?= $emEstoque ?></h3>
                                    <p class="card-text text-muted">Em Estoque</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2"><i class="bi bi-exclamation-triangle fs-1"></i></div>
                                    <h3><?= $estoqueMedio ?></h3>
                                    <p class="card-text text-muted">Estoque Médio</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-danger mb-2"><i class="bi bi-x-circle fs-1"></i></div>
                                    <h3><?= $estoqueBaixo ?></h3>
                                    <p class="card-text text-muted">Estoque Baixo</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos e Tabela -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribuição por Categoria</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoCategorias" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0"><i class="bi bi-table me-2"></i>Últimas Peças Adicionadas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="ultimas-pecas-table">
                                            <thead>
                                                <tr>
                                                    <th>Peça</th>
                                                    <th>Categoria</th>
                                                    <th>Estoque</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ultimasPecas as $peca): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($peca['nome_peca']) ?></td>
                                                    <td><?= htmlspecialchars($peca['tipo']) ?></td>
                                                    <td><?= htmlspecialchars($peca['qtde']) ?></td>
                                                    <td>
                                                        <?php [$statusTxt, $statusClass] = estoqueStatus($peca['qtde']); ?>
                                                        <span class="badge <?= $statusClass ?> status-badge"><?= $statusTxt ?></span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body py-2">
                            <form method="POST" class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Pesquisar peças..." name="busca" value="<?= htmlspecialchars($_POST['busca'] ?? '') ?>">
                                        <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- Seleciona a categoria -->
                                    <select class="form-select form-select-sm" name="categoria" onchange="this.form.submit()">
                                        <option value="" selected>Todas categorias</option>
                                        <option value="hardware" <?= ($_POST['categoria'] ?? '') == 'hardware' ? 'selected' : '' ?>>Hardware</option>
                                        <option value="perifericos" <?= ($_POST['categoria'] ?? '') == 'perifericos' ? 'selected' : '' ?>>Periféricos</option>
                                        <option value="cabos" <?= ($_POST['categoria'] ?? '') == 'cabos' ? 'selected' : '' ?>>Cabos</option>
                                        <option value="outros" <?= ($_POST['categoria'] ?? '') == 'outros' ? 'selected' : '' ?>>Outros</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <!-- Seleciona o status -->
                                    <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                        <option value="" selected>Todos status</option>
                                        <option value="disponivel" <?= ($_POST['status'] ?? '') == 'disponivel' ? 'selected' : '' ?>>Disponível</option>
                                        <option value="medio" <?= ($_POST['status'] ?? '') == 'medio' ? 'selected' : '' ?>>Estoque Médio</option>
                                        <option value="baixo" <?= ($_POST['status'] ?? '') == 'baixo' ? 'selected' : '' ?>>Estoque baixo</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <!-- Seleciona a ordem -->
                                    <select class="form-select form-select-sm" name="ordenacao" onchange="this.form.submit()">
                                        <option value="nome_asc" <?= ($_POST['ordenacao'] ?? '') == 'nome_asc' ? 'selected' : '' ?>>Nome (A-Z)</option>
                                        <option value="nome_desc" <?= ($_POST['ordenacao'] ?? '') == 'nome_desc' ? 'selected' : '' ?>>Nome (Z-A)</option>
                                        <option value="estoque_desc" <?= ($_POST['ordenacao'] ?? '') == 'estoque_desc' ? 'selected' : '' ?>>Maior Estoque</option>
                                        <option value="estoque_asc" <?= ($_POST['ordenacao'] ?? '') == 'estoque_asc' ? 'selected' : '' ?>>Menor Estoque</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-primary btn-sm w-100" type="button" onclick="limparFiltros()">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Limpar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabela de peças -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <?php if (!empty($pecas_estoque)): ?>
                                <table class="table table-hover table-striped mb-0" id="report-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 60px;">ID</th>
                                            <th>Nome</th>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th>Preço</th>
                                            <th>Fornecedor</th>
                                            <th>Estoque Atual</th>
                                            <th>Estoque Mínimo</th>
                                            <th>Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pecas_estoque as $peca): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($peca['id_peca_est']) ?></td>
                                            <td><?= htmlspecialchars($peca['nome_peca']) ?></td>
                                            <td><?= htmlspecialchars($peca['tipo']) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="toggleDescricao(this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <span class="descricao-texto d-none"><?= htmlspecialchars($peca['descricao_peca']) ?></span>
                                            </td>
                                            <td>R$ <?= number_format($peca['preco'], 2, ',', '.') ?></td>
                                            <td><?= htmlspecialchars($peca['nome_fornecedor']) ?></td>
                                            <td><?= htmlspecialchars($peca['qtde']) ?></td>
                                            <td><?= htmlspecialchars($peca['qtde_minima']) ?></td>
                                            <td>
                                                <?php [$txt, $cls] = estoqueStatus($peca['qtde'] ?? 0); ?>
                                                <span class="badge <?= $cls ?>"><?= $txt ?></span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary btn-action" title="Alterar"
                                                    onclick="editarPeca(
                                                        <?= $peca['id_peca_est'] ?>,
                                                        '<?= htmlspecialchars($peca['nome_peca'], ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($peca['tipo'], ENT_QUOTES) ?>',
                                                        <?= $peca['id_fornecedor'] ?? 0 ?>,
                                                        <?= $peca['preco'] ?? 0 ?>,
                                                        <?= $peca['qtde'] ?>,
                                                        <?= $peca['qtde_minima'] ?>
                                                    )">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger btn-action" title="Excluir" 
                                                    onclick="excluirPeca(<?= $peca['id_peca_est'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <div class="text-center p-4">
                                    <p class="text-muted">Nenhuma peça encontrada.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editar peça -->
    <div class="modal fade" id="modalAdicionarPeca" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Peça</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="processa_alteracao_peca.php" method="POST" id="formPeca">
                        <input type="hidden" name="id_peca_est" value="">
                        <div class="mb-3">
                            <label for="nome_peca" class="form-label">Nome da Peça</label>
                            <input type="text" class="form-control" id="nome_peca" name="nome_peca" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Categoria</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" selected disabled>Selecione uma categoria</option>
                                <option value="hardware">Hardware</option>
                                <option value="perifericos">Periféricos</option>
                                <option value="cabos">Cabos</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_fornecedor" class="form-label">Fornecedor</label>
                            <select class="form-select" id="id_fornecedor" name="id_fornecedor" required>
                                <option value="" selected disabled>Selecione um fornecedor</option>
                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <option value="<?= $fornecedor['id_fornecedor'] ?>"><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço da Peça</label>
                            <input type="text" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantidade" class="form-label">Estoque Atual</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantidade_minima" class="form-label">Estoque Mínimo</label>
                                <input type="number" class="form-control" id="quantidade_minima" name="quantidade_minima" min="0" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menu toggle function
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (menuToggle && sidebar) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-hidden');
                    mainContent.classList.toggle('main-content-full');
                });
            }

            // Relógio
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('pt-BR');
                document.getElementById('liveClock').textContent = timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Gráfico
            const ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');
            new Chart(ctxCategorias, {
                type: 'doughnut',
                data: {
                    labels: ['Hardware', 'Periféricos', 'Cabos', 'Outros'],
                    datasets: [{
                        data: [
                            <?= $categorias['hardware'] ?>,
                            <?= $categorias['perifericos'] ?>,
                            <?= $categorias['cabos'] ?>,
                            <?= $categorias['outros'] ?>
                        ],
                        backgroundColor: ['#0d6efd', '#6f42c1', '#20c997', '#fd7e14']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });

        function limparFiltros() {
            document.querySelector('input[name="busca"]').value = '';
            document.querySelector('select[name="categoria"]').value = '';
            document.querySelector('select[name="status"]').value = '';
            document.querySelector('select[name="ordenacao"]').value = 'nome_asc';
            document.querySelector('form').submit();
        }

        function editarPeca(id, nome, tipo, id_fornecedor, preco, qtde, qtde_minima) {
            document.querySelector('#formPeca input[name="id_peca_est"]').value = id;
            document.querySelector('#formPeca input[name="nome_peca"]').value = nome;
            document.querySelector('#formPeca select[name="tipo"]').value = String(tipo).toLowerCase();
            document.querySelector('#formPeca select[name="id_fornecedor"]').value = String(id_fornecedor);
            document.querySelector('#formPeca input[name="preco"]').value = preco;
            document.querySelector('#formPeca input[name="quantidade"]').value = qtde;
            document.querySelector('#formPeca input[name="quantidade_minima"]').value = qtde_minima;
            
            const modal = new bootstrap.Modal(document.getElementById('modalAdicionarPeca'));
            modal.show();
        }
    </script>
</body>
</html>