<?php
    session_start();
    require_once 'conexao.php';
    require_once 'php/permissoes.php';

    // Verifica se o usuário está logado
    if (!isset($_SESSION['id_usuario'])) {
        echo "<script>alert('Acesso Negado! Faça login primeiro.'); window.location.href='index.php';</script>";
        exit();
    }

    // Inicializa o array ordens_ocultas na sessão se ele não existir, prevenindo erros de variável indefinida.
    if (!isset($_SESSION['ordens_ocultas'])) {
        $_SESSION['ordens_ocultas'] = [];
    }

    // Inicializa variáveis
    $busca = '';
    $ordens = [];

    // Verifica se há busca por texto (GET)
    if (isset($_GET['busca']) && !empty($_GET['busca'])) {
        $busca = trim($_GET['busca']);
    }

     // Construir a query de seleção
    $sql = "SELECT no.id_ordem, 
    no.nome_client_ordem,
    u.nome_usuario AS nome_tecnico,  
    u.id_usuario AS id_tecnico,
    no.problema, 
    no.dt_recebimento, 
    no.valor_total,
    no.marca_aparelho,
    no.prioridade,
    no.observacao,
    no.status_ordem AS statuss,
    c.nome_cliente,
    no.id_cliente,
    f.nome_funcionario
    FROM nova_ordem no
    LEFT JOIN cliente c ON no.id_cliente = c.id_cliente
    LEFT JOIN funcionario f ON no.id_funcionario = f.id_funcionario
    LEFT JOIN usuario u ON no.tecnico = u.id_usuario";

    // Inicializa variáveis
    $where_conditions = [];
    $params = [];

    // Adiciona filtro por busca se existir
    if (!empty($busca)) {
        // Se a busca for numérica
        if (is_numeric($busca)) {
            $where_conditions[] = "no.id_ordem = :busca";
            $params[':busca'] = $busca;
        } else {
            $where_conditions[] = "(c.nome_cliente LIKE :busca_nome OR no.nome_client_ordem LIKE :busca_nome)";
            $params[':busca_nome'] = "$busca%";
        }
    }

    // ADICIONAR FILTRO POR STATUS - NOVO CÓDIGO
    if (isset($_GET['statuss']) && !empty($_GET['statuss'])) {
        $where_conditions[] = "no.status_ordem = :status";
        $params[':status'] = $_GET['statuss'];
    }

    // Combinar conditions se houver
    if (!empty($where_conditions)) {
        // Adiciona ao comando sql clausula WHERE e junta ao array
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    // Adiciona ao comando sql
    $sql .= " ORDER BY no.id_ordem DESC";

    // Prepara a query
    $stmt = $pdo->prepare($sql);

    // Percorre array $params
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    // Executa a query
    $stmt->execute();
    $ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filtro oculto com valor, senão sem valor
    $filtro_oculto = isset($_GET['filtro_oculto']) ? $_GET['filtro_oculto'] : '0';

    // Se o valor for 0,
    if ($filtro_oculto == '0') {
        // Filtra os dados do array
        $ordens = array_filter($ordens, function($ordem) {
            // Retorna as ordens não ocultas
            return !in_array($ordem['id_ordem'], $_SESSION['ordens_ocultas']);
        });
    } else {
        // Filtra os dados do array
        $ordens = array_filter($ordens, function($ordem) {
            // Retorna as ordens ocultas
            return in_array($ordem['id_ordem'], $_SESSION['ordens_ocultas']);
        });
    }

    // Alterar os status da ordem
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_ordem = $_GET['id'];
        
        if(isset($_GET['statuss'])) {
            $novo_status = $_GET['statuss'];
             
            // Cria a query de atualização
            $sql = "UPDATE nova_ordem SET status_ordem = :statuss WHERE id_ordem = :id";
            $mensagem = 'Status da ordem alterado com sucesso!';
        
            // Prepara a query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':statuss', $novo_status);
            $stmt->bindParam(':id', $id_ordem, PDO::PARAM_INT);

            // Executa a query e exibe mensagens
            if($stmt->execute()){
                echo "<script>alert('$mensagem');window.location.href='consultar_ordem.php';</script>";
            } else{
                echo "<script>alert('Erro ao alterar status da ordem!');</script>";
            }
        }
    }

    // Processar ações (excluir, ocultar, mostrar)
    if (isset($_GET['acao']) && isset($_GET['id'])) {
        $id_ordem = $_GET['id'];
        
        if ($_GET['acao'] == 'excluir') {
            // Cria a query de exclusão
            $sql_excluir = "DELETE FROM nova_ordem WHERE id_ordem = :id";
            $stmt_excluir = $pdo->prepare($sql_excluir);
            $stmt_excluir->bindParam(':id', $id_ordem, PDO::PARAM_INT);
            
            // Executa a query e exbie mensagens
            if ($stmt_excluir->execute()) {
                echo "<script>alert('Ordem excluída com sucesso!'); window.location.href='consultar_ordem.php';</script>";
            } else {
                echo "<script>alert('Erro ao excluir ordem!');</script>";
            }
        }

        if ($_GET['acao'] == 'ocultar') {
            // Garante que não terá ordens duplicadas
            if (!in_array($id_ordem, $_SESSION['ordens_ocultas'])) {
                $_SESSION['ordens_ocultas'][] = $id_ordem;
            }
            echo "<script>alert('Ordem ocultada!'); window.location.href='consultar_ordem.php';</script>";
        }
        // Se a ação for "mostrar", remove o ID da ordem da lista de ordens ocultas na sessão e redireciona com alerta.
        if ($_GET['acao'] == 'mostrar') {
            if (($key = array_search($id_ordem, $_SESSION['ordens_ocultas'])) !== false) {
                unset($_SESSION['ordens_ocultas'][$key]);
            }
            echo "<script>alert('Ordem reativada!'); window.location.href='consultar_ordem.php?filtro_oculto=1';</script>";
        }
    }

    // Buscar dados de uma ordem específica (AJAX)
    if (isset($_GET['carregar_ordem']) && isset($_GET['id_ordem'])) {
        $id_ordem = $_GET['id_ordem'];
        
        // Cria a query de seleção
        $sql_ordem = "SELECT no.*, c.nome_cliente, u.nome_usuario as nome_tecnico 
                      FROM nova_ordem no 
                      LEFT JOIN cliente c ON no.id_cliente = c.id_cliente 
                      LEFT JOIN usuario u ON no.tecnico = u.id_usuario
                      WHERE no.id_ordem = :id_ordem";
        
        // Prepara a query
        $stmt_ordem = $pdo->prepare($sql_ordem);
        $stmt_ordem->bindParam(':id_ordem', $id_ordem, PDO::PARAM_INT);
        $stmt_ordem->execute();
        
        $ordem = $stmt_ordem->fetch(PDO::FETCH_ASSOC);
        
        if ($ordem) {
            header('Content-Type: application/json');
            echo json_encode($ordem);
            exit();
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Ordem não encontrada']);
            exit();
        }
    }

    // Buscar técnicos
    $sql_tecnicos = "SELECT id_usuario, nome_usuario FROM usuario WHERE id_perfil = 4 AND status = 'ativo' ORDER BY nome_usuario";
    $stmt_tecnicos = $pdo->query($sql_tecnicos);
    $tecnicos = $stmt_tecnicos->fetchAll(PDO::FETCH_ASSOC);

    // Buscar peças em estoque
    $sql_pecas = "SELECT id_peca_est, nome_peca, descricao_peca, preco, qtde FROM peca_estoque WHERE qtde > 0 ORDER BY nome_peca";
    $stmt_pecas = $pdo->query($sql_pecas);
    $pecas_estoque = $stmt_pecas->fetchAll(PDO::FETCH_ASSOC);

// Processar alteração de ordem
if (isset($_POST['alterar_ordem'])) {
    $id_ordem = $_POST['id_ordem'];
    
    // Atualizar dados da ordem
    $sql_update = "UPDATE nova_ordem SET 
                    nome_client_ordem = :nome_cliente,
                    tecnico = :tecnico,
                    marca_aparelho = :marca_aparelho,
                    prioridade = :prioridade,
                    problema = :problema,
                    dt_recebimento = :dt_recebimento,
                    valor_total = :valor_total,
                    observacao = :observacao
                  WHERE id_ordem = :id_ordem";
    
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        ':nome_cliente' => $_POST['nome_cliente'],
        ':tecnico' => $_POST['tecnico'],
        ':marca_aparelho' => $_POST['marca_aparelho'],
        ':prioridade' => $_POST['prioridade'],
        ':problema' => $_POST['problema'],
        ':dt_recebimento' => $_POST['dt_recebimento'],
        ':valor_total' => $_POST['valor_total'],
        ':observacao' => $_POST['observacao'],
        ':id_ordem' => $id_ordem
    ]);
    
    // Processar peças utilizadas - CÓDIGO CORRIGIDO
    $sql_pecas_atuais = "SELECT id_peca_est, quantidade FROM ordem_servico_pecas WHERE id_ordem = :id_ordem";
    $stmt_pecas_atuais = $pdo->prepare($sql_pecas_atuais);
    $stmt_pecas_atuais->execute([':id_ordem' => $id_ordem]);
    $pecas_antigas = $stmt_pecas_atuais->fetchAll(PDO::FETCH_ASSOC);
    
    // Criar mapa das peças antigas
    $pecas_antigas_map = [];
    foreach ($pecas_antigas as $peca) {
        $pecas_antigas_map[$peca['id_peca_est']] = (int)$peca['quantidade'];
    }
    
    // Processar peças novas do formulário
    $pecas_novas_map = [];
    
    // Buscar todos os campos POST que começam com "quantidade_"
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantidade_') === 0 && !empty($value)) {
            $id_peca_est = str_replace('quantidade_', '', $key);
            
            // Verificar se é numérico
            if (is_numeric($id_peca_est) && is_numeric($value) && $value > 0) {
                $pecas_novas_map[$id_peca_est] = (int)$value;
            }
        }
    }
    
    // Calcular diferenças e atualizar estoque
    foreach ($pecas_novas_map as $id_peca_est => $quantidade_nova) {
        $quantidade_antiga = isset($pecas_antigas_map[$id_peca_est]) ? $pecas_antigas_map[$id_peca_est] : 0;
        $diferenca = $quantidade_nova - $quantidade_antiga;
        
        if ($diferenca != 0) {
            // Verificar estoque atual
            $sql_check_stock = "SELECT qtde, nome_peca FROM peca_estoque WHERE id_peca_est = :id_peca_est";
            $stmt_check_stock = $pdo->prepare($sql_check_stock);
            $stmt_check_stock->execute([':id_peca_est' => $id_peca_est]);
            $estoque_info = $stmt_check_stock->fetch(PDO::FETCH_ASSOC);
            
            if ($estoque_info && $estoque_info['qtde'] < $diferenca) {
                echo "<script>alert('Erro: Estoque insuficiente para a peça \\\"{$estoque_info['nome_peca']}\\\"! Disponível: {$estoque_info['qtde']}, Necessário: $diferenca'); window.location.href='consultar_ordem.php';</script>";
                exit();
            }
            
            // Atualizar estoque pela diferença
            $sql_update_stock = "UPDATE peca_estoque SET qtde = qtde - :diferenca WHERE id_peca_est = :id_peca_est";
            $stmt_update_stock = $pdo->prepare($sql_update_stock);
            $stmt_update_stock->execute([
                ':diferenca' => $diferenca,
                ':id_peca_est' => $id_peca_est
            ]);
            
            // Verificar estoque baixo
            if ($estoque_info) {
                $novo_estoque = $estoque_info['qtde'] - $diferenca;
                if ($novo_estoque <= 3) {
                    echo "<script>alert('ATENÇÃO: Estoque da peça \\\"{$estoque_info['nome_peca']}\\\" está baixo ($novo_estoque unidades restantes)!');</script>";
                }
            }
        }
    }
    
    // Devolver ao estoque as peças que foram removidas
    foreach ($pecas_antigas_map as $id_peca_est => $quantidade_antiga) {
        if (!isset($pecas_novas_map[$id_peca_est])) {
            // Devolver ao estoque a quantidade que foi removida
            $sql_update_stock = "UPDATE peca_estoque SET qtde = qtde + :quantidade WHERE id_peca_est = :id_peca_est";
            $stmt_update_stock = $pdo->prepare($sql_update_stock);
            $stmt_update_stock->execute([
                ':quantidade' => $quantidade_antiga,
                ':id_peca_est' => $id_peca_est
            ]);
        }
    }
    
    // Remover todas as peças anteriores da ordem
    $sql_delete_pecas = "DELETE FROM ordem_servico_pecas WHERE id_ordem = :id_ordem";
    $stmt_delete_pecas = $pdo->prepare($sql_delete_pecas);
    $stmt_delete_pecas->execute([':id_ordem' => $id_ordem]);
    
    // Inserir novas peças utilizadas
    foreach ($pecas_novas_map as $id_peca_est => $quantidade) {
        $sql_insert_peca = "INSERT INTO ordem_servico_pecas (id_ordem, id_peca_est, quantidade) 
                            VALUES (:id_ordem, :id_peca_est, :quantidade)";
        $stmt_insert_peca = $pdo->prepare($sql_insert_peca);
        $stmt_insert_peca->execute([
            ':id_ordem' => $id_ordem,
            ':id_peca_est' => $id_peca_est,
            ':quantidade' => $quantidade
        ]);
    }
    
    echo "<script>alert('Ordem atualizada com sucesso!'); window.location.href='consultar_ordem.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
                        <small class="text-muted">Horário atual:</small>
                        <span id="liveClock" class="badge bg-secondary"></span>
                    </span>
                </div>
            </nav>

            <!-- Conteúdo - Formulário -->
            <div class="flex-grow-1 p-3" style="overflow-y: auto;">
                <div class="container-fluid">
                    <!-- Cabeçalho com título and botão de nova solicitação -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Solicitações de Serviço</h5>
                        <a href="nova_ordem.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Nova Solicitação
                        </a>
                    </div>
                    
                    <!-- Barra de pesquisa e filtros -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body py-2">
                            <form method="GET" action="consultar_ordem.php" id="filterForm">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" id="busca" name="busca" class="form-control" 
                                                placeholder="Pesquisar por ID ou nome do cliente..." 
                                                value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                                            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="filtro_oculto" id="filtro_oculto" class="form-select form-select-sm">
                                            <option value="0" <?= (isset($_GET['filtro_oculto']) && $_GET['filtro_oculto'] == '0') ? 'selected' : '' ?>>Ordens visíveis</option>
                                            <option value="1" <?= (isset($_GET['filtro_oculto']) && $_GET['filtro_oculto'] == '1') ? 'selected' : '' ?>>Ordens ocultas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="statuss" id="statuss" class="form-select form-select-sm">
                                            <option value="">Todos os status</option>
                                            <option value="Aberta" <?= (isset($_GET['statuss']) && $_GET['statuss'] == 'Aberta') ? 'selected' : '' ?>>Aberta</option>
                                            <option value="Em Andamento" <?= (isset($_GET['statuss']) && $_GET['statuss'] == 'Em Andamento') ? 'selected' : '' ?>>Em Andamento</option>
                                            <option value="Aguardando Peças" <?= (isset($_GET['statuss']) && $_GET['statuss'] == 'Aguardando Peças') ? 'selected' : '' ?>>Aguardando Peças</option>
                                            <option value="Concluído" <?= (isset($_GET['statuss']) && $_GET['statuss'] == 'Concluído') ? 'selected' : '' ?>>Concluído</option>
                                            <option value="Cancelada" <?= (isset($_GET['statuss']) && $_GET['statuss'] == 'Cancelada') ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <?php if(isset($_GET['busca']) || isset($_GET['filtro_oculto']) || isset($_GET['statuss'])): ?>
                                            <a href="consultar_ordem.php" class="btn btn-outline-danger btn-sm">Limpar Filtros</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabela de solicitações -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <?php if(!empty($ordens)): ?>
                                    <table class="table table-striped table-hover table-bordered mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th><center>ID</center></th>
                                                <th><center>Cliente</center></th>
                                                <th><center>Técnico</center></th>
                                                <th><center>Problema</center></th>
                                                <th><center>Data</center></th>
                                                <th><center>Valor (R$)</center></th>
                                                <th><center>Prioridade</center></th>
                                                <th><center>Status</center></th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($ordens as $ordem): ?>
                                                <tr>
                                                    <td><center><?= htmlspecialchars($ordem['id_ordem']) ?></center></td>
                                                    <td><center><?= htmlspecialchars($ordem['nome_cliente'] ? $ordem['nome_cliente'] : $ordem['nome_client_ordem']) ?></center></td>
                                                    <td><center><?= htmlspecialchars($ordem['nome_tecnico'] ? $ordem['nome_tecnico'] : 'Não atribuído') ?></center></td>
                                                    <td><center><?= htmlspecialchars(substr($ordem['problema'], 0, 30)) ?>...</center></td>
                                                    <td><center><?= htmlspecialchars($ordem['dt_recebimento']) ?></center></td>
                                                    <td><center>R$ <?= number_format($ordem['valor_total'], 2, ',', '.') ?></center></td>
                                                    <td><center><?= htmlspecialchars($ordem['prioridade']) ?></center></td>
                                                    <td>
                                                        <center>
                                                            <?php 
                                                            $badge_class = '';
                                                            switch($ordem['statuss']) {
                                                                case 'Aberta': $badge_class = 'bg-primary'; break;
                                                                case 'Em Andamento': $badge_class = 'bg-warning text-dark'; break;
                                                                case 'Aguardando Peças': $badge_class = 'bg-info'; break;
                                                                case 'Concluído': $badge_class = 'bg-success'; break;
                                                                case 'Cancelada': $badge_class = 'bg-danger'; break;
                                                                default: $badge_class = 'bg-secondary';
                                                            }
                                                            ?>
                                                            <span class="badge <?=$badge_class?>"><?=$ordem['statuss']?></span>
                                                        </center> 
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="btn btn-sm btn-primary me-1" title="Alterar" onclick="carregarDadosOrdem(<?= htmlspecialchars($ordem['id_ordem']) ?>)">Alterar</a>

                                                        <?php if ($filtro_oculto == '0'): ?>
                                                            <a href="consultar_ordem.php?acao=ocultar&id=<?= htmlspecialchars($ordem['id_ordem']) ?>" 
                                                                class="btn btn-sm btn-danger me-1" title="Ocultar" 
                                                                onclick="return confirm('Deseja ocultar esta ordem?')">
                                                                Inativar
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="consultar_ordem.php?acao=mostrar&id=<?= htmlspecialchars($ordem['id_ordem']) ?>" 
                                                                class="btn btn-sm btn-success me-1" title="Reativar" 
                                                                onclick="return confirm('Deseja tornar esta ordem visível novamente?')">
                                                                Reativar
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <button type="button" class="btn btn-sm <?= $badge_class ?> me-1" onclick="abrirModalStatus(<?=htmlspecialchars($ordem['id_ordem'])?>, '<?=$ordem['statuss']?>', '<?=htmlspecialchars($ordem['id_ordem'])?>')">
                                                            <i class="bi bi-gear-fill me-1"></i> Status
                                                        </button>
                                                        
                                                        <button class="btn btn-sm btn-info" onclick="mostrarDetalhes(<?=htmlspecialchars($ordem['id_ordem'])?>)">
                                                            <i class="bi bi-info-circle"></i> Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="p-3 text-center">
                                        <p>Nenhuma ordem de serviço encontrada.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Mostrando <?= count($ordens) ?> de <?= count($ordens) ?> registros</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Alterar Ordem -->
            <div class="modal fade" id="modalOrdem" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Ordem de Serviço</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="consultar_ordem.php">
                            <div class="modal-body">
                                <input type="hidden" id="id_ordem" name="id_ordem">
                                
                                <div class="mb-3">
                                    <label for="nome_cliente" class="form-label">Nome do Cliente</label>
                                    <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" required>
                                </div>
                                
                                <!-- Técnico -->
                                <div class="mb-2">
                                    <label for="tecnico" class="form-label">Técnico</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-person-gear"></i></span>
                                            <select class="form-select" id="tecnico" name="tecnico" required>
                                                <option value="" selected disabled>Selecione o técnico</option>
                                                <?php foreach ($tecnicos as $tecnico): ?>
                                                    <option value="<?= $tecnico['id_usuario'] ?>">
                                                        <?= htmlspecialchars($tecnico['nome_usuario']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                
                                <div class="mb-3">
                                    <label for="marca_aparelho" class="form-label">Marca do Aparelho</label>
                                    <input type="text" class="form-control" id="marca_aparelho" name="marca_aparelho">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prioridade" class="form-label">Prioridade</label>
                                    <select class="form-select" id="prioridade" name="prioridade">
                                        <option value="Baixa">Baixa</option>
                                        <option value="Média">Média</option>
                                        <option value="Alta">Alta</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="problema" class="form-label">Problema Relatado</label>
                                    <textarea class="form-control" id="problema" name="problema" rows="3"></textarea>
                                </div>
                                
                                <!-- Seção de Peças Utilizadas -->
                                <div class="mb-3">
                                    <label class="form-label">Peças Utilizadas</label>
                                    <div class="alert alert-info py-2">
                                        <small><i class="bi bi-info-circle"></i> As quantidades são limitadas conforme o estoque disponível.</small>
                                    </div>
                                    <div class="border p-3 rounded">
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAdicionarPeca">
                                                <i class="bi bi-plus-circle"></i> Adicionar Peça
                                            </button>
                                        </div>
                                        <div id="lista-pecas">
                                            <!-- As peças serão adicionadas aqui via JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="dt_recebimento" class="form-label">Data de Recebimento</label>
                                    <input type="date" class="form-control" id="dt_recebimento" name="dt_recebimento">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="valor_total" class="form-label">Valor Total (R$)</label>
                                    <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="observacao" class="form-label">Observações</label>
                                    <textarea class="form-control" id="observacao" name="observacao" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="alterar_ordem" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para Detalhes da Ordem -->
            <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detalhesModalLabel">Detalhes da Ordem de Serviço</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>ID:</strong> <span id="detalhesId"></span></p>
                                    <p><strong>Cliente:</strong> <span id="detalhesCliente"></span></p>
                                    <p><strong>Técnico:</strong> <span id="detalhesTecnico"></span></p>
                                    <p><strong>Data de Recebimento:</strong> <span id="detalhesData"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Valor:</strong> <span id="detalhesValor"></span></p>
                                    <p><strong>Prioridade:</strong> <span id="detalhesPrioridade"></span></p>
                                    <p><strong>Marca:</strong> <span id="detalhesMarca"></span></p>
                                    <p><strong>Status:</strong> <span id="detalhesStatus"></span></p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p><strong>Problema:</strong></p>
                                    <div class="border p-2 rounded bg-light">
                                        <span id="detalhesProblema"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p><strong>Observações:</strong></p>
                                    <div class="border p-2 rounded bg-light">
                                        <span id="detalhesObservacao"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Seção de Peças Utilizadas -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p><strong>Peças Utilizadas:</strong></p>
                                    <div class="border p-2 rounded bg-light">
                                        <div id="detalhesPecas">
                                            <p class="text-muted mb-0">Carregando informações das peças...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Alterar Status -->
            <div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Status da Ordem</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Alterar status da ordem: <strong id="idOrdemStatus"></strong></p>
                            <p>Status atual: <span id="statusAtual" class="badge"></span></p>
                            
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="selecionarStatus('Aberta')">
                                    <div>
                                        <span class="badge bg-primary me-2"><i class="bi bi-folder-plus"></i></span>
                                        Aberta
                                    </div>
                                    <small class="text-muted">Ordem recém-criada</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="selecionarStatus('Em Andamento')">
                                    <div>
                                        <span class="badge bg-warning me-2"><i class="bi bi-tools"></i></span>
                                        Em Andamento
                                    </div>
                                    <small class="text-muted">Ordem em execução</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="selecionarStatus('Aguardando Peças')">
                                    <div>
                                        <span class="badge bg-info me-2"><i class="bi bi-clock-history"></i></span>
                                        Aguardando Peças
                                    </div>
                                    <small class="text-muted">Aguardando peças para continuar</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="selecionarStatus('Concluído')">
                                    <div>
                                        <span class="badge bg-success me-2"><i class="bi bi-check-circle"></i></span>
                                        Concluído
                                    </div>
                                    <small class="text-muted">Ordem finalizada com sucesso</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="selecionarStatus('Cancelada')">
                                    <div>
                                        <span class="badge bg-danger me-2"><i class="bi bi-x-circle"></i></span>
                                        Cancelada
                                    </div>
                                    <small class="text-muted">Ordem cancelada</small>
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="confirmarStatus">Confirmar Alteração</button>
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

        // Submeter formulário quando os filtros forem alterados
        document.getElementById('filtro_oculto').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
        
        document.getElementById('statuss').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Função para carregar dados da ordem no modal via AJAX
        function carregarDadosOrdem(id) {
            // Mostrar loading no modal
            document.getElementById('id_ordem').value = id;
            document.getElementById('nome_cliente').value = 'Carregando...';
            document.getElementById('tecnico').value = 'Carregando...';
            document.getElementById('marca_aparelho').value = 'Carregando...';
            document.getElementById('problema').value = 'Carregando...';
            document.getElementById('dt_recebimento').value = '';
            document.getElementById('valor_total').value = '';
            document.getElementById('observacao').value = 'Carregando...';
            
            // Limpar lista de peças
            document.getElementById('lista-pecas').innerHTML = '';
            
            // Abre o modal primeiro
            var modal = new bootstrap.Modal(document.getElementById('modalOrdem'));
            modal.show();
            
            // Fazer requisição AJAX para buscar os dados reais da ordem
            fetch('consultar_ordem.php?carregar_ordem=true&id_ordem=' + id)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar dados da ordem');
                    }
                    return response.json();
                })
                .then(ordem => {
                    // Preencher o formulário com os dados reais
                    document.getElementById('id_ordem').value = ordem.id_ordem;
                    document.getElementById('nome_cliente').value = ordem.nome_cliente || ordem.nome_client_ordem || '';
                    document.getElementById('tecnico').value = ordem.tecnico || '';
                    document.getElementById('marca_aparelho').value = ordem.marca_aparelho || '';
                    document.getElementById('prioridade').value = ordem.prioridade || 'Média';
                    document.getElementById('problema').value = ordem.problema || '';
                    
                    // Formatar data para o input type="date"
                    if (ordem.dt_recebimento) {
                        const data = new Date(ordem.dt_recebimento);
                        const dataFormatada = data.toISOString().split('T')[0];
                        document.getElementById('dt_recebimento').value = dataFormatada;
                    }
                    
                    document.getElementById('valor_total').value = ordem.valor_total || '';
                    document.getElementById('observacao').value = ordem.observacao || '';
                    
                    // Carregar peças utilizadas nesta ordem
                    return fetch('buscar_pecas_ordem.php?id_ordem=' + id);
                })
                .then(response => response.json())
                .then(pecasUtilizadas => {
                    // Adicionar peças utilizadas ao formulário
                    if (pecasUtilizadas.length > 0) {
                        pecasUtilizadas.forEach(peca => {
                            adicionarPeca(peca.id_peca_est, peca.quantidade);
                        });
                    } else {
                        // Adicionar uma peça vazia se não houver peças
                        adicionarPeca();
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao carregar dados da ordem: ' + error.message);
                    // Adicionar uma peça vazia em caso de erro
                    adicionarPeca();
                });
        }

        // Função para mostrar detalhes da ordem
        function mostrarDetalhes(idOrdem) {
            // Fazer uma requisição AJAX para buscar os detalhes da ordem
            fetch(`buscar_detalhes_ordem.php?id=${idOrdem}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
            
                    // Preencher os campos do modal com os dados retornados
                    document.getElementById('detalhesId').textContent = data.id_ordem;
                    document.getElementById('detalhesCliente').textContent = data.nome_cliente;
                    document.getElementById('detalhesTecnico').textContent = data.nome_tecnico || 'Não atribuído'; // Nome do técnico
                    document.getElementById('detalhesData').textContent = data.dt_recebimento;
                    document.getElementById('detalhesValor').textContent = 'R$ ' + (data.valor_total ? parseFloat(data.valor_total).toFixed(2).replace('.', ',') : '0,00');
                    document.getElementById('detalhesPrioridade').textContent = data.prioridade;
                    document.getElementById('detalhesProblema').textContent = data.problema;
                    document.getElementById('detalhesMarca').textContent = data.marca_aparelho || 'Não informado';
                    document.getElementById('detalhesObservacao').textContent = data.observacao || 'Nenhuma observação';
            
                    // Status com badge colorido
                    const statusBadge = document.getElementById('detalhesStatus');
                    statusBadge.textContent = data.status_ordem;
            
                    // Aplicar classe CSS baseada no status
                    statusBadge.className = '';
                    switch(data.status_ordem) {
                        case 'Aberta': statusBadge.classList.add('badge', 'bg-primary'); break;
                        case 'Em Andamento': statusBadge.classList.add('badge', 'bg-warning', 'text-dark'); break;
                        case 'Aguardando Peças': statusBadge.classList.add('badge', 'bg-info'); break;
                        case 'Concluído': statusBadge.classList.add('badge', 'bg-success'); break;
                        case 'Cancelada': statusBadge.classList.add('badge', 'bg-danger'); break;
                        default: statusBadge.classList.add('badge', 'bg-secondary');
                    }
            
                    // Buscar informações das peças utilizadas
                    return fetch(`buscar_pecas_ordem.php?id_ordem=${idOrdem}`);
                })
                .then(response => response.json())
                .then(pecas => {
                    const pecasContainer = document.getElementById('detalhesPecas');
                    
                    if (pecas.length === 0) {
                        pecasContainer.innerHTML = '<p class="text-muted mb-0">Nenhuma peça utilizada nesta ordem.</p>';
                        return;
                    }
                    
                    let html = '<ul class="list-group list-group-flush">';
                    pecas.forEach(peca => {
                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 px-0 py-1">
                                <div>
                                    <span class="fw-medium">${peca.nome_peca}</span>
                                    <small class="text-muted d-block">${peca.descricao_peca || 'Sem descrição'}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill">${peca.quantidade} un.</span>
                                    <small class="text-muted d-block">R$ ${parseFloat(peca.preco).toFixed(2).replace('.', ',')} cada</small>
                                </div>
                            </li>
                        `;
                    });
                    html += '</ul>';
                    
                    pecasContainer.innerHTML = html;
                })
                .then(() => {
                    // Abrir o modal
                    const detalhesModal = new bootstrap.Modal(document.getElementById('detalhesModal'));
                    detalhesModal.show();
                })
                .catch(error => {
                    console.error('Erro ao buscar detalhes:', error);
                    alert('Não foi possível carregar os detalhes da ordem.');
                });
        }
        
        // Variáveis globais para controle do status
        let ordemIdStatus = null;
        let novoStatusSelecionado = null;

        // Função para abrir o modal de status
        function abrirModalStatus(id, statusAtual, idOrdem) {
            ordemIdStatus = id;
            novoStatusSelecionado = statusAtual;
            
            // Atualizar informações no modal
            document.getElementById('idOrdemStatus').textContent = idOrdem;
            
            // Atualizar badge do status atual
            const statusAtualEl = document.getElementById('statusAtual');
            let badgeClass = '';
            switch(statusAtual) {
                case 'Aberta': badgeClass = 'bg-primary'; break;
                case 'Em Andamento': badgeClass = 'bg-warning text-dark'; break;
                case 'Aguardando Peças': badgeClass = 'bg-info'; break;
                case 'Concluído': badgeClass = 'bg-success'; break;
                case 'Cancelada': badgeClass = 'bg-danger'; break;
                default: badgeClass = 'bg-secondary';
            }
            statusAtualEl.className = `badge ${badgeClass}`;
            statusAtualEl.textContent = statusAtual;
            
            // Destacar o status atual na lista
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
                if (item.textContent.includes(statusAtual)) {
                    item.classList.add('active');
                }
            });
            
            // Abrir o modal
            var modal = new bootstrap.Modal(document.getElementById('modalStatus'));
            modal.show();
        }

        // Função para selecionar um status
        function selecionarStatus(status) {
            novoStatusSelecionado = status;
            
            // Destacar o item selecionado
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
                if (item.textContent.includes(status)) {
                    item.classList.add('active');
                }
            });
        }

        // Configurar botão de confirmação
        document.getElementById('confirmarStatus').addEventListener('click', function() {
            if (novoStatusSelecionado && ordemIdStatus) {
                // Redirecionar para alterar o status
                window.location.href = `consultar_ordem.php?id=${ordemIdStatus}&statuss=${encodeURIComponent(novoStatusSelecionado)}`;
            }
        });

        // Variável global para armazenar as peças disponíveis
        const pecasDisponiveis = <?php echo json_encode($pecas_estoque); ?>;

        // Função para obter o estoque de uma peça
        function getEstoque(idPeca) {
            const peca = pecasDisponiveis.find(p => p.id_peca_est == idPeca);
            return peca ? parseInt(peca.qtde, 10) : 0;
        }

        // Função para calcular a quantidade já usada de uma peça (excluindo o item atual)
        function calcularQuantidadeUsada(idPeca, excludeIndex = null) {
            let soma = 0;
            document.querySelectorAll('.peca-item').forEach(item => {
                const idx = item.getAttribute('data-index');
                if (idx === excludeIndex) return;
                
                const select = item.querySelector('select[name="pecas_utilizadas[]"]');
                const qInput = item.querySelector('input[name="quantidades_utilizadas[]"]');
                
                if (select && select.value == idPeca && qInput) {
                    const q = parseInt(qInput.value || 0, 10);
                    soma += isNaN(q) ? 0 : q;
                }
            });
            return soma;
        }

        // Função para atualizar a quantidade máxima permitida para um item
        function atualizarQuantidadeMaxima(selectElement, index) {
            const idPeca = selectElement.value;
            const quantidadeInput = document.querySelector(`#quantidade-${index}`);
            const feedbackElement = document.querySelector(`#feedback-${index}`);
            
            if (!idPeca) {
                quantidadeInput.removeAttribute('max');
                if (feedbackElement) feedbackElement.textContent = '';
                return;
            }
            
            const estoqueTotal = getEstoque(idPeca);
            const usadoOutros = calcularQuantidadeUsada(idPeca, index);
            const maxDisponivel = Math.max(0, estoqueTotal - usadoOutros);
            
            quantidadeInput.max = maxDisponivel;
            
            // Atualizar feedback visual
            if (feedbackElement) {
                if (maxDisponivel === 0) {
                    feedbackElement.textContent = 'Estoque esgotado';
                    feedbackElement.className = 'form-text text-danger';
                } else if (maxDisponivel <= 3) {
                    feedbackElement.textContent = `ATENÇÃO: Estoque baixo (${maxDisponivel} unidades)`;
                    feedbackElement.className = 'form-text text-warning';
                } else {
                    feedbackElement.textContent = `(Estoque total: ${estoqueTotal})`;
                    feedbackElement.className = 'form-text text-muted';
                }
            }
            
            // Ajustar valor se exceder o novo máximo
            const valorAtual = parseInt(quantidadeInput.value || 0, 10);
            if (valorAtual > maxDisponivel) {
                quantidadeInput.value = maxDisponivel;
            }
            
            // Desabilitar se não houver estoque
            quantidadeInput.disabled = maxDisponivel === 0;
        }

        // Função para validar a quantidade inserida
        function validarQuantidade(index) {
            const quantidadeInput = document.querySelector(`#quantidade-${index}`);
            if (!quantidadeInput) return;
            
            const max = parseInt(quantidadeInput.max || 999, 10);
            let val = parseInt(quantidadeInput.value || 0, 10);
            
            if (isNaN(val) || val < 1) val = 1;
            if (val > max) val = max;
            
            quantidadeInput.value = val;
            
            // Atualizar os limites de todos os itens após alteração
            document.querySelectorAll('.peca-item').forEach(item => {
                const select = item.querySelector('select[name="pecas_utilizadas[]"]');
                const idx = item.getAttribute('data-index');
                if (select) atualizarQuantidadeMaxima(select, idx);
            });
        }

        // Função para remover uma peça da lista
        function removerPeca(button) {
            const pecaItem = button.closest('.peca-item');
            if (!pecaItem) return;
            
            pecaItem.remove();
            
            // Atualizar limites para os demais itens
            document.querySelectorAll('.peca-item').forEach(item => {
                const select = item.querySelector('select[name="pecas_utilizadas[]"]');
                const idx = item.getAttribute('data-index');
                if (select) atualizarQuantidadeMaxima(select, idx);
            });
        }

        // Função para adicionar uma nova peça à lista
function adicionarPeca(idPeca = '', quantidade = 1) {
    const listaPecas = document.getElementById('lista-pecas');
    const index = Date.now(); // ID único para a peça

    const pecaItem = document.createElement('div');
    pecaItem.className = 'peca-item mb-3 p-3 border rounded';
    pecaItem.setAttribute('data-index', index);

    // Usar o ID da peça no nome do campo de quantidade
    const fieldName = idPeca ? `quantidade_${idPeca}` : `quantidade_new_${index}`;
    
    pecaItem.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-5">
                <label class="form-label small mb-1">Peça</label>
                <select name="pecas_utilizadas[]" class="form-select" onchange="atualizarQuantidadeMaxima(this, '${index}')">
                    <option value="">Selecione uma peça</option>
                    ${pecasDisponiveis.map(peca => `
                        <option value="${peca.id_peca_est}" ${peca.id_peca_est == idPeca ? 'selected' : ''}
                                data-preco="${peca.preco}" data-estoque="${peca.qtde}">
                            ${peca.nome_peca} (Estoque: ${peca.qtde})
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Quantidade</label>
                <input type="number" name="${fieldName}" class="form-control" 
                       id="quantidade-${index}" value="${quantidade}" min="1" 
                       onchange="validarQuantidade('${index}')" />
                <div id="feedback-${index}" class="form-text"></div>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Ação</label>
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removerPeca(this)">
                    <i class="bi bi-trash"></i> Remover
                </button>
            </div>
        </div>
    `;
    listaPecas.appendChild(pecaItem);

    // Se já tiver uma peça selecionada, atualizar a quantidade máxima
    const selectEl = pecaItem.querySelector('select[name="pecas_utilizadas[]"]');
    if (idPeca) {
        atualizarQuantidadeMaxima(selectEl, index);
    }
}

        // Adicionar evento ao botão de adicionar peça
        document.getElementById('btnAdicionarPeca').addEventListener('click', function() {
            adicionarPeca();
        });
    </script>
</body>
</html>