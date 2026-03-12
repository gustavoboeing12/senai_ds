<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica o id do usuario logado
if(!isset($_SESSION['id_usuario'])){
  header("Location: index.php");
  exit();
}

// Obtendo o nome do perfil do usuário logado
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";

// Prepara para encapsular
$stmtPerfil = $pdo -> prepare($sqlPerfil);
$stmtPerfil -> bindParam(":id_perfil",$id_perfil);
$stmtPerfil -> execute();
$perfil = $stmtPerfil -> fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// Buscar estatísticas para o dashboard
$stats = [];

// Total de ordens de serviço
$sqlOrdens = "SELECT COUNT(*) as total FROM nova_ordem";
$stmtOrdens = $pdo->query($sqlOrdens);
$stats['total_ordens'] = $stmtOrdens->fetch(PDO::FETCH_ASSOC)['total'];

// Ordens em aberto (status diferente de Concluído e Cancelada)
$sqlOrdensAbertas = "SELECT COUNT(*) as abertas FROM nova_ordem 
                    WHERE status_ordem != 'Concluído' AND status_ordem != 'Cancelada'";
$stmtOrdensAbertas = $pdo->query($sqlOrdensAbertas);
$stats['ordens_abertas'] = $stmtOrdensAbertas->fetch(PDO::FETCH_ASSOC)['abertas'];

// Ordens concluídas (últimos 7 dias)
$sqlOrdensConcluidas = "SELECT COUNT(*) as concluidas FROM nova_ordem 
                       WHERE status_ordem = 'Concluído' 
                       AND dt_recebimento >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$stmtOrdensConcluidas = $pdo->query($sqlOrdensConcluidas);
$stats['ordens_concluidas_7dias'] = $stmtOrdensConcluidas->fetch(PDO::FETCH_ASSOC)['concluidas'];

// Total de clientes ativos
$sqlClientes = "SELECT COUNT(*) as total FROM cliente WHERE status = 'Ativo'";
$stmtClientes = $pdo->query($sqlClientes);
$stats['total_clientes'] = $stmtClientes->fetch(PDO::FETCH_ASSOC)['total'];

// Receita total (últimos 30 dias) - apenas ordens concluídas
// Esta função é utilizada quando um conjunto de campos for passado e deve ser retornado o primeiro não nulo
$sqlReceita = "SELECT COALESCE(SUM(valor_total), 0) as receita FROM nova_ordem 
              WHERE status_ordem = 'Concluído' 
              AND dt_recebimento >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$stmtReceita = $pdo->query($sqlReceita);
$stats['receita_30dias'] = $stmtReceita->fetch(PDO::FETCH_ASSOC)['receita'];

// Buscar últimas ordens de serviço 
$sqlUltimasOrdens = "SELECT no.id_ordem, no.nome_client_ordem, no.marca_aparelho, 
                    no.problema, no.prioridade, no.dt_recebimento, 
                 -- f.nome_funcionario,
                    u.nome_usuario as tecnico, no.status_ordem as status
                    FROM nova_ordem no
                 -- LEFT JOIN funcionario f ON no.tecnico = f.id_funcionario
                    LEFT JOIN usuario u ON no.tecnico = u.id_usuario
                    ORDER BY no.dt_recebimento DESC LIMIT 5";
$stmtUltimasOrdens = $pdo->query($sqlUltimasOrdens);
$ultimas_ordens = $stmtUltimasOrdens->fetchAll(PDO::FETCH_ASSOC);

// Buscar ordens por status (para gráfico)
/*
  Verifica se o status da ordem é "Aberta".
  Se for, retorna 1.
  Se não for, retorna 0.
  Para mostrar no dashboard de estatisticas.
*/
$sqlOrdensStatus = "SELECT 
                    SUM(CASE WHEN status_ordem = 'Aberta' THEN 1 ELSE 0 END) as aberta,
                    SUM(CASE WHEN status_ordem = 'Em Andamento' THEN 1 ELSE 0 END) as andamento,
                    SUM(CASE WHEN status_ordem = 'Aguardando Peças' THEN 1 ELSE 0 END) as aguardando_pecas,
                    SUM(CASE WHEN status_ordem = 'Concluído' THEN 1 ELSE 0 END) as concluido,
                    SUM(CASE WHEN status_ordem = 'Cancelada' THEN 1 ELSE 0 END) as cancelada
                    FROM nova_ordem";
$stmtOrdensStatus = $pdo->query($sqlOrdensStatus);
$ordens_status = $stmtOrdensStatus->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- script para funcionar o menu dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Dashboard - Lego mania</title>
    <style>
        .card-stats {
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .priority-high {
            border-left: 4px solid #dc3545;
        }
        .priority-medium {
            border-left: 4px solid #ffc107;
        }
        .priority-low {
            border-left: 4px solid #28a745;
        }
        .status-badge {
            font-size: 0.75rem;
        }
        .status-aberta { background-color: #176efd; }
        .status-andamento { background-color: #ffc107; }
        .status-aguardando { background-color: #0dcaf0; }
        .status-concluido { background-color: #28a745; }
        .status-cancelada { background-color: #dc3545; }
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
        <span class="navbar-brand mb-0 h1">
          <!-- Contéudo que identifica as horas -->
          <small class="text-muted">Horário atual:</small>
          <span id="liveClock" class="badge bg-secondary"></span>
        </span>
      </div>
    </nav>

    <!-- Conteúdo -->
    <div class="p-4 flex-grow-1" style="overflow-y: auto;">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Bem-vindo, <?php echo $_SESSION["usuario"];?>!</h3>
        <span class="badge bg-primary">Perfil: <?php echo $nome_perfil;?></span>
      </div>

      <!-- Cards de Estatísticas -->
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card card-stats h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-muted mb-0">Ordens em Aberto</h6>
                  <h3 class="font-weight-bold mb-0"><?php echo $stats['ordens_abertas']; ?></h3>
                </div>
                <div class="icon-shape bg-primary text-white rounded-circle p-3">
                  <i class="bi bi-clipboard-check"></i>
                </div>
              </div>
              <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-nowrap">Total de ordens: <?php echo $stats['total_ordens']; ?></span>
              </p>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card card-stats h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-muted mb-0">Ordens Concluídas</h6>
                  <h3 class="font-weight-bold mb-0"><?php echo $stats['ordens_concluidas_7dias']; ?></h3>
                </div>
                <div class="icon-shape bg-success text-white rounded-circle p-3">
                  <i class="bi bi-check-circle"></i>
                </div>
              </div>
              <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-nowrap">Últimos 7 dias</span>
              </p>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card card-stats h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-muted mb-0">Clientes Ativos</h6>
                  <h3 class="font-weight-bold mb-0"><?php echo $stats['total_clientes']; ?></h3>
                </div>
                <div class="icon-shape bg-info text-white rounded-circle p-3">
                  <i class="bi bi-people"></i>
                </div>
              </div>
              <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-nowrap">Clientes cadastrados</span>
              </p>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card card-stats h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-muted mb-0">Receita (30 dias)</h6>
                  <h3 class="font-weight-bold mb-0">R$ <?php echo number_format($stats['receita_30dias'], 2, ',', '.'); ?></h3>
                </div>
                <div class="icon-shape bg-warning text-white rounded-circle p-3">
                  <i class="bi bi-currency-dollar"></i>
                </div>
              </div>
              <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-nowrap">Faturamento recente</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráfico e Últimas Ordens -->
      <div class="row">
        <!-- Gráfico de Status das Ordens -->
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-header bg-white">
              <h5 class="card-title mb-0">Status das Ordens de Serviço</h5>
            </div>
            <div class="card-body">
              <canvas id="statusChart" height="250"></canvas>
            </div>
          </div>
        </div>

        <!-- Últimas Ordens de Serviço -->
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Últimas Ordens de Serviço</h5>
              <a href="nova_ordem.php" class="btn btn-sm btn-primary">Nova OS</a>
            </div>
            <div class="card-body p-0">
              <?php if(!empty($ultimas_ordens)): ?>
                <div class="list-group list-group-flush">
                  <?php foreach($ultimas_ordens as $ordem):
                    $priority_class = '';
                    if($ordem['prioridade'] == 'Alta') $priority_class = 'priority-high';
                    elseif($ordem['prioridade'] == 'Média') $priority_class = 'priority-medium';
                    else $priority_class = 'priority-low';
                    
                    switch ($ordem['status']) {
                      case 'Aberta':
                          $status_class = 'status-aberta';
                          break;
                      case 'Em Andamento':
                          $status_class = 'status-andamento';
                          break;
                      case 'Aguardando Peças':
                          $status_class = 'status-aguardando';
                          break;
                      case 'Concluído':
                          $status_class = 'status-concluido';
                          break;
                      case 'Cancelada':
                          $status_class = 'status-cancelada';
                          break;
                      default:
                          $status_class = 'bg-secondary'; // fallback
                  }
                  ?>
                    <div class="list-group-item <?php echo $priority_class; ?>">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">#<?php echo $ordem['id_ordem']; ?> - <?php echo htmlspecialchars($ordem['nome_client_ordem']); ?></h6>
                        <small><?php echo date('d/m/Y', strtotime($ordem['dt_recebimento'])); ?></small>
                      </div>
                      <p class="mb-1"><?php echo htmlspecialchars($ordem['marca_aparelho']); ?> - <?php echo substr(htmlspecialchars($ordem['problema']), 0, 50); ?>...</p>
                      <div class="d-flex justify-content-between align-items-center">
                        <small>
                          <!-- Verifica tem algum tecnico atribuido a O.S -->
                          <?php if(!empty($ordem['tecnico'])): ?>
                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($ordem['tecnico']); ?>
                          <?php else: ?>
                            <span class="text-muted">Sem técnico atribuído</span>
                          <?php endif; ?>
                        </small>
                          <!-- Verifica tem algum status atribuido a O.S -->
                        <?php if(!empty($ordem['status'])): ?>
                          <span class="badge <?php echo $status_class; ?> status-badge"><?php echo $ordem['status']; ?></span>
                        <?php else: ?>
                          <span class="badge bg-warning status-badge">Sem status</span>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="text-center p-4">
                  <i class="bi bi-inbox display-4 text-muted"></i>
                  <p class="text-muted mt-3">Nenhuma ordem de serviço encontrada</p>
                  <a href="nova_ordem.php" class="btn btn-primary mt-2">Criar primeira ordem</a>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-footer bg-white">
              <a href="consultar_ordem.php" class="btn btn-sm btn-outline-primary">Ver todas as ordens</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Ações Rápidas -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-white">
              <h5 class="card-title mb-0">Ações Rápidas</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3 col-6 mb-3">
                  <a href="nova_ordem.php" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-plus-circle display-6 mb-2"></i>
                    <span>Nova OS</span>
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <a href="gestao_cliente.php" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-people display-6 mb-2"></i>
                    <span>Clientes</span>
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <a href="consultar_ordem.php" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-clipboard-data display-6 mb-2"></i>
                    <span>Ordens</span>
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <a href="logs.php" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-clock-history display-6 mb-2"></i>
                    <span>Logs</span>
                  </a>
                </div>
              </div>
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

  function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString('pt-BR');
      document.getElementById('liveClock').textContent = timeString;
  }
  setInterval(updateClock, 1000);
  updateClock(); // Inicializa imediatamente

  // Gráfico de status das ordens
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Aberta', 'Em Andamento', 'Aguardando Peças', 'Concluído', 'Cancelada'],
        datasets: [{
          data: [
            <?php echo $ordens_status['aberta']; ?>,
            <?php echo $ordens_status['andamento']; ?>,
            <?php echo $ordens_status['aguardando_pecas']; ?>,
            <?php echo $ordens_status['concluido']; ?>,
            <?php echo $ordens_status['cancelada']; ?>
          ],
          backgroundColor: [
            '#176efd', // Aberta
            '#ffc107', // Em Andamento
            '#0dcaf0', // Aguardando Peças
            '#28a745', // Concluído
            '#dc3545'  // Cancelada
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  });
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>