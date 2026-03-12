<?php
session_start();
require_once 'php/permissoes.php';
require_once 'conexao.php';

// Verifica se o usuario tem permissão de ADM, Funcionario ou Técnico
if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2 && $_SESSION['perfil']!=4){
    echo "<script> alert ('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Buscar todas as peças do estoque com cálculo de valor total
$sql = "SELECT 
            id_peca_est, 
            nome_peca, 
            descricao_peca, 
            qtde, 
            preco, 
            (qtde * preco) as valor_total,
            qtde_minima, 
            tipo, 
            dt_cadastro, 
            nome_funcionario, 
            nome_fornecedor
        FROM peca_estoque
        JOIN funcionario ON peca_estoque.id_funcionario = funcionario.id_funcionario
        JOIN fornecedor ON peca_estoque.id_fornecedor = fornecedor.id_fornecedor";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pecas_estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular totais
$valor_total_estoque = 0;
$quantidade_total_pecas = 0;
$categorias_valor = ['hardware' => 0, 'perifericos' => 0, 'cabos' => 0, 'outros' => 0];
$categorias_quantidade = ['hardware' => 0, 'perifericos' => 0, 'cabos' => 0, 'outros' => 0];

// Percorre cada elemento de peça no estoque
foreach ($pecas_estoque as $peca) {
    $valor_total_estoque += $peca['valor_total'];
    $quantidade_total_pecas += $peca['qtde'];   
    // tipo da peça
    $tipo = strtolower($peca['tipo']);
    if (isset($categorias_valor[$tipo])) {
        $categorias_valor[$tipo] += $peca['valor_total'];
        $categorias_quantidade[$tipo] += $peca['qtde'];
    }
}

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

// Ordenar peças por valor total (decrescente) para o gráfico
usort($pecas_estoque, function($a, $b) {
    return $b['valor_total'] - $a['valor_total'];
});
$top_pecas = array_slice($pecas_estoque, 0, 5);
$todas_pecas = $pecas_estoque; // Todas as peças ordenadas
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Despesas com Peças - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        .see-more-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .chart-tooltip {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 3px;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
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
                        <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Relatório de Despesas com Peças em Estoque</h5>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="generatePDF()">
                                <i class="bi bi-download me-1"></i> Exportar PDF
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="window.location.href='relatorio_pecas_estoque.php'">
                                <i class="bi bi-box me-1"></i> Ver Estoque
                            </button>
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
                                    <h3>R$ <?= number_format($valor_total_estoque, 2, ',', '.') ?></h3>
                                    <p class="card-text text-muted">Valor Total em Estoque</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="bi bi-box-seam fs-1"></i>
                                    </div>
                                    <h3><?= $quantidade_total_pecas ?></h3>
                                    <p class="card-text text-muted">Total de Peças</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2">
                                        <i class="bi bi-calculator fs-1"></i>
                                    </div>
                                    <h3>R$ <?= number_format($valor_total_estoque / ($quantidade_total_pecas > 0 ? $quantidade_total_pecas : 1), 2, ',', '.') ?></h3>
                                    <p class="card-text text-muted">Valor Médio por Peça</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow-sm border-0 card-dashboard">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2">
                                        <i class="bi bi-grid-3x3 fs-1"></i>
                                    </div>
                                    <h3><?= count($pecas_estoque) ?></h3>
                                    <p class="card-text text-muted">Tipos de Peças Diferentes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribuição de Valor por Categoria</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoValorCategorias" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Top 5 Peças com Maior Valor em Estoque</h6>
                                    <button class="btn btn-sm btn-outline-primary see-more-btn" id="btnVerMaisPecas">
                                        <i class="bi bi-list-ul me-1"></i> Ver todas
                                    </button>
                                </div>
                                <div class="card-body position-relative">
                                    <div class="chart-container">
                                        <canvas id="graficoTopPecas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de peças -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0"><i class="bi bi-table me-2"></i>Detalhamento das Peças em Estoque</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="tabelaDespesasPecas">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Categoria</th>
                                            <th scope="col">Fornecedor</th>
                                            <th scope="col">Quantidade</th>
                                            <th scope="col">Preço Unit.</th>
                                            <th scope="col">Valor Total</th>
                                            <th scope="col">Status Estoque</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Percorre a variavel peça no estoque e tras a busca por meio da QUERY -->
                                        <?php foreach($pecas_estoque as $peca): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($peca['id_peca_est']) ?></td>
                                            <td><?= htmlspecialchars($peca['nome_peca']) ?></td>
                                            <td><?= htmlspecialchars($peca['tipo']) ?></td>
                                            <td><?= htmlspecialchars($peca['nome_fornecedor']) ?></td>
                                            <td><?= htmlspecialchars($peca['qtde']) ?></td>
                                            <td>R$ <?= number_format($peca['preco'], 2, ',', '.') ?></td>
                                            <td>R$ <?= number_format($peca['valor_total'], 2, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                    [$txt, $cls] = estoqueStatus($peca['qtde'] ?? 0);
                                                    echo "<span class='badge $cls'>$txt</span>";
                                                ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-dark">
                                        <tr>
                                            <th colspan="4">TOTAL GERAL</th>
                                            <th><?= $quantidade_total_pecas ?></th>
                                            <th>-</th>
                                            <th>R$ <?= number_format($valor_total_estoque, 2, ',', '.') ?></th>
                                            <th>-</th>
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

    <!-- Modal para visualizar todas as peças -->
    <div class="modal fade" id="modalTodasPecas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Todas as Peças por Valor em Estoque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabelaTodasPecas">
                            <thead>
                                <tr>
                                    <th>Posição</th>
                                    <th>Nome da Peça</th>
                                    <th>Valor em Estoque</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Categoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Conteúdo preenchido via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dados das peças
        const todasPecas = <?= json_encode($todas_pecas) ?>;
        const topPecas = <?= json_encode($top_pecas) ?>;
        
        // Gráfico de distribuição de valor por categoria
        const ctxValorCategorias = document.getElementById('graficoValorCategorias').getContext('2d');
        new Chart(ctxValorCategorias, {
            type: 'doughnut',
            data: {
                labels: ['Hardware', 'Periféricos', 'Cabos', 'Outros'],
                datasets: [{
                    data: [
                        <?= $categorias_valor['hardware'] ?>,
                        <?= $categorias_valor['perifericos'] ?>,
                        <?= $categorias_valor['cabos'] ?>,
                        <?= $categorias_valor['outros'] ?>
                    ],
                    backgroundColor: ['#0d6efd', '#6f42c1', '#20c997', '#fd7e14']
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
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: R$ ${value.toFixed(2).replace('.', ',')} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Configuração do gráfico Top 5 Peças
        const ctxTopPecas = document.getElementById('graficoTopPecas').getContext('2d');
        const graficoTopPecas = new Chart(ctxTopPecas, {
            type: 'bar',
            data: {
                labels: topPecas.map(peca => peca.nome_peca.length > 20 ? peca.nome_peca.substring(0, 17) + '...' : peca.nome_peca),
                datasets: [{
                    label: 'Valor em Estoque (R$)',
                    data: topPecas.map(peca => peca.valor_total),
                    backgroundColor: topPecas.map((peca, index) => {
                        // Gradiente de cores para as barras
                        const hues = ['#0d6efd', '#0dcaf0', '#198754', '#ffc107', '#fd7e14'];
                        return hues[index % hues.length];
                    }),
                    borderColor: topPecas.map((peca, index) => {
                        const hues = ['#0a58ca', '#0aa2c0', '#146c43', '#ddaa01', '#dc6502'];
                        return hues[index % hues.length];
                    }),
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Gráfico de barras horizontais
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const peca = topPecas[context.dataIndex];
                                return [
                                    `Valor total: R$ ${peca.valor_total.toFixed(2).replace('.', ',')}`,
                                    `Quantidade: ${peca.qtde} unidades`,
                                    `Preço unitário: R$ ${peca.preco.toFixed(2).replace('.', ',')}`
                                ];
                            },
                            afterLabel: function(context) {
                                const peca = topPecas[context.dataIndex];
                                return `Fornecedor: ${peca.nome_fornecedor}`;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 4
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Valor em Estoque (R$)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Peças',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Configurar o botão "Ver todas"
        document.getElementById('btnVerMaisPecas').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalTodasPecas'));
            const tabelaBody = document.querySelector('#tabelaTodasPecas tbody');
            
            // Limpar tabela
            tabelaBody.innerHTML = '';
            
            // Preencher tabela com todas as peças
            todasPecas.forEach((peca, index) => {
                const row = document.createElement('tr');
                
                // Destacar as 5 primeiras
                if (index < 5) {
                    row.classList.add('table-primary');
                }
                
                row.innerHTML = `
                    <td>${index + 1}º</td>
                    <td>${peca.nome_peca}</td>
                    <td>R$ ${peca.valor_total.toFixed(2).replace('.', ',')}</td>
                    <td>${peca.qtde} unidades</td>
                    <td>R$ ${peca.preco.toFixed(2).replace('.', ',')}</td>
                    <td>${peca.tipo}</td>
                `;
                
                tabelaBody.appendChild(row);
            });
            
            modal.show();
        });

        // Função para exportar PDF
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Título
            doc.setFontSize(18);
            doc.text('Relatório de Despesas com Peças em Estoque', 14, 15);
            doc.setFontSize(12);
            doc.text('Data: ' + new Date().toLocaleDateString('pt-BR'), 14, 22);
            
            // Dados resumidos
            doc.setFontSize(14);
            doc.text('Resumo Financeiro', 14, 35);
            doc.setFontSize(10);
            doc.text('Valor Total em Estoque: R$ ' + <?= $valor_total_estoque ?>?.toFixed(2).replace('.', ','), 14, 42);
            doc.text('Quantidade Total de Peças: ' + <?= $quantidade_total_pecas ?>, 14, 49);
            doc.text('Valor Médio por Peça: R$ ' + (<?= $valor_total_estoque ?> / <?= $quantidade_total_pecas ?>).toFixed(2).replace('.', ','), 14, 56);
            
            // Tabela
            doc.autoTable({
                startY: 65,
                head: [['ID', 'Nome', 'Categoria', 'Qtd', 'Preço Unit.', 'Valor Total']],
                body: [
                    <?php 
                    // Imprime as informações
                    foreach($pecas_estoque as $peca) {
                        echo "[";
                        echo "'" . $peca['id_peca_est'] . "',";
                        echo "'" . addslashes($peca['nome_peca']) . "',";
                        echo "'" . $peca['tipo'] . "',";
                        echo "'" . $peca['qtde'] . "',";
                        echo "'R$ " . number_format($peca['preco'], 2, ',', '.') . "',";
                        echo "'R$ " . number_format($peca['valor_total'], 2, ',', '.') . "'";
                        echo "],";
                    }
                    ?>
                ],
                foot: [['TOTAL', '', '', '<?= $quantidade_total_pecas ?>', '', 'R$ <?= number_format($valor_total_estoque, 2, ',', '.') ?>']],
                theme: 'grid',
                headStyles: {
                    fillColor: [33, 37, 41]
                }
            });
            
            doc.save('relatorio_despesas_pecas_' + new Date().toISOString().slice(0, 10) + '.pdf');
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