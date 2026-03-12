<?php
    session_start();
    require_once 'conexao.php'; // Arquivo que faz a conexão com o BD
    require_once 'funcao_email.php'; // Arquivo com as funções que geram a senha e simulam o envio

    // Verifica se metodo é igual a POST e executa a condição.
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email=$_POST['email'];

        // Verifica se o email existe no banco de dados
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 
        if($usuario) {
            // Gera uma senha temporária e aleatória
            $senha_temporaria = gerarSenhaTemporaria();
            $senha_hash = password_hash($senha_temporaria,PASSWORD_DEFAULT);

            // Atualiza a senha do usuário no banco
            $sql = "UPDATE usuario SET senha = :senha,senha_temporaria = TRUE WHERE email = :email";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':senha',$senha_hash);
            $stmt->bindParam(':email',$email);
            $stmt->execute();

            // Simula o envio do email (grava em TXT)
            simularEnvioEmail($email,$senha_temporaria);
            echo "<script> alert('Uma senha temporária foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt');
                  window.location.href='index.php'; </script>";
        }
        else {
            echo "<script>alert('Email não encontrado!');</script>";
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
            <h4 class="mb-0">Recuperar Senha</h4>
            <small class="text-muted">Digite seu e-mail para redefinir a senha</small>
        </div>

        <form action="esqueceu_senha.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Enviar Link de Recuperação</button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">&larr; Voltar para o Login</a>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
