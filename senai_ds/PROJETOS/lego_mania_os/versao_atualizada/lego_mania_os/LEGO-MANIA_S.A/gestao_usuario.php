<?php
    session_start();
    require_once 'conexao.php';
    require_once 'php/permissoes.php';

    // VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM OU SECRETARIA
    if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=3){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    } 

    // INICIALIZA VARIÁVEIS
    $filtro_perfil = '';
    $filtro_status = '';
    $busca = '';
    $usuarios = [];

    // VERIFICA SE HÁ FILTRO POR PERFIL (GET)
    if(isset($_GET['id_perfil']) && !empty($_GET['id_perfil'])) {
        $filtro_perfil = $_GET['id_perfil'];
    }

    // VERIFICA SE HÁ FILTRO POR STATUS (GET)
    if(isset($_GET['status']) && !empty($_GET['status'])) {
        $filtro_status = $_GET['status'];
    }

    // VERIFICA SE HÁ BUSCA POR TEXTO (GET)
    if(isset($_GET['busca']) && !empty($_GET['busca'])){
        $busca = trim($_GET['busca']);
    }

    // CONSTRUIR A QUERY PARA BUSCAR INFORMAÇÕE DO USUARIO, PERFIL E MOTIVO DA INATIVIDADE
    $sql = "SELECT u.*, p.nome_perfil, m.descricao as motivo_inatividade 
            FROM usuario u 
            LEFT JOIN perfil p ON u.id_perfil = p.id_perfil 
            LEFT JOIN motivo_inatividade m ON u.id_motivo_inatividade = m.id_motivo";
    $where_conditions = [];
    $params = [];

    // ADICIONAR FILTRO POR PERFIL SE EXISTIR
    if(!empty($filtro_perfil)) {
        $where_conditions[] = "u.id_perfil = :id_perfil";
        $params[':id_perfil'] = $filtro_perfil;
    }

    // ADICIONAR FILTRO POR STATUS SE EXISTIR
    if(!empty($filtro_status)) {
        $where_conditions[] = "u.status = :status";
        $params[':status'] = $filtro_status;
    }

    // ADICIONAR FILTRO POR BUSCA SE EXISTIR
    if(!empty($busca)) {
        if(is_numeric($busca)){
            $where_conditions[] = "u.id_usuario = :busca";
            $params[':busca'] = $busca;
        } else {
            $where_conditions[] = "u.nome_usuario LIKE :busca_nome";
            $params[':busca_nome'] = "$busca%";
        }
    }

    // COMBINAR CONDITIONS SE HOUVER
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY u.nome_usuario ASC";

    // PREPARAR E EXECUTAR A QUERY
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    // INATIVAR USUARIO //

    // Inativar Usuario em vez de excluir
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_usuario = $_GET['id'];
        
        // Verificar se está inativando ou reativando
        if(isset($_GET['acao']) && $_GET['acao'] == 'reativar') {
            // Reativar usuário
            $sql = "UPDATE usuario SET status = 'Ativo', id_motivo_inatividade = NULL, data_inatividade = NULL, observacao_inatividade = NULL WHERE id_usuario = :id";
            $mensagem = 'Usuário reativado com sucesso!';
        } else {
            // Inativar usuário - redireciona para página de inativação
            header("Location: inativar_usuario.php?id=" . $id_usuario);
            exit();
        }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('$mensagem');window.location.href='gestao_usuario.php';</script>";
    } else{
        echo "<script>alert('Erro ao alterar status do usuário!');</script>";
    }
}

$stmt->execute();
$usuarios = $stmt->fetchALL(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/login.css">
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
                <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Gestão de Usuários</h5>
                <div>
                    <!-- Botão de Estatísticas -->
                    <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalEstatisticas">
                        <i class="bi bi-graph-up me-1"></i> Estatísticas
                    </button>
 
                    <a href="cadastro_usuario.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Novo Usuário
                    </a>
                </div>
            </div>
                    
                    <!-- Barra de pesquisa e filtros -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body py-2">
                            <form method="GET" action="gestao_usuario.php" id="filterForm">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" id="busca" name="busca" class="form-control" 
                                                placeholder="Pesquisar por ID ou nome..." 
                                                value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                                            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="id_perfil" id="id_perfil" class="form-select form-select-sm">
                                            <option value="">Todos os cargos</option>
                                            <option value="1" <?= (isset($_GET['id_perfil']) && $_GET['id_perfil'] == '1') ? 'selected' : '' ?>>Administrador</option>
                                            <option value="2" <?= (isset($_GET['id_perfil']) && $_GET['id_perfil'] == '2') ? 'selected' : '' ?>>Funcionário</option>
                                            <option value="3" <?= (isset($_GET['id_perfil']) && $_GET['id_perfil'] == '3') ? 'selected' : '' ?>>Secretaria</option>
                                            <option value="4" <?= (isset($_GET['id_perfil']) && $_GET['id_perfil'] == '4') ? 'selected' : '' ?>>Técnico</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" id="status" class="form-select form-select-sm">
                                            <option value="">Todos os status</option>
                                            <option value="Ativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
                                            <option value="Inativo" <?= (isset($_GET['status']) && $_GET['status'] == 'Inativo') ? 'selected' : '' ?>>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <?php if(isset($_GET['busca']) || isset($_GET['id_perfil']) || isset($_GET['status'])): ?>
                                            <a href="gestao_usuario.php" class="btn btn-outline-danger btn-sm">Limpar Filtros</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabela de Usuários -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <?php if(!empty($usuarios)): ?>
                                    <table class="table table-striped table-hover table-bordered mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th><center>ID</center></th>
                                                <th><center>Nome Usuário</center></th>
                                                <th><center>E-mail</center></th>
                                                <th><center>Perfil</center></th>
                                                <th><center>Status</center></th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($usuarios as $usuario): ?>
                                                <tr>
                                                    <td><center><?=htmlspecialchars($usuario['id_usuario'])?></center></td>
                                                    <td><center><?=htmlspecialchars($usuario['nome_usuario'])?></center></td>
                                                    <td><center><?=htmlspecialchars($usuario['email'])?></center></td>
                                                    <td><center><?=htmlspecialchars($usuario['nome_perfil'])?></center></td>
                                                    <td>
                                                        <center>
                                                            <?php if($usuario['status'] == 'Ativo'): ?>
                                                                <span class="badge bg-success">Ativo</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger badge-details" onclick="mostrarDetalhesUsuario(<?=htmlspecialchars($usuario['id_usuario'])?>)">
                                                                    Inativo <i class="bi bi-info-circle"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </center> 
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="btn btn-sm btn-primary me-1" onclick="carregarDadosUsuario(<?=htmlspecialchars($usuario['id_usuario'])?>)">Alterar</a>
                                                        
                                                        <?php if($usuario['status'] == 'Ativo'): ?>
                                                            <a href="inativar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" class="btn btn-sm btn-danger me-1">Inativar</a>
                                                        <?php else: ?>
                                                            <a href="gestao_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>&acao=reativar" class="btn btn-sm btn-success me-1" onclick="return confirm('Tem certeza que deseja reativar este usuário?')">Reativar</a>
                                                        <?php endif; ?>
                                                        
                                                        <button class="btn btn-sm btn-info" onclick="mostrarDetalhesUsuario(<?=htmlspecialchars($usuario['id_usuario'])?>)">
                                                            <i class="bi bi-info-circle"></i> Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>

                                <?php else: ?><br>
                                    <center><p>Nenhum usuário encontrado.</p></center>
                                <?php endif; ?>
  
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Mostrando <?= count($usuarios) ?> de <?= count($usuarios) ?> usuários</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Alterar Usuário -->
            <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Usuário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="php/alterar_usuario.php">
                            <div class="modal-body">
                                <input type="hidden" id="id_usuario" name="id_usuario">
                                
                                <div class="mb-3">
                                    <label for="nome_usuario" class="form-label">Nome do Usuário</label>
                                    <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <!-- Perfil -->
                                <div class="mb-2">
                                        <label for="id_perfil" class="form-label">Perfil</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-person-rolodex"></i></span>
                                            <select class="form-select" id="id_perfil" name="id_perfil" required>
                                                <option value="" selected disabled>Selecione o perfil</option>
                                                <option value="1" id="adminoption">Administrador</option>
                                                <option value="2" id="funcoption">Funcionario</option>
                                                <option value="3" id="secretariaoption">Secretaria</option>
                                                <option value="4" id="tecoption">Técnico</option>
                                            </select>
                                        </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="senha" name="senha">
                                        <button type="button" class="btn btn-outline-secondary" id="toggleSenha">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="alterar_usuario" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para Detalhes do Usuário -->
            <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalhes do Usuário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="detalhesUsuario">
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
                            <h5 class="modal-title">Estatísticas de Usuários</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Distribuição por Perfil</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm" id="tabelaPerfis">
                                                    <thead>
                                                        <tr>
                                                            <th>Perfil</th>
                                                            <th class="text-end">Quantidade</th>
                                                            <th class="text-end">Percentual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Calcular estatísticas por perfil
                                                        $perfis = [
                                                            'Administrador' => 0,
                                                            'Secretaria' => 0,
                                                            'Funcionario' => 0,
                                                            'Tecnico' => 0
                                                        ];
                                                        
                                                        foreach($usuarios as $usuario) {
                                                            if(isset($perfis[$usuario['nome_perfil']])) {
                                                                $perfis[$usuario['nome_perfil']]++;
                                                            }
                                                        }
                                                        
                                                        $totalUsuarios = count($usuarios);
                                                        foreach($perfis as $perfil => $quantidade):
                                                            if($quantidade > 0):
                                                                $percentual = $totalUsuarios > 0 ? round(($quantidade / $totalUsuarios) * 100, 1) : 0;
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($perfil) ?></td>
                                                            <td class="text-end"><?= $quantidade ?></td>
                                                            <td class="text-end"><?= $percentual ?>%</td>
                                                        </tr>
                                                        <?php endif; endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Status dos Usuários</h6>
                                            <div class="d-flex justify-content-around text-center">
                                                <div>
                                                    <div class="fs-2 text-success"><?= count(array_filter($usuarios, fn($u) => $u['status'] === 'Ativo')) ?></div>
                                                    <div class="text-muted">Ativos</div>
                                                </div>
                                                <div>
                                                    <div class="fs-2 text-danger"><?= count(array_filter($usuarios, fn($u) => $u['status'] === 'Inativo')) ?></div>
                                                    <div class="text-muted">Inativos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Resumo Geral</h6>
                                            <div class="mb-2">Total de Usuários: <strong><?= count($usuarios) ?></strong></div>
                                            <div class="mb-2">Administradores: <strong><?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Administrador')) ?></strong></div>
                                            <div class="mb-2">Secretarias: <strong><?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Secretaria')) ?></strong></div>
                                            <div class="mb-2">Funcionários: <strong><?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Funcionario')) ?></strong></div>
                                            <div>Técnicos: <strong><?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Tecnico')) ?></strong></div>
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

        // Função para carregar dados do usuário no modal
        function carregarDadosUsuario(id) {
            fetch('buscar_usuario.php?id=' + id)
                .then(response => response.json())
                .then(usuario => {
                    document.getElementById('id_usuario').value = usuario.id_usuario;
                    document.getElementById('nome_usuario').value = usuario.nome_usuario;
                    document.getElementById('email').value = usuario.email;
                    
                    // Abre o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
                    modal.show();
                })
                .catch(error => console.error('Erro:', error));
        }

        // Função para mostrar detalhes do usuário
        function mostrarDetalhesUsuario(id) {
            // Busca os dados básicos do usuário (que já funcionam)
            fetch('buscar_usuario.php?id=' + id)
                .then(response => response.json())
                .then(usuarioBasico => {
                    // Agora busca os dados completos com informações de inatividade
                    fetch('buscar_dados_completos_usuario.php?id=' + id)
                        .then(response => response.json())
                        .then(usuarioCompleto => {
                            let detalhesHTML = `
                                <div class="mb-3">
                                    <strong>ID:</strong> ${usuarioBasico.id_usuario}
                                </div>
                                <div class="mb-3">
                                    <strong>Nome:</strong> ${usuarioBasico.nome_usuario}
                                </div>
                                <div class="mb-3">
                                    <strong>E-mail:</strong> ${usuarioBasico.email}
                                </div>
                            `;

                            // Adiciona informações de inatividade se disponíveis
                            if (usuarioCompleto.status === 'Inativo') {
                                let tempoInativo = '';
                                if (usuarioCompleto.data_inatividade) {
                                    const dataInatividade = new Date(usuarioCompleto.data_inatividade);
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
                                        <strong>Data de Inativação:</strong> ${usuarioCompleto.data_inatividade || 'Não informada'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Tempo Inativo:</strong> ${tempoInativo || 'Não calculado'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Motivo da Inativação:</strong> ${usuarioCompleto.motivo_inatividade || 'Não informado'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Observações:</strong> ${usuarioCompleto.observacao_inatividade || 'Nenhuma observação'}
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

                            document.getElementById('detalhesUsuario').innerHTML = detalhesHTML;
                            
                            // Abre o modal
                            var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
                            modal.show();
                        })
                        .catch(error => {
                            // Se falhar a segunda requisição, mostra apenas os dados básicos
                            let detalhesHTML = `
                                <div class="mb-3">
                                    <strong>ID:</strong> ${usuarioBasico.id_usuario}
                                </div>
                                <div class="mb-3">
                                    <strong>Nome:</strong> ${usuarioBasico.nome_usuario}
                                </div>
                                <div class="mb-3">
                                    <strong>E-mail:</strong> ${usuarioBasico.email}
                                </div>
                                <div class="mb-3 text-warning">
                                    <em>Informações de status não disponíveis</em>
                                </div>
                            `;
                            document.getElementById('detalhesUsuario').innerHTML = detalhesHTML;
                            
                            var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
                            modal.show();
                        });
                })
                .catch(error => {
                    alert('Erro ao carregar dados do usuário');
                    console.error('Erro:', error);
                });
        }

        // Alternar visibilidade da senha
        document.getElementById('toggleSenha').addEventListener('click', function() {
            const senhaInput = document.getElementById('senha');
            const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            senhaInput.setAttribute('type', tipo);
            this.innerHTML = tipo === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });

        // Submeter formulário quando os filtros forem alterados
        document.getElementById('id_perfil').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
        
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });


        // Função para exportar estatísticas
        function exportarEstatisticas(formato) {
            const dados = {
                titulo: 'Relatório de Estatísticas de Usuários - ' + new Date().toLocaleDateString('pt-BR'),
                totalUsuarios: <?= count($usuarios) ?>,
                ativos: <?= count(array_filter($usuarios, fn($u) => $u['status'] === 'Ativo')) ?>,
                inativos: <?= count(array_filter($usuarios, fn($u) => $u['status'] === 'Inativo')) ?>,
                perfis: {
                    'Administrador': <?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Administrador')) ?>,
                    'Secretaria': <?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Secretaria')) ?>,
                    'Funcionario': <?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Funcionario')) ?>,
                    'Tecnico': <?= count(array_filter($usuarios, fn($u) => $u['nome_perfil'] === 'Tecnico')) ?>
                }
            };

            if (formato === 'pdf') {
                exportarPDF(dados);
            } else if (formato === 'excel') {
                exportarExcel(dados);
            }
        }

        // Função para exportar PDF
        function exportarPDF(dados) {
            // Aqui você pode integrar com uma biblioteca como jsPDF
            // Esta é uma implementação básica que abre uma nova janela para impressão
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
                        <tr><th>Total de Usuários</th><td>${dados.totalUsuarios}</td></tr>
                        <tr><th>Usuários Ativos</th><td>${dados.ativos}</td></tr>
                        <tr><th>Usuários Inativos</th><td>${dados.inativos}</td></tr>
                    </table>
                    
                    <h2>Distribuição por Perfil</h2>
                    <table>
                        <tr><th>Perfil</th><th>Quantidade</th><th>Percentual</th></tr>
                        ${Object.entries(dados.perfis).map(([perfil, quantidade]) => `
                            <tr>
                                <td>${perfil}</td>
                                <td>${quantidade}</td>
                                <td>${dados.totalUsuarios > 0 ? ((quantidade / dados.totalUsuarios) * 100).toFixed(1) + '%' : '0%'}</td>
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
    </script>


</body>
</html>