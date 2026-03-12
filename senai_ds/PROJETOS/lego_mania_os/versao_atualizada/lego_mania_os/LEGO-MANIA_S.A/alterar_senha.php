<?php 
    session_start();
    require_once 'conexao.php';

    // Verifica que o usuário esteja logado
    if(!isset($_SESSION['id_usuario'])) {
        echo "<script>alert('Acesso Negado!'); window.location.href='index.php';</script>";
        exit();
    }
 
    // Verifica se o formulário foi enviado
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Coleta de dados
        $id_usuario = $_SESSION['id_usuario'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Verifica se as senhas são iguais(nova e confirmação)
        if($nova_senha !== $confirmar_senha) {
            echo "<script>alert('As senhas não coincidem!');</script>";
        }
        // Se a senha não conter pelo menos 8 caracteres, retorna erro
        elseif(strlen($nova_senha) < 8) {
            echo "<script>alert('A senha deve ter no mínimo 8 caracteres!');</script>";
        }
        // Se a senha for igual á senha temporária, retorna erro
        elseif($nova_senha === "temp123") {
            echo "<script>alert('Escolha uma senha diferente de temporaria!');</script>";
        }
        else {
            // Criptograda a senha
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            // Atualiza a senha e remove o status de temporária
            $sql = "UPDATE usuario SET senha = :senha,senha_temporaria = FALSE WHERE id_usuario = :id";
            // Prepara a query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':senha',$senha_hash);
            $stmt->bindParam(':id',$id_usuario);

            // Executa a query preparada e retorna mensagens
            if($stmt->execute()) {
                session_destroy(); // Finaliza a sessão
                echo "<script>alert('Senha alterada com sucesso! Faça login novamente');window.location.href='index.php';</script>";
            }
            // Se der algum erro na query
            else {
                echo "<script>alert('Erro ao alterar a senha!');</script>";
            }
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
    <title>Recuperar - Lego mania</title>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="text-dark"><i class="bi bi-lock-fill"></i></h3>
            <h4 class="mb-0">Alterar Senha</h4>
            <small class="text-muted">Digite sua nova senha!</small>
        </div>

        <form action="alterar_senha.php" method="POST">
            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" placeholder="Nova Senha" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar Senha" required>
            </div>

            <label>
                <!-- Chama a função de exibir senha-->
                <input type="checkbox" onclick="mostrarSenha()"> Mostrar Senha
            </label><br><br>

            <button type="submit" class="btn btn-dark w-100">Salvar nova senha</button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">&larr; Voltar para o Login</a>
        </div>
    </div>
</div>

<script>
    function mostrarSenha() {
        var senha1 = document.getElementById("nova_senha");
        var senha2 = document.getElementById("confirmar_senha");
        var tipo = senha1.type === "password" ? "text": "password";
        senha1.type = tipo;
        senha2.type = tipo;
    }
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>