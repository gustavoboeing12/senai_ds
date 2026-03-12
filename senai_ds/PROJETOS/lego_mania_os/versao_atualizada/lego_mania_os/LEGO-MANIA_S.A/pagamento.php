<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Inicializar variáveis
$ordens = [];
$ordens_abertas = [];
$mensagem_sucesso = '';
$mensagem_erro = '';

// Verificar se a conexão foi estabelecida
if (!isset($pdo) || $pdo === null) {
    die("Erro de conexão com o banco de dados. Verifique o arquivo conexao.php");
}

// Buscar ordens em aberto ao carregar a página
try {
    // Modificar a consulta SQL para usar nome_client_ordem quando id_cliente for NULL
    $sql_abertas = "SELECT no.*, 
        COALESCE(c.nome_cliente, no.nome_client_ordem) as nome_cliente,
        COALESCE(c.cpf_cnpj, 'Não informado') as cpf_cnpj,
        COALESCE(c.telefone, 'Não informado') as telefone,
        COALESCE(c.email, 'Não informado') as email,
        f.nome_funcionario as tecnico_nome
        FROM nova_ordem no 
        LEFT JOIN cliente c ON no.id_cliente = c.id_cliente 
        LEFT JOIN funcionario f ON no.tecnico = f.id_funcionario
        WHERE status_ordem LIKE 'Concluído'
        ORDER BY no.id_ordem DESC";
    $stmt_abertas = $pdo->query($sql_abertas);
    
    if ($stmt_abertas && $stmt_abertas->rowCount() > 0) {
        while ($row = $stmt_abertas->fetch(PDO::FETCH_ASSOC)) {
            $ordens_abertas[] = $row;
        }
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar ordens em aberto: " . $e->getMessage();
}

// Processar busca de ordens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $termo = trim($_POST['numero_ordem']);
    
    if (!empty($termo)) {
        // Verificar se é um ID numérico
        if (is_numeric($termo)) {
            $sql = "SELECT no.*, c.nome_cliente, c.cpf_cnpj, c.telefone, c.email,
                    f.nome_funcionario as tecnico_nome
                    FROM nova_ordem no 
                    LEFT JOIN cliente c ON no.id_cliente = c.id_cliente 
                    LEFT JOIN funcionario f ON no.tecnico = f.id_funcionario
                    WHERE no.id_ordem = :termo";
            $stmt = $pdo->prepare($sql);
            if ($stmt) {
                $stmt->bindParam(':termo', $termo, PDO::PARAM_INT);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $ordens[] = $row;
                    }
                }
            } else {
                $mensagem_erro = "Erro na preparação da consulta";
            }
        } else {
            // Buscar por nome ou CPF/CNPJ do cliente
            $sql = "SELECT no.*, c.nome_cliente, c.cpf_cnpj, c.telefone, c.email,
                    f.nome_funcionario as tecnico_nome
                    FROM nova_ordem no 
                    LEFT JOIN cliente c ON no.id_cliente = c.id_cliente 
                    LEFT JOIN funcionario f ON no.tecnico = f.id_funcionario
                    WHERE c.nome_cliente LIKE :termo OR c.cpf_cnpj LIKE :termo 
                    ORDER BY no.id_ordem DESC";
            $stmt = $pdo->prepare($sql);
            if ($stmt) {
                $termo_like = "%$termo%";
                $stmt->bindParam(':termo', $termo_like, PDO::PARAM_STR);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $ordens[] = $row;
                    }
                }
            } else {
                $mensagem_erro = "Erro na preparação da consulta";
            }
        }
    }
}

// Processar pagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagar'])) {
    $id_ordem = $_POST['id_ordem'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    
    // Atualizar o status da ordem para paga
    $sql = "UPDATE nova_ordem SET status_ordem = 'Concluído', metodo_pag = :metodo_pagamento WHERE id_ordem = :id_ordem";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':metodo_pagamento', $metodo_pagamento, PDO::PARAM_STR);
        $stmt->bindParam(':id_ordem', $id_ordem, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $mensagem_sucesso = "Pagamento registrado com sucesso para a ordem #$id_ordem!";
            
            // Atualizar a lista de ordens abertas
            $ordens_abertas = array_filter($ordens_abertas, function($ordem) use ($id_ordem) {
                return $ordem['id_ordem'] != $id_ordem;
            });
            
            // Registrar no log usando a função do conexao.php
            $acao = "Pagamento registrado para a ordem #$id_ordem (Método: $metodo_pagamento)";
            registrarLog($acao, 'nova_ordem', $id_ordem);
        } else {
            $mensagem_erro = "Erro ao registrar pagamento";
        }
    } else {
        $mensagem_erro = "Erro na preparação da consulta";
    }
    
    // Limpar a lista de ordens após o pagamento
    $ordens = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Pagamento - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .ordem-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .ordem-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }
        .badge-status {
            font-size: 0.8rem;
        }
        .scrollable-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .card-details {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 120px;
        }
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0c63e4;
        }
        .scrollable-list {
        max-height: 400px;
        overflow-y: auto;
        }

        #filtro-ordens {
            border-radius: 0.25rem;
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
        <!-- Botão voltar -->
        <button class="btn btn-outline-dark" style="position: absolute; margin-left: 60px;" onclick="history.back()">Voltar</button>
        <span class="navbar-brand mb-0 h1">
          <!-- Contéudo que identifica as horas -->
          <small class="text-muted">Horário atual:</small>
          <span id="liveClock" class="badge bg-secondary"></span>
        </span>
      </div>
    </nav>

            <!-- Conteúdo - Formulário -->
            <div class="flex-grow-1 p-3" style="overflow-y: auto;">
                <div class="container-fluid">
                    <!-- Mensagens de alerta -->
                    <?php if (!empty($mensagem_sucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $mensagem_sucesso; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($mensagem_erro)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $mensagem_erro; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Lista de Ordens Abertas -->
                        <div class="col-md-5 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Ordens em Aberto</h6>
                                    <span class="badge bg-light text-dark" id="contador-ordens"><?php echo count($ordens_abertas); ?></span>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Campo de pesquisa em tempo real -->
                                    <div class="p-2 border-bottom">
                                        <div class="input-group input-group-sm">
                                            <form action="pagamento.php" method="POST"></form>
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" id="filtro-ordens" placeholder="Filtrar por nome do cliente...">
                                            <button type="submit" name="procurar" class="btn btn-primary">
                                                    <i class="bi bi-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="scrollable-list">
                                        <?php if (!empty($ordens_abertas)): ?>
                                            <?php foreach ($ordens_abertas as $ordem): ?>
                                                <div class="ordem-item p-3 border-bottom" data-nome="<?php echo htmlspecialchars(strtolower($ordem['nome_cliente'])); ?>">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <strong>#<?php echo $ordem['id_ordem']; ?></strong>
                                                                <span class="badge badge-status 
                                                                    <?php 
                                                                    switch($ordem['status_ordem']) {
                                                                        case 'Aberta': echo 'bg-warning'; break;
                                                                        case 'Em Andamento': echo 'bg-info'; break;
                                                                        case 'Aguardando Peças': echo 'bg-secondary'; break;
                                                                        default: echo 'bg-secondary';
                                                                    }
                                                                    ?>">
                                                                    <?php echo $ordem['status_ordem']; ?>
                                                                </span>
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="fw-semibold"><?php echo htmlspecialchars($ordem['nome_cliente']); ?></span>
                                                            </div>
                                                            <div class="small text-muted">
                                                                CPF/CNPJ: <?php echo htmlspecialchars($ordem['cpf_cnpj']); ?>
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="fw-bold text-primary">R$ <?php echo number_format($ordem['valor_total'], 2, ',', '.'); ?></span>
                                                            </div>
                                                            <?php if (!empty($ordem['tecnico_nome'])): ?>
                                                                <div class="small">
                                                                    <i class="bi bi-person-gear"></i> Técnico: <?php echo htmlspecialchars($ordem['tecnico_nome']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="p-3 text-center text-muted">
                                                <i class="bi bi-inbox display-4"></i>
                                                <p class="mt-2">Nenhuma ordem concluída</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Formulário de Pagamento -->
                        <div class="col-md-7">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Registrar Pagamento</h5>
                                </div>
                                <div class="card-body p-3">
                                    <form method="POST" action="">
                                        <!-- Campo de busca -->
                                        <div class="mb-3">
                                            <label for="numero_ordem" class="form-label">Buscar Ordem de Serviço</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control" id="numero_ordem" name="numero_ordem" 
                                                       placeholder="Buscar por ID ou NOME" required 
                                                       value="<?php echo isset($_POST['numero_ordem']) ? htmlspecialchars($_POST['numero_ordem']) : ''; ?>">
                                                <button type="submit" name="buscar" class="btn btn-primary">
                                                    <i class="bi bi-search"></i> Buscar
                                                </button>
                                            </div>
                                            <small class="text-muted">Clique em uma ordem na lista ao lado para preencher automaticamente</small>
                                        </div>
                                    </form>
                                    
                                    <!-- Lista de ordens encontradas -->
                                    <?php if (!empty($ordens)): ?>
                                        <div class="mt-4">
                                            <h6 class="mb-3">Ordens encontradas:</h6>
                                            
                                            <?php foreach ($ordens as $ordem): ?>
                                                <div class="card mb-4 border-primary">
                                                    <div class="card-header bg-light">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Ordem #<?php echo $ordem['id_ordem']; ?></h5>
                                                            <span class="badge 
                                                                <?php 
                                                                switch($ordem['status_ordem']) {
                                                                    case 'Aberta': echo 'bg-warning'; break;
                                                                    case 'Em Andamento': echo 'bg-info'; break;
                                                                    case 'Aguardando Peças': echo 'bg-secondary'; break;
                                                                    case 'Concluído': echo 'bg-success'; break;
                                                                    case 'Cancelada': echo 'bg-danger'; break;
                                                                    default: echo 'bg-secondary';
                                                                }
                                                                ?> fs-6">
                                                                <?php echo $ordem['status_ordem']; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="card-details p-3 rounded">
                                                                    <h6 class="border-bottom pb-2">Informações do Cliente</h6>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Nome:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['nome_cliente']); ?></span>
                                                                    </div>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">CPF/CNPJ:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['cpf_cnpj']); ?></span>
                                                                    </div>
                                                                    <?php if (!empty($ordem['telefone'])): ?>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Telefone:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['telefone']); ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($ordem['email'])): ?>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Email:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['email']); ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-details p-3 rounded">
                                                                    <h6 class="border-bottom pb-2">Detalhes do Serviço</h6>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Data Receb.:</span>
                                                                        <span><?php echo date('d/m/Y', strtotime($ordem['dt_recebimento'])); ?></span>
                                                                    </div>
                                                                    <?php if (!empty($ordem['tecnico_nome'])): ?>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Técnico:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['tecnico_nome']); ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($ordem['marca_aparelho'])): ?>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Marca:</span>
                                                                        <span><?php echo htmlspecialchars($ordem['marca_aparelho']); ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($ordem['prioridade'])): ?>
                                                                    <div class="d-flex mb-2">
                                                                        <span class="info-label">Prioridade:</span>
                                                                        <span class="badge bg-<?php echo $ordem['prioridade'] == 'alta' ? 'danger' : ($ordem['prioridade'] == 'media' ? 'warning' : 'info'); ?>">
                                                                            <?php echo ucfirst($ordem['prioridade']); ?>
                                                                        </span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Accordion para mais detalhes -->
                                                        <div class="accordion mb-3" id="accordionDetails<?php echo $ordem['id_ordem']; ?>">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $ordem['id_ordem']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $ordem['id_ordem']; ?>">
                                                                        <i class="bi bi-info-circle me-2"></i>Mais Detalhes
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse<?php echo $ordem['id_ordem']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionDetails<?php echo $ordem['id_ordem']; ?>">
                                                                    <div class="accordion-body">
                                                                        <?php if (!empty($ordem['problema'])): ?>
                                                                        <div class="mb-2">
                                                                            <strong>Problema relatado:</strong>
                                                                            <p class="mb-1"><?php echo nl2br(htmlspecialchars($ordem['problema'])); ?></p>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($ordem['observacao'])): ?>
                                                                        <div class="mb-2">
                                                                            <strong>Observações:</strong>
                                                                            <p class="mb-1"><?php echo nl2br(htmlspecialchars($ordem['observacao'])); ?></p>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($ordem['tempo_uso'])): ?>
                                                                        <div class="mb-2">
                                                                            <strong>Tempo de uso:</strong>
                                                                            <span><?php echo htmlspecialchars($ordem['tempo_uso']); ?></span>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                                            <div>
                                                                <h4 class="text-primary mb-0">Total: R$ <?php echo number_format($ordem['valor_total'], 2, ',', '.'); ?></h4>
                                                            </div>
                                                            
                                                            <?php if ($ordem['status_ordem'] !== 'Concluído' && $ordem['status_ordem'] !== 'Cancelada'): ?>
                                                                <form method="POST" action="" class="ms-3">
                                                                    <input type="hidden" name="id_ordem" value="<?php echo $ordem['id_ordem']; ?>">
                                                                    
                                                                    <div class="input-group mb-2">
                                                                        <label class="input-group-text" for="metodoSelect<?php echo $ordem['id_ordem']; ?>">
                                                                            <i class="bi bi-credit-card"></i>
                                                                        </label>
                                                                        <select class="form-select" id="metodoSelect<?php echo $ordem['id_ordem']; ?>" name="metodo_pagamento" required>
                                                                            <option value="">Método de pagamento</option>
                                                                            <option value="Dinheiro">Dinheiro</option>
                                                                            <option value="Cartão Débito">Cartão Débito</option>
                                                                            <option value="Cartão Crédito">Cartão Crédito</option>
                                                                            <option value="PIX">PIX</option>
                                                                            <option value="Transferência">Transferência</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <button type="submit" name="pagar" class="btn btn-success w-100">
                                                                        <i class="bi bi-credit-card me-1"></i> Registrar Pagamento
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-<?php echo $ordem['status_ordem'] == 'Concluído' ? 'success' : 'danger'; ?> me-2 fs-6">
                                                                        <?php echo $ordem['status_ordem']; ?>
                                                                    </span>
                                                                    <?php if (!empty($ordem['metodo_pag'])): ?>
                                                                        <span class="text-muted">(<?php echo $ordem['metodo_pag']; ?>)</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])): ?>
                                        <div class="alert alert-warning mt-3">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Nenhuma ordem encontrada com o termo "<?php echo htmlspecialchars($_POST['numero_ordem']); ?>".
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted mt-5 py-4">
                                            <i class="bi bi-search display-4"></i>
                                            <p class="mt-2">Use o campo acima para buscar uma ordem de serviço</p>
                                            <small>Ou clique em uma ordem na lista ao lado</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botão de impressão (só aparece quando há ordens) -->
            <?php if (!empty($ordens)): ?>
            <button class="btn btn-primary print-btn rounded-pill shadow" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
            <?php endif; ?>
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
        
        // Auto-submit ao clicar em uma ordem da lista
        document.querySelectorAll('.ordem-item').forEach(item => {
            item.addEventListener('click', function() {
                const idOrdem = this.querySelector('strong').textContent.replace('#', '');
                document.getElementById('numero_ordem').value = idOrdem;
                document.querySelector('form').submit();
            });
        });
        
        // Focar no campo de busca ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('numero_ordem').focus();
        });

        // Filtro em tempo real para ordens em aberto
        document.getElementById('filtro-ordens').addEventListener('input', function() {
            const filtro = this.value.toLowerCase();
            const ordens = document.querySelectorAll('.ordem-item');
            let contador = 0;
            
            ordens.forEach(ordem => {
                const nomeCliente = ordem.getAttribute('data-nome');
                if (nomeCliente.includes(filtro)) {
                    ordem.style.display = 'block';
                    contador++;
                } else {
                    ordem.style.display = 'none';
                }
            });
            
            // Atualizar contador
            document.getElementById('contador-ordens').textContent = contador;
        });
    </script>
</body>
</html>