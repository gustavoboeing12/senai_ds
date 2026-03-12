<?php
    session_start();
    require_once 'conexao.php';
    require_once 'php/permissoes.php';
    require_once 'php/estatisticas.php';

    // Coletar filtros atuais para estatísticas
    $filtrosEstatisticas = [];
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $filtrosEstatisticas['status'] = $_GET['status'];
    }

    // VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM OU SECRETARIA
    if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=3){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    } 

    // INICIALIZA VARIÁVEIS
    $filtro_status = '';
    $busca = '';
    $clientes = [];

    // VERIFICA SE HÁ FILTRO POR STATUS (GET)
    if(isset($_GET['status']) && !empty($_GET['status'])) {
        $filtro_status = $_GET['status'];
    }

    // VERIFICA SE HÁ BUSCA POR TEXTO (GET)
    if(isset($_GET['busca']) && !empty($_GET['busca'])){
        $busca = trim($_GET['busca']);
    }

    // Construir o select para pegar informações do cliente.
        $sql = "SELECT c.id_cliente,
        c.nome_cliente,
        c.cpf_cnpj,
        c.endereco,
        c.bairro,
        c.cidade,
        c.estado,
        c.telefone,
        c.email,
        c.status,
        c.data_inatividade,
        c.observacao_inatividade,
        f.nome_funcionario 
        FROM cliente c 
        LEFT JOIN funcionario f ON c.id_funcionario = f.id_funcionario";

    $where_conditions = [];
    $params = [];

    // ADICIONAR FILTRO POR STATUS SE EXISTIR
    if(!empty($filtro_status)) {
        $where_conditions[] = "c.status = :status";
        $params[':status'] = $filtro_status;
    }

    // ADICIONAR FILTRO POR BUSCA SE EXISTIR
    if(!empty($busca)) {
        if(is_numeric($busca)){
            $where_conditions[] = "c.id_cliente = :busca";
            $params[':busca'] = $busca;
        } else {
            $where_conditions[] = "(c.nome_cliente LIKE :busca_nome OR c.cpf_cnpj LIKE :busca_cpf)";
            $params[':busca_nome'] = "$busca%";
            $params[':busca_cpf'] = "$busca%";
        }
    }

    // COMBINAR CONDITIONS SE HOUVER
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions); // implode: unir um array de strings em uma única string
    }

    // .= Concatenação e Atribuição
    $sql .= " ORDER BY c.nome_cliente ASC";

    // PREPARAR E EXECUTAR A QUERY
    $stmt = $pdo->prepare($sql);

    // Para pelos parametros chaves e encapsula
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    // INATIVAR CLIENTE //

    // Inativar Cliente em vez de excluir
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_cliente = $_GET['id'];
        
        // Verificar se está inativando ou reativando
        if(isset($_GET['acao']) && $_GET['acao'] == 'reativar') {
            // Reativar cliente
            $sql = "UPDATE cliente SET status = 'Ativo', data_inatividade = NULL, observacao_inatividade = NULL WHERE id_cliente = :id";
            $mensagem = 'Cliente reativado com sucesso!';
        } else {
            // Inativar cliente - redireciona para página de inativação
            header("Location: inativar_cliente.php?id=" . $id_cliente);
            exit();
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT); // Protege ID do cliente

        if($stmt->execute()){ // se der certo emite a mensagem e retorna para "gestao_cliente.php"
            echo "<script>alert('$mensagem');window.location.href='gestao_cliente.php';</script>";
        } else{
            echo "<script>alert('Erro ao alterar status do cliente!');</script>";
        }
    }

    // Calcular estatísticas por cidade
    $cidades = [];
    foreach($clientes as $cliente) {
        $cidade = $cliente['cidade'] ?: 'Não informada';
        if(!isset($cidades[$cidade])) {
            $cidades[$cidade] = 0;
        }
        $cidades[$cidade]++;
    }
    arsort($cidades); // Retorna as cidades por ordem do maior para o menor
    $totalClientes = count($clientes); // total clientes
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
                    <!-- Cabeçalho com título and botão de novo cliente -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Gestão de Clientes</h5>
                        <div>
                            <!-- Botão de Estatísticas - MODIFICADO para funcionar igual ao de usuários -->
                            <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalEstatisticas">
                                <i class="bi bi-graph-up me-1"></i> Estatísticas
                            </button>
                            <a href="cadastro_cliente.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Novo Cliente
                            </a>
                        </div>
                    </div>
                    
                    <!-- Barra de pesquisa e filtros -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body py-2">
                            <form method="GET" action="gestao_cliente.php" id="filterForm">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" id="busca" name="busca" class="form-control" 
                                                placeholder="Pesquisar por ID, nome ou CPF/CNPJ..." 
                                                value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                                            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" id="status" class="form-select form-select-sm">
                                            <option value="">Todos os status</option>
                                            <option value="Ativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
                                            <option value="Inativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Inativo') ? 'selected' : '' ?>>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <?php if(isset($_GET['busca']) || isset($_GET['status'])): ?>
                                            <a href="gestao_cliente.php" class="btn btn-outline-danger btn-sm">Limpar Filtros</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabela de clientes -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <?php if(!empty($clientes)): ?>
                                    <table class="table table-striped table-hover table-bordered mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th><center>ID</center></th>
                                                <th><center>Nome</center></th>
                                                <th><center>CPF/CNPJ</center></th>
                                                <th><center>Telefone</center></th>
                                                <th><center>E-mail</center></th>
                                                <th><center>Status</center></th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Passa pelo array(BD) e traz as consultas por meio da QUERY do cliente -->
                                            <?php foreach($clientes as $cliente): ?>
                                                <tr>
                                                    <!-- Consultas por meio da QUERY(cliente) sendo chamada -->
                                                    <td><center><?=htmlspecialchars($cliente['id_cliente'])?></center></td>
                                                    <td><center><?=htmlspecialchars($cliente['nome_cliente'])?></center></td>
                                                    <td><center><?=htmlspecialchars($cliente['cpf_cnpj'])?></center></td>
                                                    <td><center><?=htmlspecialchars($cliente['telefone'])?></center></td>
                                                    <td><center><?=htmlspecialchars($cliente['email'])?></center></td>
                                                    <td>
                                                        <center>
                                                            <?php if($cliente['status'] == 'Ativo'): ?>
                                                                <span class="badge bg-success">Ativo</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger badge-details" onclick="mostrarDetalhesCliente(<?=htmlspecialchars($cliente['id_cliente'])?>)">
                                                                    Inativo <i class="bi bi-info-circle"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </center> 
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="btn btn-sm btn-primary me-1" onclick="carregarDadosCliente(<?=htmlspecialchars($cliente['id_cliente'])?>)">Alterar</a>
                                                        
                                                        <?php if($cliente['status'] == 'Ativo'): ?>
                                                            <a href="inativar_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" class="btn btn-sm btn-danger me-1">Inativar</a>
                                                        <?php else: ?>
                                                            <a href="gestao_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>&acao=reativar" class="btn btn-sm btn-success me-1" onclick="return confirm('Tem certeza que deseja reativar este cliente?')">Reativar</a>
                                                        <?php endif; ?>
                                                        
                                                        <button class="btn btn-sm btn-info" onclick="mostrarDetalhesCliente(<?=htmlspecialchars($cliente['id_cliente'])?>)">
                                                            <i class="bi bi-info-circle"></i> Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>

                                <?php else: ?><br>
                                    <center><p>Nenhum cliente encontrado.</p></center>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Mostrando <?= count($clientes) ?> de <?= count($clientes) ?> clientes</span>
                                </div>
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Alterar Cliente -->
            <div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="php/alterar_cliente.php">
                            <div class="modal-body">
                                <input type="hidden" id="id_cliente" name="id_cliente">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nome_cliente" class="form-label">Nome do Cliente *</label>
                                            <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" oninput="this.value=this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'')" required >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cpf_cnpj" class="form-label">CPF/CNPJ *</label>
                                            <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj" oninput="mascaraCPFCNPJ()" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" class="form-control" id="telefone" name="telefone" oninput="mascaraTelefone()">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email">
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
                                            <label for="cep" class="form-label">CEP</label>
                                            <input type="text" class="form-control" id="cep" name="cep" oninput="mascaraCEP()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cidade" class="form-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidade" name="cidade">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="alterar_cliente" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para Detalhes do Cliente -->
            <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalhes do Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="detalhesCliente">
                            <!-- Conteúdo será preenchido via JavaScript -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                        </div>
                </div>
            </div>

            <!-- Modal para Estatísticas - MODIFICADO para funcionar igual ao de usuários -->
            <div class="modal fade" id="modalEstatisticas" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Estatísticas de Clientes</h5>
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
                                                        <!-- Mostra o total de cidades e o percentual de cidades -->
                                                        <?php
                                                        $totalClientes = count($clientes);
                                                        foreach($cidades as $cidade => $quantidade):
                                                            $percentual = $totalClientes > 0 ? round(($quantidade / $totalClientes) * 100, 1) : 0;
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($cidade) ?></td>
                                                            <td class="text-end"><?= $quantidade ?></td>
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
                                            <h6 class="card-title">Status dos Clientes</h6>
                                            <div class="d-flex justify-content-around text-center">
                                                <div>
                                                    <div class="fs-2 text-success"><?= count(array_filter($clientes, fn($c) => $c['status'] === 'Ativo')) ?></div>
                                                    <div class="text-muted">Ativos</div>
                                                </div>
                                                <div>
                                                    <div class="fs-2 text-danger"><?= count(array_filter($clientes, fn($c) => $c['status'] === 'Inativo')) ?></div>
                                                    <div class="text-muted">Inativos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Resumo Geral</h6>
                                            <div class="mb-2">Total de Clientes: <strong><?= count($clientes) ?></strong></div>
                                            <div class="mb-2">Clientes Ativos: <strong><?= count(array_filter($clientes, fn($c) => $c['status'] === 'Ativo')) ?></strong></div>
                                            <div class="mb-2">Clientes Inativos: <strong><?= count(array_filter($clientes, fn($c) => $c['status'] === 'Inativo')) ?></strong></div>
                                            <div>Total de Cidades: <strong><?= count($cidades) ?></strong></div>
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

        // Função de mostrar a hora
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('liveClock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock(); // Inicializa imediatamente

        // Função para carregar dados do cliente no modal
        function carregarDadosCliente(id) {
            fetch('buscar_cliente.php?id=' + id)
                .then(response => response.json())
                .then(cliente => {
                    if (cliente.erro) {
                        alert(cliente.erro);
                        return;
                    }

                    // Mostra os detalhes no modal das informações do cliente
                    document.getElementById('id_cliente').value = cliente.id_cliente;
                    document.getElementById('nome_cliente').value = cliente.nome_cliente;
                    document.getElementById('cpf_cnpj').value = cliente.cpf_cnpj;
                    document.getElementById('telefone').value = cliente.telefone || '';
                    document.getElementById('email').value = cliente.email || '';
                    document.getElementById('endereco').value = cliente.endereco || '';
                    document.getElementById('bairro').value = cliente.bairro || '';
                    document.getElementById('cep').value = cliente.cep || '';
                    document.getElementById('cidade').value = cliente.cidade || '';
                    document.getElementById('estado').value = cliente.estado || '';
                    
                    // Abre o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalCliente'));
                    modal.show();
                })
                .catch(error => {
                    alert('Erro ao carregar dados do cliente');
                    console.error('Erro:', error);
                });
        }

        // Função para mostrar detalhes do cliente
        function mostrarDetalhesCliente(id) {
            // Busca os dados completos do cliente
            fetch('buscar_dados_completos_cliente.php?id=' + id)
                .then(response => response.json())
                .then(cliente => {
                    if (cliente.erro) {
                        alert(cliente.erro);
                        return;
                    }

                    // Caso não seja informado
                    let detalhesHTML = `
                        <div class="mb-3">
                            <strong>ID:</strong> ${cliente.id_cliente}
                        </div>
                        <div class="mb-3">
                            <strong>Nome:</strong> ${cliente.nome_cliente}
                        </div>
                        <div class="mb-3">
                            <strong>CPF/CNPJ:</strong> ${cliente.cpf_cnpj}
                        </div>
                        <div class="mb-3">
                            <strong>Endereço:</strong> ${cliente.endereco || 'Não informado'}
                        </div>
                        <div class="mb-3">
                            <strong>Bairro:</strong> ${cliente.bairro || 'Não informado'}
                        </div>
                        <div class="mb-3">
                            <strong>CEP:</strong> ${cliente.cep || 'Não informado'}
                        </div>
                        <div class="mb-3">
                            <strong>Cidade:</strong> ${cliente.cidade || 'Não informada'}
                        </div>
                        <div class="mb-3">
                            <strong>Estado:</strong> ${cliente.estado || 'Não informado'}
                        </div>
                        <div class="mb-3">
                            <strong>Telefone:</strong> ${cliente.telefone || 'Não informado'}
                        </div>
                        <div class="mb-3">
                            <strong>E-mail:</strong> ${cliente.email || 'Não informado'}
                        </div>
                    `;

                    // Adiciona informações de inatividade se disponíveis
                    if (cliente.status === 'Inativo') {
                        let tempoInativo = '';
                        if (cliente.data_inatividade) {
                            const dataInatividade = new Date(cliente.data_inatividade);
                            const agora = new Date();
                            const diffTime = Math.abs(agora - dataInatividade);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            tempoInativo = `${diffDays} dia(s)`;
                        }

                        // Estilização boostrap e alerta caso n seja informado
                        detalhesHTML += `
                            <div class="mb-3">
                                <strong>Status:</strong> 
                                <span class="badge bg-danger">Inativo</span>
                            </div>
                            <div class="mb-3">
                                <strong>Data de Inativação:</strong> ${cliente.data_inatividade || 'Não informada'}
                            </div>
                            <div class="mb-3">
                                <strong>Tempo Inativo:</strong> ${tempoInativo || 'Não calculado'}
                            </div>
                            <div class="mb-3">
                                <strong>Observações:</strong> ${cliente.observacao_inatividade || 'Nenhuma observação'}
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

                    document.getElementById('detalhesCliente').innerHTML = detalhesHTML;
                    
                    // Abre o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
                    modal.show();
                })
                .catch(error => {
                    alert('Erro ao carregar dados do cliente');
                    console.error('Erro:', error);
                });
        }

        // Submeter formulário quando os filtros forem alterados
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Função para exportar estatísticas
        function exportarEstatisticas(formato) {
            const dados = {
                titulo: 'Relatório de Estatísticas de Clientes - ' + new Date().toLocaleDateString('pt-BR'),
                totalClientes: <?= count($clientes) ?>,
                ativos: <?= count(array_filter($clientes, fn($c) => $c['status'] === 'Ativo')) ?>,
                inativos: <?= count(array_filter($clientes, fn($c) => $c['status'] === 'Inativo')) ?>,
                cidades: {
                    <?php foreach($cidades as $cidade => $quantidade): ?>
                        '<?= addslashes($cidade) ?>': <?= $quantidade ?>,
                    <?php endforeach; ?>
                }
            };

            if (formato === 'pdf') {
                exportarPDF(dados);
            }
        }

        // Função para exportar PDF
        function exportarPDF(dados) {
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
                        <tr><th>Total de Clientes</th><td>${dados.totalClientes}</td></tr>
                        <tr><th>Clientes Ativos</th><td>${dados.ativos}</td></tr>
                        <tr><th>Clientes Inativos</th><td>${dados.inativos}</td></tr>
                    </table>
                    
                    <h2>Distribuição por Cidade</h2>
                    <table>
                        <tr><th>Cidade</th><th>Quantidade</th><th>Percentual</th></tr>
                        ${Object.entries(dados.cidades).map(([cidade, quantidade]) => `
                            <tr>
                                <td>${cidade}</td>
                                <td>${quantidade}</td>
                                <td>${dados.totalClientes > 0 ? ((quantidade / dados.totalClientes) * 100).toFixed(1) + '%' : '0%'}</td>
                            </tr>
                        `).join('')}
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

        // Máscaras para os campos
        function aplicarMascaras() {
            // Máscara para CPF/CNPJ
            $('#cpf_cnpj').mask('000.000.000-00', {reverse: true});
            
            // Máscara para telefone
            $('#telefone').mask('(00) 00000-0000');
            
            // Máscara para CEP
            $('#cep').mask('00000-000');
        }

        // Aplicar máscaras quando o modal for aberto
        document.getElementById('modalCliente').addEventListener('shown.bs.modal', aplicarMascaras);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>
</html>