<?php
session_start();
require_once 'php/permissoes.php';
require_once 'conexao.php'; // Supondo que existe um arquivo de conexão

// Processar filtros
$filtro_data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$filtro_data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
$filtro_peca = isset($_GET['peca']) ? $_GET['peca'] : '';
$filtro_tecnico = isset($_GET['tecnico']) ? $_GET['tecnico'] : '';

// VERIFICA SE O USUARIO ESTÁ LOGADO
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso Negado! Faça login primeiro.'); window.location.href='index.php';</script>";
    exit();
}

// Construir consulta SQL
$where_conditions = [];
$params = [];

if (!empty($filtro_data_inicio)) {
    $where_conditions[] = "no.dt_recebimento >= ?";
    $params[] = $filtro_data_inicio;
}

if (!empty($filtro_data_fim)) {
    $where_conditions[] = "no.dt_recebimento <= ?";
    $params[] = $filtro_data_fim;
}

if (!empty($filtro_peca)) {
    $where_conditions[] = "pe.nome_peca LIKE ?";
    $params[] = "%$filtro_peca%";
}

if (!empty($filtro_tecnico)) {
    $where_conditions[] = "u.nome_usuario LIKE ?";
    $params[] = "%$filtro_tecnico%";
}

$where_sql = '';
if (!empty($where_conditions)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
}

// Consulta para obter as saídas de peças
$sql = "
    SELECT 
        pe.nome_peca,
        pe.descricao_peca,
        pe.preco as preco_unitario,
        op.quantidade,
        (pe.preco * op.quantidade) as valor_total,
        no.nome_client_ordem,
        u.nome_usuario as tecnico,
        no.dt_recebimento,
        no.id_ordem
    FROM ordem_servico_pecas op
    INNER JOIN peca_estoque pe ON op.id_peca_est = pe.id_peca_est
    INNER JOIN nova_ordem no ON op.id_ordem = no.id_ordem
    INNER JOIN usuario u ON no.tecnico = u.id_usuario
    $where_sql
    ORDER BY no.dt_recebimento DESC";

// Executar consulta (usando PDO ou mysqli)
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $saidas_pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Tratar erro
    $saidas_pecas = [];
    $erro = "Erro ao buscar dados: " . $e->getMessage();
}

// Calcular totais
$total_quantidade = 0;
$total_valor = 0;
foreach ($saidas_pecas as $saida) {
    $total_valor += $saida['valor_total'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Saída de Peças - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .print-only { display: none; }
        @media print {
            .no-print { display: none; }
            .print-only { display: block; }
            .card { border: none; box-shadow: none; }
            .table { font-size: 12px; }
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
            <nav class="navbar navbar-light bg-white shadow-sm no-print">
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
                    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                        <h5 class="mb-0"><i class="bi bi-box-arrow-up me-2"></i>Relatório de Saída de Peças</h5>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Imprimir
                            </button>
                            <button class="btn btn-success btn-sm" id="btnExportar">
                                <i class="bi bi-file-earmark-excel me-1"></i> Exportar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Cabeçalho para impressão -->
                    <div class="print-only text-center mb-3">
                        <h4>Lego Mania - Relatório de Saída de Peças</h4>
                        <p>Emitido em: <?php echo date('d/m/Y H:i:s'); ?></p>
                    </div>

                    <!-- Filtros -->
                    <div class="card shadow-sm mb-3 no-print">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtros</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label for="data_inicio" class="form-label">Data Início</label>
                                        <input type="date" class="form-control form-control-sm" id="data_inicio" name="data_inicio" value="<?php echo $filtro_data_inicio; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="data_fim" class="form-label">Data Fim</label>
                                        <input type="date" class="form-control form-control-sm" id="data_fim" name="data_fim" value="<?php echo $filtro_data_fim; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="peca" class="form-label">Peça</label>
                                        <input type="text" class="form-control form-control-sm" id="peca" name="peca" value="<?php echo $filtro_peca; ?>" placeholder="Nome da peça">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tecnico" class="form-label">Técnico</label>
                                        <input type="text" class="form-control form-control-sm" id="tecnico" name="tecnico" value="<?php echo $filtro_tecnico; ?>" placeholder="Nome do técnico">
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-filter me-1"></i> Aplicar Filtros
                                        </button>
                                        <a href="relatorio_saida.php" class="btn btn-outline-secondary btn-sm ms-2">
                                            <i class="bi bi-x-circle me-1"></i> Limpar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resumo -->
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-box-seam fs-1"></i>
                                    </div>
                                    <h4 class="card-title"><?php echo count($saidas_pecas); ?></h4>
                                    <p class="card-text text-muted">Registros Encontrados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="bi bi-currency-dollar fs-1"></i>
                                    </div>
                                    <h4 class="card-title">R$ <?php echo number_format($total_valor, 2, ',', '.'); ?></h4>
                                    <p>Valor Total</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de saídas -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="tabelaRelatorio">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Data</th>
                                            <th scope="col">Peça</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col" class="text-end">Preço Unit.</th>
                                            <th scope="col" class="text-end">Valor Total</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Técnico</th>
                                            <th scope="col" class="text-center no-print">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Conta as saídas de peças e percorre o array -->
                                        <?php if (count($saidas_pecas) > 0): ?>
                                            <?php foreach ($saidas_pecas as $saida): ?>
                                                <!-- tras os resultados de uma busca(nome da peça, descrição...) -->
                                                <tr>
                                                    <td><?php echo date('d/m/Y', strtotime($saida['dt_recebimento'])); ?></td>
                                                    <td><?php echo htmlspecialchars($saida['nome_peca']); ?></td>
                                                    <td><?php echo htmlspecialchars($saida['descricao_peca']); ?></td>
                                                    <td class="text-end">R$ <?php echo number_format($saida['preco_unitario'], 2, ',', '.'); ?></td>
                                                    <td class="text-end">R$ <?php echo number_format($saida['valor_total'], 2, ',', '.'); ?></td>
                                                    <td><?php echo htmlspecialchars($saida['nome_client_ordem']); ?></td>
                                                    <td><?php echo htmlspecialchars($saida['tecnico']); ?></td>
                                                    <td class="text-center no-print"> <?php echo htmlspecialchars($saida['quantidade']); ?>
                                                        <!-- <a href="consultar_ordem.php?id=<?php echo $saida['id_ordem']; ?>" class="btn btn-sm btn-outline-info" title="Ver OS"> 
                                                            <i class="bi bi-eye"></i>
                                                        </a> -->
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <?php if (isset($erro)): ?>
                                                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                                                    <?php else: ?>
                                                        <i class="bi bi-inbox display-4 d-block text-muted mb-2"></i>
                                                        <span class="text-muted">Nenhuma saída de peça encontrada com os filtros aplicados.</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="8" class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <strong class="me-3">TOTAL:</strong>
                                                    <strong><span>R$ <?php echo number_format($total_valor, 2, ',', '.'); ?></span></strong>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
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

        // Atualizar relógio
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('liveClock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Exportar para Excel
        document.getElementById('btnExportar').addEventListener('click', function() {
            // Criar uma tabela HTML temporária para exportação
            let table = document.getElementById('tabelaRelatorio').cloneNode(true);
            
            // Remover colunas de ações (última coluna)
            let rows = table.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    rows[i].deleteCell(cells.length - 1);
                }
            }
            
            // Converter tabela para CSV
            let csv = [];
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length; j++) {
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
                    row.push('"' + text + '"');
                }
                csv.push(row.join(";"));
            }
            let csvString = csv.join("\n");
            
            // Criar download
            let filename = "relatorio_saida_pecas_" + new Date().toISOString().slice(0, 10) + ".csv";
            let link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvString));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
</body>
</html>