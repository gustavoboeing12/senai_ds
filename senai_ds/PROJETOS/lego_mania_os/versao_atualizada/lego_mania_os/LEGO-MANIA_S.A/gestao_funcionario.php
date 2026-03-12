<?php
    session_start();

    require_once 'conexao.php';
    require_once 'php/permissoes.php';
    require_once 'php/estatisticas.php';

    // VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM OU SECRETARIA
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    } 

    // INICIALIZA VARIÁVEIS
    $filtro_status = '';
    $busca = '';
    $funcionarios = [];

    // VERIFICA SE HÁ FILTRO POR STATUS (GET)
    if(isset($_GET['status']) && !empty($_GET['status'])) {
        $filtro_status = $_GET['status'];
    }

    // VERIFICA SE HÁ BUSCA POR TEXTO (GET)
    if(isset($_GET['busca']) && !empty($_GET['busca'])){
        $busca = trim($_GET['busca']);
    }

    // CONSTRUIR A QUERY PARA BUSCAR AS INFORMAÇÕES/MOTIVO DO FUNCIONARIO POR SELECT
    $sql = "SELECT f.*, m.descricao as motivo_inatividade 
            FROM funcionario f 
            LEFT JOIN motivo_inatividade m ON f.id_motivo_inatividade = m.id_motivo";
    $where_conditions = [];
    $params = [];

    // ADICIONAR FILTRO POR STATUS SE EXISTIR
    if(!empty($filtro_status)) {
        $where_conditions[] = "f.status = :status";
        $params[':status'] = $filtro_status;
    }

    // ADICIONAR FILTRO POR BUSCA SE EXISTIR
    if(!empty($busca)) {
        if(is_numeric($busca)){
            $where_conditions[] = "f.id_funcionario = :busca";
            $params[':busca'] = $busca;
        } else {
            $where_conditions[] = "(f.nome_funcionario LIKE :busca_nome OR f.cpf_funcionario LIKE :busca_cpf)";
            $params[':busca_nome'] = "$busca%";
            $params[':busca_cpf'] = "$busca%";
        }
    }

    // COMBINAR CONDITIONS SE HOUVER
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY f.nome_funcionario ASC";

    // PREPARAR E EXECUTAR A QUERY
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    // INATIVAR FUNCIONARIO //

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_funcionario = $_GET['id'];
        
        // Verificar se está inativando ou reativando
        if(isset($_GET['acao']) && $_GET['acao'] == 'reativar') {
            // Reativar funcionário
            $sql = "UPDATE funcionario SET status = 'Ativo', id_motivo_inatividade = NULL, data_inatividade = NULL, observacao_inatividade = NULL WHERE id_funcionario = :id";
            $mensagem = 'Funcionário reativado com sucesso!';
        } else {
            // Inativar funcionário - redireciona para página de inativação
            header("Location: inativar_funcionario.php?id=" . $id_funcionario);
            exit();
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_funcionario, PDO::PARAM_INT);

        if($stmt->execute()){
            echo "<script>alert('$mensagem');window.location.href='gestao_funcionario.php';</script>";
        } else{
            echo "<script>alert('Erro ao alterar status do funcionário!');</script>";
        }
    }

    // Obter estatísticas de funcionários
    $estatisticasFuncionarios = gerarEstatisticas('funcionario');

    // Função para calcular a média salarial dos funcionários
    function calcularMediaSalarial() {
        global $pdo;
    
        // Faz uma busca por média de salario dos funcionarios ativos.
        $sql = "SELECT AVG(salario) as media_salarial FROM funcionario WHERE status = 'Ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $resultado['media_salarial'] ?: 0;
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Funcionários - Lego Mania</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/validacoes_form.js"></script>
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
                    <!-- Cabeçalho com título e botão de novo funcionário -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Gestão de Funcionários</h5>
                        <div>
                            <!-- Botão de Estatísticas -->
                            <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalEstatisticas">
                                <i class="bi bi-graph-up me-1"></i> Estatísticas
                            </button>
                            
                            <a href="cadastro_funcionario.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Novo Funcionário
                            </a>
                        </div>
                    </div>
                    
                    <!-- Barra de pesquisa e filtros -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body py-2">
                            <form method="GET" action="gestao_funcionario.php" id="filterForm">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" id="busca" name="busca" class="form-control" 
                                                placeholder="Pesquisar por ID, nome ou CPF..." 
                                                value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                                            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <!-- Busca por todos os status ou status selecionados(ativos/inativos) -->
                                        <select name="status" id="status" class="form-select form-select-sm">
                                            <option value="">Todos os status</option>
                                            <option value="Ativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
                                            <option value="Inativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Inativo') ? 'selected' : '' ?>>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <?php if(isset($_GET['busca']) || isset($_GET['status'])): ?>
                                            <a href="gestao_funcionario.php" class="btn btn-outline-danger btn-sm">Limpar Filtros</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabela de Funcionários -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <?php if(!empty($funcionarios)): ?>
                                    <table class="table table-striped table-hover table-bordered mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th><center>ID</center></th>
                                                <th><center>Nome</center></th>
                                                <th><center>CPF</center></th>
                                                <th><center>Salário (R$)</center></th>
                                                <th><center>Status</center></th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($funcionarios as $funcionario): ?>
                                                <tr>
                                                    <td><center><?=htmlspecialchars($funcionario['id_funcionario'])?></center></td>
                                                    <td><center><?=htmlspecialchars($funcionario['nome_funcionario'])?></center></td>
                                                    <td><center><?=htmlspecialchars($funcionario['cpf_funcionario'])?></center></td>
                                                    <td><center>R$ <?=number_format($funcionario['salario'], 2, ',', '.')?></center></td>
                                                    <td>
                                                        <center>
                                                            <?php if($funcionario['status'] == 'Ativo'): ?>
                                                                <span class="badge bg-success">Ativo</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger badge-details" onclick="mostrarDetalhesFuncionario(<?=htmlspecialchars($funcionario['id_funcionario'])?>)">
                                                                    Inativo <i class="bi bi-info-circle"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </center> 
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="btn btn-sm btn-primary me-1" onclick="carregarDadosFuncionario(<?=htmlspecialchars($funcionario['id_funcionario'])?>)">Alterar</a> 
                                                        
                                                        <?php if($funcionario['status'] == 'Ativo'): ?>
                                                            <a href="inativar_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario'])?>" class="btn btn-sm btn-danger me-1">Inativar</a>
                                                        <?php else: ?>
                                                            <a href="gestao_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario'])?>&acao=reativar" class="btn btn-sm btn-success me-1" onclick="return confirm('Tem certeza que deseja reativar este funcionário?')">Reativar</a>
                                                        <?php endif; ?>
                                                        
                                                        <button class="btn btn-sm btn-info" onclick="mostrarDetalhesFuncionario(<?=htmlspecialchars($funcionario['id_funcionario'])?>)">
                                                            <i class="bi bi-info-circle"></i> Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                <?php else: ?><br>
                                    <center><p>Nenhum funcionário encontrado.</p></center>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Mostrando <?= count($funcionarios) ?> de <?= count($funcionarios) ?> funcionários</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Alterar Funcionário -->
            <div class="modal fade" id="modalFuncionario" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Funcionário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="php/alterar_funcionario.php">
                            <div class="modal-body">
                                <input type="hidden" id="id_funcionario" name="id_funcionario">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nome_funcionario" class="form-label">Nome do Funcionário</label>
                                            <input type="text" class="form-control" id="nome_funcionario" name="nome_funcionario" oninput="this.value=this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cpf_funcionario" class="form-label">CPF</label>
                                            <input type="text" class="form-control" id="cpf_funcionario" name="cpf_funcionario" oninput="mascaraCPF_func()" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="salario" class="form-label">Salário (R$)</label>
                                            <input type="number" step="0.01" class="form-control" id="salario" name="salario" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" class="form-control" id="telefone" oninput="mascaraTelefone()"
                                            name="telefone">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="endereco" class="form-label">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bairro" class="form-label">Bairro</label>
                                            <input type="text" class="form-control" id="bairro" name="bairro">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cidade" class="form-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidade" name="cidade">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">Estado</label>
                                            <input type="text" class="form-control" id="estado" name="estado">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cep" class="form-label">CEP</label>
                                            <input type="text" class="form-control" id="cep" name="cep" oninput="mascaraCEP()">>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="dt_nascimento" class="form-label">Data de Nascimento</label>
                                            <input type="date" class="form-control" id="dt_nascimento" name="dt_nascimento">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="alterar_funcionario" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para Detalhes do Funcionário -->
            <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalhes do Funcionário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="detalhesFuncionario">
                            <!-- Conteúdo será preenchido via JavaScript -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Estatísticas -->
            <div class="modal fade" id="modalEstatisticas" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Estatísticas de Funcionários</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Distribuição por Cidade</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm" id="tabelaCidades">
                                                    <thead>
                                                        <tr>
                                                            <th>Cidade</th>
                                                            <th class="text-end">Quantidade</th>
                                                            <th class="text-end">Percentual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $totalFuncionarios = $estatisticasFuncionarios['total'];
                                                        foreach($estatisticasFuncionarios['cidades'] as $cidade):
                                                            $percentual = $totalFuncionarios > 0 ? round(($cidade['quantidade'] / $totalFuncionarios) * 100, 1) : 0;
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($cidade['cidade'] ?: 'Não informada') ?></td>
                                                            <td class="text-end"><?= $cidade['quantidade'] ?></td>
                                                            <td class="text-end"><?= $percentual ?>%</td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Status dos Funcionários</h6>
                                            <div class="d-flex justify-content-around text-center">
                                                <div>
                                                    <div class="fs-2 text-success"><?= $estatisticasFuncionarios['ativos'] ?></div>
                                                    <div class="text-muted">Ativos</div>
                                                </div>
                                                <div>
                                                    <div class="fs-2 text-danger"><?= $estatisticasFuncionarios['inativos'] ?></div>
                                                    <div class="text-muted">Inativos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Resumo Geral</h6>
                                            <div class="mb-2">Total de Funcionários: <strong><?= $estatisticasFuncionarios['total'] ?></strong></div>
                                            <div class="mb-2">Ativos: <strong><?= $estatisticasFuncionarios['ativos'] ?></strong></div>
                                            <div class="mb-2">Inativos: <strong><?= $estatisticasFuncionarios['inativos'] ?></strong></div>
                                            <div>Média Salarial: <strong>R$ <?= number_format(calcularMediaSalarial(), 2, ',', '.') ?></strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" onclick="exportarEstatisticas('pdf')">Exportar PDF</button>
                        </div>
                    </div>
                </div>
            </div>

    <!-- SCRIPTS -->
                                    
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

        // Função para carregar dados do funcionário no modal
        function carregarDadosFuncionario(id) {
            fetch('buscar_funcionario.php?id=' + id)
                .then(response => response.json())
                .then(funcionario => {
                    document.getElementById('id_funcionario').value = funcionario.id_funcionario;
                    document.getElementById('nome_funcionario').value = funcionario.nome_funcionario;
                    document.getElementById('cpf_funcionario').value = funcionario.cpf_funcionario;
                    document.getElementById('salario').value = funcionario.salario;
                    document.getElementById('email').value = funcionario.email || '';
                    document.getElementById('telefone').value = funcionario.telefone || '';
                    document.getElementById('endereco').value = funcionario.endereco || '';
                    document.getElementById('bairro').value = funcionario.bairro || '';
                    document.getElementById('cidade').value = funcionario.cidade || '';
                    document.getElementById('estado').value = funcionario.estado || '';
                    document.getElementById('cep').value = funcionario.cep || '';
                    document.getElementById('dt_nascimento').value = funcionario.dt_nascimento || '';
                    
                    // Abre o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalFuncionario'));
                    modal.show();
                })
                .catch(error => console.error('Erro:', error));
        }

        // Submeter formulário quando os filtros forem alterados
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Função para mostrar detalhes do funcionário (atualizada)
        function mostrarDetalhesFuncionario(id) {
            fetch('buscar_funcionario.php?id=' + id)
                .then(response => response.json())
                .then(funcionarioBasico => {
                    // Busca os dados completos com informações de inatividade
                    fetch('buscar_dados_completos_funcionario.php?id=' + id)
                        .then(response => response.json())
                        .then(funcionarioCompleto => {
                            let detalhesHTML = `
                                <div class="mb-3">
                                    <strong>ID:</strong> ${funcionarioBasico.id_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>Nome:</strong> ${funcionarioBasico.nome_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>CPF:</strong> ${funcionarioBasico.cpf_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>Salário:</strong> R$ ${parseFloat(funcionarioBasico.salario).toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                                </div>
                                <div class="mb-3">
                                    <strong>E-mail:</strong> ${funcionarioBasico.email || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Telefone:</strong> ${funcionarioBasico.telefone || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Endereço:</strong> ${funcionarioBasico.endereco || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Bairro:</strong> ${funcionarioBasico.bairro || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Cidade:</strong> ${funcionarioBasico.cidade || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Estado:</strong> ${funcionarioBasico.estado || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>CEP:</strong> ${funcionarioBasico.cep || 'Não informado'}
                                </div>
                                <div class="mb-3">
                                    <strong>Data de Nascimento:</strong> ${funcionarioBasico.dt_nascimento || 'Não informada'}
                                </div>
                            `;

                            // Adiciona informações de inatividade se disponíveis
                            if (funcionarioCompleto.status === 'Inativo') {
                                let tempoInativo = '';
                                if (funcionarioCompleto.data_inatividade) {
                                    const dataInatividade = new Date(funcionarioCompleto.data_inatividade);
                                    const agora = new Date();
                                    const diffTime = Math.abs(agora - dataInatividade);
                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                    tempoInativo = `${diffDays} dia(s)`;
                                }

                                detalhesHTML += `
                                    <div class="mb-3">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-danger">Inativo</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Data de Inativação:</strong> ${funcionarioCompleto.data_inatividade || 'Não informada'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Tempo Inativo:</strong> ${tempoInativo || 'Não calculado'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Motivo da Inativação:</strong> ${funcionarioCompleto.motivo_inatividade || 'Não informado'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Observações:</strong> ${funcionarioCompleto.observacao_inatividade || 'Nenhuma observação'}
                                    </div>
                                `;
                            } else {
                                detalhesHTML += `
                                    <div class="mb-3">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-success">Ativo</span>
                                    </div>
                                `;
                            }

                            document.getElementById('detalhesFuncionario').innerHTML = detalhesHTML;
                            
                            // Abre o modal
                            var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
                            modal.show();
                        })
                        .catch(error => {
                            // Se falhar a segunda requisição, mostra apenas os dados básicos
                            let detalhesHTML = `
                                <div class="mb-3">
                                    <strong>ID:</strong> ${funcionarioBasico.id_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>Nome:</strong> ${funcionarioBasico.nome_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>CPF:</strong> ${funcionarioBasico.cpf_funcionario}
                                </div>
                                <div class="mb-3">
                                    <strong>Salário:</strong> R$ ${parseFloat(funcionarioBasico.salario).toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                                </div>
                                <div class="mb-3 text-warning">
                                    <em>Informações de status não disponíveis</em>
                                </div>
                            `;
                            document.getElementById('detalhesFuncionario').innerHTML = detalhesHTML;
                            
                            var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
                            modal.show();
                        });
                })
                .catch(error => {
                    alert('Erro ao carregar dados do funcionário');
                    console.error('Erro:', error);
                });
        }

        // Função para exportar estatísticas
        function exportarEstatisticas(formato) {
            const dados = {
                titulo: 'Relatório de Estatísticas de Funcionários - ' + new Date().toLocaleDateString('pt-BR'),
                totalFuncionarios: <?= $estatisticasFuncionarios['total'] ?>,
                ativos: <?= $estatisticasFuncionarios['ativos'] ?>,
                inativos: <?= $estatisticasFuncionarios['inativos'] ?>,
                mediaSalarial: <?= calcularMediaSalarial() ?>,
                cidades: <?= json_encode($estatisticasFuncionarios['cidades']) ?>
            };

            if (formato === 'pdf') {
                exportarPDF(dados);
            }
        }

        // Função para exportar PDF
        function exportarPDF(dados) {
            // Criar tabela de cidades
            let tabelaCidades = '';
            dados.cidades.forEach(cidade => {
                const percentual = dados.totalFuncionarios > 0 ? 
                    ((cidade.quantidade / dados.totalFuncionarios) * 100).toFixed(1) : 0;
                tabelaCidades += `
                    <tr>
                        <td>${cidade.cidade || 'Não informada'}</td>
                        <td>${cidade.quantidade}</td>
                        <td>${percentual}%</td>
                    </tr>
                `;
            });

            const conteudo = `
                <html>
                <head>
                    <title>${dados.titulo}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #333; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                        .total { font-weight: bold; }
                    </style>
                </head>
                <body>
                    <h1>${dados.titulo}</h1>
                    
                    <h2>Resumo Geral</h2>
                    <table>
                        <tr><th>Total de Funcionários</th><td>${dados.totalFuncionarios}</td></tr>
                        <tr><th>Funcionários Ativos</th><td>${dados.ativos}</td></tr>
                        <tr><th>Funcionários Inativos</th><td>${dados.inativos}</td></tr>
                        <tr><th>Média Salarial</th><td>R$ ${dados.mediaSalarial.toFixed(2).replace('.', ',')}</td></tr>
                    </table>
                    
                    <h2>Distribuição por Cidade</h2>
                    <table>
                        <tr><th>Cidade</th><th>Quantidade</th><th>Percentual</th></tr>
                        ${tabelaCidades}
                    </table>
                    
                    <p><small>Gerado em: ${new Date().toLocaleString('pt-BR')}</small></p>
                </body>
                </html>
            `;
            
            const janela = window.open('', '_blank');
            janela.document.write(conteudo);
            janela.document.close();
            janela.print();
        }
    </script>

</body>
</html>