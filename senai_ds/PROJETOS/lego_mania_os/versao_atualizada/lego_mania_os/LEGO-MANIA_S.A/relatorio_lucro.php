<?php
session_start();
require_once 'php/permissoes.php';
require_once 'conexao.php';

if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 4){
    echo "<script> alert ('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Obter parâmetros de filtro se existirem
$filtro_ano = isset($_GET['ano']) ? $_GET['ano'] : '';
$filtro_semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';
$filtro_trimestre = isset($_GET['trimestre']) ? $_GET['trimestre'] : '';
$filtro_mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$filtro_data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$filtro_data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// Construir a consulta SQL base
$sql = "
    SELECT 
        no.id_ordem,
        no.nome_client_ordem,
        no.valor_total as valor_ordem,
        no.dt_recebimento,
        no.status_ordem,
        COALESCE(SUM(pe.preco * osp.quantidade), 0) as custo_pecas,
        (no.valor_total - COALESCE(SUM(pe.preco * osp.quantidade), 0)) as lucro_bruto,
        c.nome_cliente,
        u.nome_usuario as nome_tecnico
    FROM nova_ordem no
    LEFT JOIN ordem_servico_pecas osp ON no.id_ordem = osp.id_ordem
    LEFT JOIN peca_estoque pe ON osp.id_peca_est = pe.id_peca_est
    LEFT JOIN cliente c ON no.id_cliente = c.id_cliente
    LEFT JOIN usuario u ON no.tecnico = u.id_usuario
    WHERE no.status_ordem = 'Concluído'
";

// Adicionar condições de filtro se existirem
$conditions = [];

if (!empty($filtro_ano)) {
    $conditions[] = "YEAR(no.dt_recebimento) = " . intval($filtro_ano);
}

if (!empty($filtro_semestre)) {
    $semestre = intval($filtro_semestre);
    if ($semestre == 1) {
        $conditions[] = "MONTH(no.dt_recebimento) BETWEEN 1 AND 6";
    } elseif ($semestre == 2) {
        $conditions[] = "MONTH(no.dt_recebimento) BETWEEN 7 AND 12";
    }
}

if (!empty($filtro_trimestre)) {
    $trimestre = intval($filtro_trimestre);
    if ($trimestre >= 1 && $trimestre <= 4) {
        $start_month = (($trimestre - 1) * 3) + 1;
        $end_month = $start_month + 2;
        $conditions[] = "MONTH(no.dt_recebimento) BETWEEN $start_month AND $end_month";
    }
}

if (!empty($filtro_mes)) {
    $conditions[] = "MONTH(no.dt_recebimento) = " . intval($filtro_mes);
}

if (!empty($filtro_data_inicio)) {
    $conditions[] = "no.dt_recebimento >= '" . $filtro_data_inicio . "'";
}

if (!empty($filtro_data_fim)) {
    $conditions[] = "no.dt_recebimento <= '" . $filtro_data_fim . " 23:59:59'";
}

// Adicionar condições à consulta SQL
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

// Finalizar a consulta
$sql .= " GROUP BY no.id_ordem ORDER BY no.dt_recebimento DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular totais
$total_valor_ordens = 0;
$total_custo_pecas = 0;
$total_lucro_bruto = 0;

foreach ($ordens as $ordem) {
    $total_valor_ordens += $ordem['valor_ordem'];
    $total_custo_pecas += $ordem['custo_pecas'];
    $total_lucro_bruto += $ordem['lucro_bruto'];
}

// Calcular margem de lucro
$margem_lucro = $total_valor_ordens > 0 ? ($total_lucro_bruto / $total_valor_ordens) * 100 : 0;

// Preparar dados para gráficos
$dados_grafico_mensal = [];
$dados_grafico_categorias = ['0-100' => 0, '100-500' => 0, '500-1000' => 0, '1000+' => 0];

foreach ($ordens as $ordem) {
    // Agrupar por mês
    $mes = date('Y-m', strtotime($ordem['dt_recebimento']));
    if (!isset($dados_grafico_mensal[$mes])) {
        $dados_grafico_mensal[$mes] = [
            'valor_ordens' => 0,
            'custo_pecas' => 0,
            'lucro' => 0
        ];
    }
    
    $dados_grafico_mensal[$mes]['valor_ordens'] += $ordem['valor_ordem'];
    $dados_grafico_mensal[$mes]['custo_pecas'] += $ordem['custo_pecas'];
    $dados_grafico_mensal[$mes]['lucro'] += $ordem['lucro_bruto'];
    
    // Classifica o lucro bruto em faixas de valores e conta quantas ordens pertencem a cada categoria.
    if ($ordem['lucro_bruto'] <= 100) {
        $dados_grafico_categorias['0-100']++;
    } elseif ($ordem['lucro_bruto'] <= 500) {
        $dados_grafico_categorias['100-500']++;
    } elseif ($ordem['lucro_bruto'] <= 1000) {
        $dados_grafico_categorias['500-1000']++;
    } else {
        $dados_grafico_categorias['1000+']++;
    }
}

// Ordenar por mês
ksort($dados_grafico_mensal);

// Obter as 10 ordens mais lucrativas
$ordens_lucrativas = $ordens;
usort($ordens_lucrativas, function($a, $b) {
    return $b['lucro_bruto'] - $a['lucro_bruto'];
});
$top_ordens = array_slice($ordens_lucrativas, 0, 10);

// Obter anos disponíveis para o filtro
$sql_anos = "SELECT DISTINCT YEAR(dt_recebimento) as ano FROM nova_ordem WHERE status_ordem = 'Concluído' ORDER BY ano DESC";
$stmt_anos = $pdo->prepare($sql_anos);
$stmt_anos->execute();
$anos_disponiveis = $stmt_anos->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Lucros - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <style>
        .card-dashboard {
            transition: transform 0.2s;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        }
        .positive-value {
            color: #198754;
            font-weight: bold;
        }
        .negative-value {
            color: #dc3545;
            font-weight: bold;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .progress-bar {
            transition: width 1s ease-in-out;
        }
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .filter-row {
            margin-bottom: 0.5rem;
        }
        .filter-badge {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex vh-100 bg-light">
        <!-- Sidebar -->
        <?php exibirMenu(); ?>

        <!-- Conteúdo principal -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- Header -->
            <nav class="navbar navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="menu-toggle"><i class="bi bi-list"></i></button>
                    <button class="btn btn-outline-dark" style="position: absolute; margin-left: 60px;" onclick="history.back()">Voltar</button>
                    <span class="navbar-brand mb-0 h1">
                        <small class="text-muted">Horário atual:</small>
                        <span id="liveClock" class="badge bg-secondary"></span>
                    </span>
                </div>
            </nav>

            <!-- Conteúdo - Relatório -->
            <div class="flex-grow-1 p-3" style="overflow-y: auto;">
                <div class="container-fluid">
                    <!-- Cabeçalho -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Relatório de Lucros - Ordens de Serviço</h5>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="generatePDF()">
                                <i class="bi bi-download me-1"></i> Exportar PDF
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Imprimir
                            </button>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtros</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="" class="filter-form">
                                <div class="row filter-row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Ano</label>
                                        <select class="form-select form-select-sm" name="ano" onchange="this.form.submit()">
                                            <option value="">Todos os anos</option>
                                            <?php foreach ($anos_disponiveis as $ano): ?>
                                                <option value="<?= $ano ?>" <?= $filtro_ano == $ano ? 'selected' : '' ?>><?= $ano ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Semestre</label>
                                        <select class="form-select form-select-sm" name="semestre" onchange="this.form.submit()">
                                            <option value="">Todos os semestres</option>
                                            <option value="1" <?= $filtro_semestre == '1' ? 'selected' : '' ?>>1º Semestre</option>
                                            <option value="2" <?= $filtro_semestre == '2' ? 'selected' : '' ?>>2º Semestre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Trimestre</label>
                                        <select class="form-select form-select-sm" name="trimestre" onchange="this.form.submit()">
                                            <option value="">Todos os trimestres</option>
                                            <option value="1" <?= $filtro_trimestre == '1' ? 'selected' : '' ?>>1º Trimestre</option>
                                            <option value="2" <?= $filtro_trimestre == '2' ? 'selected' : '' ?>>2º Trimestre</option>
                                            <option value="3" <?= $filtro_trimestre == '3' ? 'selected' : '' ?>>3º Trimestre</option>
                                            <option value="4" <?= $filtro_trimestre == '4' ? 'selected' : '' ?>>4º Trimestre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Mês</label>
                                        <select class="form-select form-select-sm" name="mes" onchange="this.form.submit()">
                                            <option value="">Todos os meses</option>
                                            <option value="1" <?= $filtro_mes == '1' ? 'selected' : '' ?>>Janeiro</option>
                                            <option value="2" <?= $filtro_mes == '2' ? 'selected' : '' ?>>Fevereiro</option>
                                            <option value="3" <?= $filtro_mes == '3' ? 'selected' : '' ?>>Março</option>
                                            <option value="4" <?= $filtro_mes == '4' ? 'selected' : '' ?>>Abril</option>
                                            <option value="5" <?= $filtro_mes == '5' ? 'selected' : '' ?>>Maio</option>
                                            <option value="6" <?= $filtro_mes == '6' ? 'selected' : '' ?>>Junho</option>
                                            <option value="7" <?= $filtro_mes == '7' ? 'selected' : '' ?>>Julio</option>
                                            <option value="8" <?= $filtro_mes == '8' ? 'selected' : '' ?>>Agosto</option>
                                            <option value="9" <?= $filtro_mes == '9' ? 'selected' : '' ?>>Setembro</option>
                                            <option value="10" <?= $filtro_mes == '10' ? 'selected' : '' ?>>Outubro</option>
                                            <option value="11" <?= $filtro_mes == '11' ? 'selected' : '' ?>>Novembro</option>
                                            <option value="12" <?= $filtro_mes == '12' ? 'selected' : '' ?>>Dezembro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row filter-row">
                                    <div class="col-md-5 mb-2">
                                        <label class="form-label">Data Início</label>
                                        <input type="date" class="form-control form-control-sm" name="data_inicio" value="<?= $filtro_data_inicio ?>" onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <label class="form-label">Data Fim</label>
                                        <input type="date" class="form-control form-control-sm" name="data_fim" value="<?= $filtro_data_fim ?>" onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-2 mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="limparFiltros()">
                                            <i class="bi bi-x-circle me-1"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Badges de filtros ativos -->
                            <?php if ($filtro_ano || $filtro_semestre || $filtro_trimestre || $filtro_mes || $filtro_data_inicio || $filtro_data_fim): ?>
                            <div class="mt-2">
                                <small class="text-muted">Filtros ativos:</small>
                                <?php if ($filtro_ano): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('ano')">
                                        Ano: <?= $filtro_ano ?> <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($filtro_semestre): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('semestre')">
                                        Semestre: <?= $filtro_semestre ?>º <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($filtro_trimestre): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('trimestre')">
                                        Trimestre: <?= $filtro_trimestre ?>º <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($filtro_mes): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('mes')">
                                        Mês: <?= [
                                            '1' => 'Janeiro', '2' => 'Fevereiro', '3' => 'Março', 
                                            '4' => 'Abril', '5' => 'Maio', '6' => 'Junho',
                                            '7' => 'Julho', '8' => 'Agosto', '9' => 'Setembro',
                                            '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                                        ][$filtro_mes] ?> <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($filtro_data_inicio): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('data_inicio')">
                                        De: <?= date('d/m/Y', strtotime($filtro_data_inicio)) ?> <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($filtro_data_fim): ?>
                                    <span class="badge bg-primary filter-badge" onclick="removerFiltro('data_fim')">
                                        Até: <?= date('d/m/Y', strtotime($filtro_data_fim)) ?> <i class="bi bi-x ms-1"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Cards de resumo -->
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-currency-dollar fs-1"></i>
                                    </div>
                                    <h4 class="card-title">R$ <?= number_format($total_valor_ordens, 2, ',', '.') ?></h4>
                                    <p class="card-text text-muted">Faturamento Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2">
                                        <i class="bi bi-box-seam fs-1"></i>
                                    </div>
                                    <h4 class="card-title">R$ <?= number_format($total_custo_pecas, 2, ',', '.') ?></h4>
                                    <p class="card-text text-muted">Custo com Peças</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="bi bi-currency-exchange fs-1"></i>
                                    </div>
                                    <h4 class="card-title">R$ <?= number_format($total_lucro_bruto, 2, ',', '.') ?></h4>
                                    <p class="card-text text-muted">Lucro Líquido Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2">
                                        <i class="bi bi-percent fs-1"></i>
                                    </div>
                                    <h4 class="card-title"><?= number_format($margem_lucro, 2, ',', '.') ?>%</h4>
                                    <p class="card-text text-muted">Margem de Lucro</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Evolução Mensal de Lucros</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoEvolucaoMensal" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribuição por Faixa de Lucro</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoFaixaLucro" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top 10 ordens mais lucrativas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Top 10 Ordens Mais Lucrativas</h6>
                                    <span class="badge bg-primary"><?= count($top_ordens) ?> ordens</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID Ordem</th>
                                                    <th>Cliente</th>
                                                    <th>Data</th>
                                                    <th>Valor Total</th>
                                                    <th>Custo Peças (R$)</th>
                                                    <th>Lucro(Total - Custo peças)</th>
                                                    <th>Margem</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($top_ordens)): ?>
                                                    <?php foreach ($top_ordens as $ordem): 
                                                        $margem_ordem = $ordem['valor_ordem'] > 0 ? ($ordem['lucro_bruto'] / $ordem['valor_ordem']) * 100 : 0;
                                                        $margem_class = $margem_ordem >= 20 ? 'text-success' : ($margem_ordem >= 10 ? 'text-warning' : 'text-danger');
                                                    ?>
                                                    <tr>
                                                        <td><?= $ordem['id_ordem'] ?></td>
                                                        <td><?= htmlspecialchars($ordem['nome_cliente'] ?: $ordem['nome_client_ordem']) ?></td>
                                                        <td><?= date('d/m/Y', strtotime($ordem['dt_recebimento'])) ?></td>
                                                        <td>R$ <?= number_format($ordem['valor_ordem'], 2, ',', '.') ?></td>
                                                        <td>R$ <?= number_format($ordem['custo_pecas'], 2, ',', '.') ?></td>
                                                        <td class="positive-value">R$ <?= number_format($ordem['lucro_bruto'], 2, ',', '.') ?></td>
                                                        <td class="<?= $margem_class ?>"><?= number_format($margem_ordem, 2, ',', '.') ?>%</td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">Nenhuma ordem encontrada</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela completa de ordens -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0"><i class="bi bi-table me-2"></i>Todas as Ordens Concluídas</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="tabelaOrdens">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Técnico</th>
                                            <th>Data</th>
                                            <th>Valor Total</th>
                                            <th>Custo Peças (R$)</th>
                                            <th>Lucro(Total - Custo peças)</th>
                                            <th>Margem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($ordens)): ?>
                                            <?php foreach ($ordens as $ordem): 
                                                $margem_ordem = $ordem['valor_ordem'] > 0 ? ($ordem['lucro_bruto'] / $ordem['valor_ordem']) * 100 : 0;
                                                $margem_class = $margem_ordem >= 20 ? 'text-success' : ($margem_ordem >= 10 ? 'text-warning' : 'text-danger');
                                            ?>
                                            <tr>
                                                <td><?= $ordem['id_ordem'] ?></td>
                                                <td><?= htmlspecialchars($ordem['nome_cliente'] ?: $ordem['nome_client_ordem']) ?></td>
                                                <td><?= htmlspecialchars($ordem['nome_tecnico'] ?: 'Não atribuído') ?></td>
                                                <td><?= date('d/m/Y', strtotime($ordem['dt_recebimento'])) ?></td>
                                                <td>R$ <?= number_format($ordem['valor_ordem'], 2, ',', '.') ?></td>
                                                <td>R$ <?= number_format($ordem['custo_pecas'], 2, ',', '.') ?></td>
                                                <td class="positive-value">R$ <?= number_format($ordem['lucro_bruto'], 2, ',', '.') ?></td>
                                                <td class="<?= $margem_class ?>"><?= number_format($margem_ordem, 2, ',', '.') ?>%</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">Nenhuma ordem encontrada</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php if (!empty($ordens)): ?>
                                    <tfoot class="table-dark">
                                        <tr>
                                            <th colspan="4">TOTAL</th>
                                            <th>R$ <?= number_format($total_valor_ordens, 2, ',', '.') ?></th>
                                            <th>R$ <?= number_format($total_custo_pecas, 2, ',', '.') ?></th>
                                            <th>R$ <?= number_format($total_lucro_bruto, 2, ',', '.') ?></th>
                                            <th><?= number_format($margem_lucro, 2, ',', '.') ?>%</th>
                                        </tr>
                                    </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Mostrando <?= count($ordens) ?> ordens concluídas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dados para gráficos
        const meses = <?= json_encode(array_keys($dados_grafico_mensal)) ?>;
        const valoresMensais = <?= json_encode(array_column($dados_grafico_mensal, 'valor_ordens')) ?>;
        const custosMensais = <?= json_encode(array_column($dados_grafico_mensal, 'custo_pecas')) ?>;
        const lucrosMensais = <?= json_encode(array_column($dados_grafico_mensal, 'lucro')) ?>;
        
        const faixasLucro = <?= json_encode(array_keys($dados_grafico_categorias)) ?>;
        const quantidadesFaixa = <?= json_encode(array_values($dados_grafico_categorias)) ?>;
        
        // Gráfico de evolução mensal
        const ctxEvolucao = document.getElementById('graficoEvolucaoMensal').getContext('2d');
        new Chart(ctxEvolucao, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Faturamento',
                        data: valoresMensais,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Custo Peças',
                        data: custosMensais,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Lucro',
                        data: lucrosMensais,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R$ ' + context.raw.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Valor (R$)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
        
        // Gráfico de distribuição por faixa de lucro
        const ctxFaixa = document.getElementById('graficoFaixaLucro').getContext('2d');
        new Chart(ctxFaixa, {
            type: 'pie',
            data: {
                labels: faixasLucro,
                datasets: [{
                    data: quantidadesFaixa,
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} ordens (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Função para exportar PDF
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Título
            doc.setFontSize(18);
            doc.text('Relatório de Lucros - Lego Mania', 14, 15);
            doc.setFontSize(12);
            doc.text('Data: ' + new Date().toLocaleDateString('pt-BR'), 14, 22);
            
            // Dados resumidos
            doc.setFontSize(14);
            doc.text('Resumo Financeiro', 14, 35);
            doc.setFontSize(10);
            doc.text('Faturamento Total: R$ ' + <?= $total_valor_ordens ?>?.toFixed(2).replace('.', ','), 14, 42);
            doc.text('Custo Total com Peças: R$ ' + <?= $total_custo_pecas ?>?.toFixed(2).replace('.', ','), 14, 49);
            doc.text('Lucro Bruto Total: R$ ' + <?= $total_lucro_bruto ?>?.toFixed(2).replace('.', ','), 14, 56);
            doc.text('Margem de Lucro: ' + <?= $margem_lucro ?>?.toFixed(2).replace('.', ',') + '%', 14, 63);
            
            // Tabela
            doc.autoTable({
                startY: 70,
                head: [['ID', 'Cliente', 'Data', 'Valor (R$)', 'Custo (R$)', 'Lucro (R$)', 'Margem']],
                body: [
                    <?php 
                    foreach($ordens as $ordem) {
                        $margem_ordem = $ordem['valor_ordem'] > 0 ? ($ordem['lucro_bruto'] / $ordem['valor_ordem']) * 100 : 0;
                        echo "[";
                        echo "'" . $ordem['id_ordem'] . "',";
                        echo "'" . addslashes($ordem['nome_cliente'] ?: $ordem['nome_client_ordem']) . "',";
                        echo "'" . date('d/m/Y', strtotime($ordem['dt_recebimento'])) . "',";
                        echo "'R$ " . number_format($ordem['valor_ordem'], 2, ',', '.') . "',";
                        echo "'R$ " . number_format($ordem['custo_pecas'], 2, ',', '.') . "',";
                        echo "'R$ " . number_format($ordem['lucro_bruto'], 2, ',', '.') . "',";
                        echo "'" . number_format($margem_ordem, 2, ',', '.') . "%'";
                        echo "],";
                    }
                    ?>
                ],
                foot: [
                    ['TOTAL', '', '', 
                     'R$ <?= number_format($total_valor_ordens, 2, ',', '.') ?>', 
                     'R$ <?= number_format($total_custo_pecas, 2, ',', '.') ?>', 
                     'R$ <?= number_format($total_lucro_bruto, 2, ',', '.') ?>', 
                     '<?= number_format($margem_lucro, 2, ',', '.') ?>%']
                ],
                theme: 'grid',
                headStyles: {
                    fillColor: [33, 37, 41]
                }
            });
            
            doc.save('relatorio_lucros_' + new Date().toISOString().slice(0, 10) + '.pdf');
        }

         // Funções para manipulação de filtros
         function limparFiltros() {
            const url = new URL(window.location.href);
            url.search = '';
            window.location.href = url.toString();
        }
        
        function removerFiltro(filtro) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filtro);
            window.location.href = url.toString();
        }

        // Atualizar relógio
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('liveClock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Alternar exibição do menu
        document.getElementById("menu-toggle").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("d-none");
        });
    </script>
</body>
</html>