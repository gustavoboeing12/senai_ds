<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica se o usuário tem permissão
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome_usuario = trim($_POST['nome_usuario']);
    $email = trim($_POST['email']);
    // Criptografa a senha
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    // Validações básicas
    if (empty($nome_usuario) || empty($email) || empty($_POST['senha']) || empty($id_perfil)) {
        echo "<script>alert('Preencha todos os campos obrigatórios!'); window.history.back();</script>";
        exit();
    }
    // Tratamento de erros
    try {
        // Verificar se email já existe
        $verificaEmail = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email");
        $verificaEmail->bindParam(':email', $email, PDO::PARAM_STR);
        $verificaEmail->execute();

        // Verifica se o email já está cadastrado
        if ($verificaEmail->fetchColumn() > 0) {
            echo "<script>alert('Erro: E-mail já cadastrado no sistema!'); window.history.back();</script>";
            exit();
        }
        // Cria a query de inserção
        $sql = "INSERT INTO usuario(nome_usuario, email, senha, id_perfil) VALUES (:nome_usuario, :email, :senha, :id_perfil)";
        // Prepara a query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_usuario', $nome_usuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':id_perfil', $id_perfil);

        // Executa a query
        if ($stmt->execute()) {
            // REGISTRAR LOG - APÓS INSERT BEM-SUCEDIDO
            $id_novo_usuario = $pdo->lastInsertId();
            
            // Descobrir o nome do perfil para incluir na ação
            $sql_perfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
            $stmt_perfil = $pdo->prepare($sql_perfil);
            $stmt_perfil->bindParam(':id_perfil', $id_perfil);
            $stmt_perfil->execute();
            $perfil = $stmt_perfil->fetch(PDO::FETCH_ASSOC);
            $nome_perfil = $perfil['nome_perfil'];
            
            // Incluir informações na ação
            $acao = "Cadastro de usuário: " . $nome_usuario . " (" . $email . ") como " . $nome_perfil;
            
            // Registrar o log
            if (function_exists('registrarLog')) {
                registrarLog($_SESSION['id_usuario'], $acao, "usuario", $id_novo_usuario);
            } else {
                error_log("Função registrarLog não encontrada!");
            }
            
            echo "<script>
                alert('Usuário cadastrado com sucesso!');
                window.location.href = 'cadastro_usuario.php';
            </script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário!'); window.history.back();</script>";
        }
    // Corrige possíveis erros dentro do 'try'
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Erro: E-mail já cadastrado no sistema!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            error_log("Erro PDO: " . $e->getMessage());
        }
    }
}

// Buscar perfis para o dropdown
$sql_perfis = "SELECT * FROM perfil";
$stmt_perfis = $pdo->query($sql_perfis);
$perfis = $stmt_perfis->fetchAll(PDO::FETCH_ASSOC);
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
                                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Cadastro de Usuario</h5>
                                </div>
                                <div class="card-body p-3">
                                    <form action="cadastro_usuario.php" method="POST">
                                        <!-- Nome -->
                                        <div class="mb-2">
                                            <label for="nome_usuario" class="form-label">Nome</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                <input type="text" class="form-control" id="nome_usuario" oninput="this.value=this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'')" name="nome_usuario" placeholder="Digite o nome completo" required>
                                            </div>
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
            
                                        <!-- Email -->
                                        <div class="mb-2">
                                            <label for="email" class="form-label">E-mail</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="funcionario@empresa.com" required>
                                            </div>
                                        </div>
            
                                        <!-- Senha -->
                                        <div class="mb-2">
                                            <label for="senha" class="form-label">Senha</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                <input type="password" class="form-control" id="senha" name="senha" minlength="8" placeholder="Crie uma senha" required>
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
                                    <small>Todos os campos são obrigatórios</small>
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
    </script>
</body>
</html>

