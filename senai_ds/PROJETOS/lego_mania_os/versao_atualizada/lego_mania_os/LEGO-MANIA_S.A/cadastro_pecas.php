<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso Negado! Faça login primeiro.'); window.location.href='index.php';</script>";
    exit();
}

// Buscar fornecedores ativos
$fornecedores = [];
try {
    $stmt = $pdo->prepare("SELECT id_fornecedor, nome_fornecedor FROM fornecedor WHERE status = 'Ativo' ORDER BY nome_fornecedor");
    $stmt->execute();
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar fornecedores: " . $e->getMessage());
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Recebe e sanitiza os dados do formulário
    $nome_peca = trim($_POST['nome_peca']);
    $descricao_peca = trim($_POST['descricao_peca']);
    $data_cadastro = $_POST['data_cadastro'];
    $quantidade = (int)$_POST['quantidade'];
    $quantidade_minima = isset($_POST['quantidade_minima']) ? (int)$_POST['quantidade_minima'] : 0;
    $tipo = trim($_POST['tipo']);
    $id_fornecedor = (int)$_POST['id_fornecedor'];
    
    // Processar o preço - converter de formato brasileiro para decimal
    $preco = trim($_POST['preco']);
    $preco = str_replace(['R$', '.', ','], ['', '', '.'], $preco);
    $preco = floatval($preco);
    
    // ID do usuário que está cadastrando (para log)
    $id_usuario_cadastrante = $_SESSION['id_usuario'];

    // Validar CPF (11 dígitos) ou CNPJ (14 dígitos)
    if ($preco <= 0) {
        echo "<script>alert('Preço deve ser maior ou igual a zero.');window.history.back();</script>";
        exit();
    }

    // Validações básicas
    if (empty($nome_peca) || empty($quantidade) || empty($quantidade_minima) || empty($id_fornecedor) || empty($preco)) {
        echo "<script>alert('Preencha todos os campos obrigatórios!');</script>";
        exit();
    }

    try {
        // Verificar se fornecedor existe e está ativo
        $verificaFornecedor = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE id_fornecedor = :id_fornecedor AND status = 'Ativo'");
        $verificaFornecedor->bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
        $verificaFornecedor->execute();

        if ($verificaFornecedor->fetchColumn() == 0) {
            echo "<script>alert('Erro: Fornecedor selecionado não é válido!');</script>";
            exit();
        }

        // Prepara a query SQL para inserir na tabela peca_estoque
        $sql = "INSERT INTO peca_estoque (id_funcionario, id_fornecedor, nome_peca, descricao_peca, qtde, qtde_minima, tipo, preco, dt_cadastro) 
                VALUES (:id_funcionario, :id_fornecedor, :nome_peca, :descricao_peca, :qtde, :qtde_minima, :tipo, :preco, :dt_cadastro)";
        
        // Prepara a query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_funcionario', $id_usuario_cadastrante, PDO::PARAM_INT);
        $stmt->bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':nome_peca', $nome_peca, PDO::PARAM_STR);
        $stmt->bindParam(':descricao_peca', $descricao_peca, PDO::PARAM_STR);
        $stmt->bindParam(':qtde', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':qtde_minima', $quantidade_minima, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':dt_cadastro', $data_cadastro, PDO::PARAM_STR);

        // Executa a query e registra no log
        if ($stmt->execute()) {
            // REGISTRAR LOG - APÓS INSERT BEM-SUCEDIDO
            $id_nova_peca = $pdo->lastInsertId();
            
            // Formatar preço para exibição
            $preco_formatado = 'R$ ' . number_format($preco, 2, ',', '.');
            
            // Incluir informações na ação
            $acao = "Cadastro de peça: " . $nome_peca . " (Quantidade: " . $quantidade . ", Preço: " . $preco_formatado . ")";
            
            // Registrar o log
            if (function_exists('registrarLog')) {
                registrarLog($id_usuario_cadastrante, $acao, "peca_estoque", $id_nova_peca);
            } else {
                error_log("Função registrarLog não encontrada! Ação: " . $acao);
            }
            
            echo "<script>
                alert('Peça cadastrada com sucesso!');
                window.location.href = 'cadastro_pecas.php';
            </script>";
        } else {
            echo "<script>alert('Erro ao cadastrar peça!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar peça: " . addslashes($e->getMessage()) . "');</script>";
        error_log("Erro PDO: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro - Lego Mania</title>
    <script src="js/validacoes_form.js"></script>
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
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Cadastro de Peças no Estoque</h5>
                                </div>
                                <div class="card-body p-3">
                                    <form action="cadastro_pecas.php" method="POST">
                                        <!-- Nome da Peça -->
                                        <div class="mb-2">
                                            <label for="nome_peca" class="form-label">Nome da Peça *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-puzzle"></i></span>
                                                <input type="text" class="form-control" id="nome_peca" name="nome_peca" placeholder="Digite o nome da peça" required>
                                            </div>
                                        </div>
            
                                        <!-- Descrição da Peça -->
                                        <div class="mb-2">
                                            <label for="descricao_peca" class="form-label">Descrição da Peça</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                                <textarea class="form-control" id="descricao_peca" name="descricao_peca" placeholder="Descreva a peça (opcional)" rows="2"></textarea>
                                            </div>
                                        </div>
            
                                        <!-- Data de Cadastro -->
                                        <div class="mb-2">
                                            <label for="data_cadastro" class="form-label">Data de Cadastro *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-calendar-plus"></i></span>
                                                <input type="date" class="form-control" id="data_cadastro" name="data_cadastro" required>
                                            </div>
                                        </div>
            
                                        <!-- Quantidade -->
                                        <div class="mb-2">
                                            <label for="quantidade" class="form-label">Quantidade *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                                <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="0" min="0" required>
                                            </div>
                                        </div>

                                        <!-- Quantidade Mínima -->
                                        <div class="mb-2">
                                            <label for="quantidade_minima" class="form-label">Quantidade Mínima *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                                <input type="number" class="form-control" id="quantidade_minima" name="quantidade_minima" placeholder="0" min="0" required>
                                            </div>
                                        </div>
            
                                        <!-- Tipo -->
                                        <div class="mb-2">
                                            <label for="tipo" class="form-label">Tipo *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                <select class="form-select" id="tipo" name="tipo" required>
                                                    <option value="" selected disabled>Selecione o tipo</option>
                                                    <option value="hardware">Hardware</option>
                                                    <option value="perifericos">Periféricos</option>
                                                    <option value="cabos">Cabos</option>
                                                    <option value="outros">Outros</option>
                                                </select>
                                            </div>
                                        </div>

                                           <!-- Preço total -->
                                           <div class="mb-2">
                                            <label for="preco" class="form-label">Preço *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                                <input type="text" class="form-control" id="preco" name="preco" placeholder="R$ 49,99" maxlength="14" min="0" oninput="mascaraPreco()" required>
                                            </div>
                                        </div>

                                        <!-- Fornecedor -->
                                        <div class="mb-3">
                                            <label for="id_fornecedor" class="form-label">Fornecedor *</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                <select class="form-select select2-fornecedor" id="id_fornecedor" name="id_fornecedor" required>
                                                    <option value="" selected disabled>Selecione um fornecedor</option>
                                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                                        <option value="<?= $fornecedor['id_fornecedor'] ?>"><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-text">
                                                <a href="cadastro_fornecedor.php" class="text-decoration-none">Cadastrar novo fornecedor</a>
                                            </div>
                                        </div>
            
                                        <!-- Botões -->
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="reset" class="btn btn-outline-secondary btn-sm me-md-2">
                                                <i class="bi bi-x-circle"></i> Limpar
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm" id="botaocadastro">
                                                <i class="bi bi-check-circle"></i> Cadastrar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-muted text-center py-2">
                                    <small>Campos marcados com * são obrigatórios</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>
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

        // Inicializar Select2 para fornecedores
        $(document).ready(function() {
            $('.select2-fornecedor').select2({
                language: "pt-BR",
                placeholder: "",
                allowClear: false,
                width: '100%'
            });
            
            // Definir data atual como padrão
            document.getElementById('data_cadastro').valueAsDate = new Date();
            
            // Validação do formulário
            $('form').on('submit', function(e) {
                let isValid = true;
                $('input[required], select[required]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });
        });
</script>
</body>
</html>
