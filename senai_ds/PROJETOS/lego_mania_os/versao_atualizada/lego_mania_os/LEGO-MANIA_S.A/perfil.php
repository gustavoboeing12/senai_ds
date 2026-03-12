<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// Busca os dados do usuário logado
$id_usuario = $_SESSION['id_usuario'];

try {
    // Faz uma QUERY para buscar as informações do usuario logado
    $sql = "SELECT u.*, p.nome_perfil 
            FROM usuario u 
            INNER JOIN perfil p ON u.id_perfil = p.id_perfil 
            WHERE u.id_usuario = :id_usuario";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='principal.php';</script>";
        exit();
    }
    
} catch (PDOException $e) {
    echo "<script>alert('Erro ao carregar dados do usuário: " . $e->getMessage() . "');</script>";
    $usuario = [];
}

// Processar formulário de edição se for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_perfil'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    try {
        // Faz a QUERY de atualização caso o usuario queira alterar o nome ou email
        $sql_update = "UPDATE usuario SET nome_usuario = :nome, email = :email WHERE id_usuario = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':id', $id_usuario);
        
        if ($stmt_update->execute()) {
            // Atualiza os dados na sessão
            $_SESSION['usuario'] = $nome;
            
            // Registra a ação no log
            registrarLog("Atualização de perfil", "usuario", $id_usuario);
            
            echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='perfil.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao atualizar perfil: " . $e->getMessage() . "');</script>";
    }
}

// Processar alteração de senha se for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Verificar se a senha atual está correta
    if (password_verify($senha_atual, $usuario['senha'])) {
        if ($nova_senha === $confirmar_senha) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            // Atualiza a senha do usuario no banco
            $sql_senha = "UPDATE usuario SET senha = :senha WHERE id_usuario = :id";
            $stmt_senha = $pdo->prepare($sql_senha);
            $stmt_senha->bindParam(':senha', $nova_senha_hash);
            $stmt_senha->bindParam(':id', $id_usuario);
            
            if ($stmt_senha->execute()) {
                registrarLog("Alteração de senha", "usuario", $id_usuario);
                echo "<script>alert('Senha alterada com sucesso!');</script>";
            }
        } else {
            echo "<script>alert('As senhas não coincidem!');</script>";
        }
    } else {
        echo "<script>alert('Senha atual incorreta!');</script>";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="js/ExportaRelatorios.js"></script>
    <style>
        body{
            overflow-y: hidden !important;
        }
    </style>
    <title>Home - Lego mania</title>
</head>
<body>
<div class="d-flex vh-100 bg-light">
  
  <!-- Sidebar -->
  <?php exibirMenu(); ?>

  <!-- Conteúdo principal -->
  <div class="flex-grow-1">
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

    <div class="flex-grow-1 p-3" style="overflow-y: auto;">
      <div class="container-fluid">
          <!-- Cabeçalho -->
        
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Perfil do Usuário</h5> 
                <div>
                    <button class="btn btn-outline-secondary btn-sm me-2" onclick="ExportaPerfilPDF()">
                        <i class="bi bi-download me-1"></i> Exportar
                    </button>
                </div>
            </div>

          <!-- Conteúdo Principal -->
        <div class="flex-grow-1 p-3" style="overflow-y: auto;">
            <div class="container-fluid">

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Informações do Perfil</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="perfil.php">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="nome" class="form-label">Nome de Usuário</label>
                                            <input type="text" class="form-control" id="nome" name="nome" 
                                                   value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>" 
                                                   <?php echo (!isset($_POST['editar'])) ? 'readonly' : ''; ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                                                   <?php echo (!isset($_POST['editar'])) ? 'readonly' : ''; ?>>
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="perfil" class="form-label">Perfil</label>
                                            <input type="text" class="form-control" id="perfil" 
                                                   value="<?php echo htmlspecialchars($usuario['nome_perfil']); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <input type="text" class="form-control" id="status" 
                                                   value="<?php echo ($usuario['senha_temporaria'] == 1) ? 'Senha Temporária' : 'Ativo'; ?>" readonly>
                                        </div>
                                    </div>
                        
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="data_cadastro" class="form-label">Data de Cadastro</label>
                                            <input type="text" class="form-control" id="data_cadastro" 
                                                   value="<?php echo date('d/m/Y', strtotime($usuario['dt_cadastro'] ?? date('Y-m-d'))); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="data_cadastro" class="form-label">Senha</label>
                                            <input type="text" class="form-control" id="data_cadastro" 
                                                   value="**********" readonly>
                                        </div> 
                                    </div>

                                    <?php if (!isset($_POST['editar'])): ?>
                                        <div class="d-flex gap-2 mt-4">
                                            <button type="submit" name="editar" class="btn btn-dark">
                                                <i class="bi bi-pencil me-1"></i> Editar Perfil
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalSenha">
                                                <i class="bi bi-key me-1"></i> Alterar Senha
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex gap-2 mt-4">
                                            <button type="submit" name="editar_perfil" class="btn btn-success">
                                                <i class="bi bi-check-circle me-1"></i> Salvar Alterações
                                            </button>
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Senha -->
    <div class="modal fade" id="modalSenha" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="perfil.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                        </div>
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="alterar_senha" class="btn btn-primary">Alterar Senha</button>
                    </div>
                </form>
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
    updateClock();

    // Alternar visibilidade da senha no modal
    document.getElementById('toggleSenha').addEventListener('click', function() {
        const senhaInput = document.getElementById('senha');
        const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        senhaInput.setAttribute('type', tipo);
        this.innerHTML = tipo === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    // Para a senha atual no modal
    document.getElementById('toggleSenhaAtual').addEventListener('click', function() {
        const senhaInput = document.getElementById('senha_atual');
        const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        senhaInput.setAttribute('type', tipo);
        this.innerHTML = tipo === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    // Para a nova senha no modal
    document.getElementById('toggleNovaSenha').addEventListener('click', function() {
        const senhaInput = document.getElementById('nova_senha');
        const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        senhaInput.setAttribute('type', tipo);
        this.innerHTML = tipo === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    // Para confirmar senha no modal
    document.getElementById('toggleConfirmarSenha').addEventListener('click', function() {
        const senhaInput = document.getElementById('confirmar_senha');
        const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        senhaInput.setAttribute('type', tipo);
        this.innerHTML = tipo === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
</script>

<

<!-- <script>
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

</script> -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>

