<!-- Tela de recuperação de senha -->
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
      <h3 class="text-dark"><i class="bi bi-shield-lock-fill"></i></h3>
      <h4 class="mb-0">Verificação de Código</h4>
      <small class="text-muted">Digite o código enviado para seu e-mail</small>
    </div>

    <form>
      <div class="mb-3">
        <label for="codigo" class="form-label">Código</label>
        <input type="text" class="form-control text-center fw-bold" id="codigo" placeholder="Ex: 123456" required>
      </div>

      <button type="submit" class="btn btn-dark w-100">Verificar Código</button>
    </form>

    <div class="text-center mt-3">
      <a href="esqueceu_senha.php" class="text-decoration-none">&larr; Voltar</a>
    </div>
  </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>




