<?php 
    session_start();
    require_once 'conexao.php';

    // Verifica se o formulario foi enviado e se o metodo é igual a POST
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Faz uma Busca na tabela usuario no banco de dados
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);  
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario) {
            // Verificar se o usuário está inativo
            if($usuario['status'] == 'Inativo') {
                $showInactiveModal = true;
            } else if(password_verify($senha, $usuario['senha'])) {
                // Login bem sucedido define variáveis de sessão
                $_SESSION['usuario'] = $usuario['nome_usuario'];
                $_SESSION['perfil'] = $usuario['id_perfil'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];

                // Verifica se a senha é temporária
                if($usuario['senha_temporaria']) {
                    header("Location: alterar_senha.php");
                    exit();
                } else {
                    header("Location: principal.php");
                    exit();
                }              
            } else {
                $showErrorModal = true;
                $errorMessage = 'E-mail ou senha incorretos';
            }
        } else {
            $showErrorModal = true;
            $errorMessage = 'E-mail ou senha incorretos';
        }
    }
?> 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="login.css">
    <title>Login - Lego mania</title>
    <style>
        body{
            overflow-y: hidden;
        }
    </style>
</head>
<body>
    
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="text-dark"><i class="bi bi-person-circle"></i></h3>
            <h4 class="mb-0">Login</h4>
            <small class="text-muted">Acesse sua conta</small>
        </div>

        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="esqueceu_senha.php" class="text-decoration-none">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn btn-dark w-100" id="entrar_button">Entrar</button><br><br><br>
        </form>
    </div>
</div>

<!-- Modal para usuário inativo -->
<div class="modal fade" id="inactiveUserModal" tabindex="-1" aria-labelledby="inactiveUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="inactiveUserModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Usuário Inativo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-person-x-fill text-warning" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Conta Desativada</h4>
                <p class="mt-3">Sua conta está atualmente inativa. Entre em contato com os responsáveis para reativar seu acesso.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Entendi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para erro de login -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel"><i class="bi bi-exclamation-circle-fill"></i> Erro no Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Falha na Autenticação</h4>
                <p class="mt-3" id="errorMessage"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tentar Novamente</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<script>
    <?php if(isset($showInactiveModal) && $showInactiveModal): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var inactiveModal = new bootstrap.Modal(document.getElementById('inactiveUserModal'));
        inactiveModal.show();
    });
    <?php endif; ?>

    <?php if(isset($showErrorModal) && $showErrorModal): ?>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('errorMessage').textContent = '<?php echo $errorMessage; ?>';
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    });
    <?php endif; ?>
</script>
</body>
</html>