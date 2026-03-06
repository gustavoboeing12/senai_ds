<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de funcionário</title>
    <link rel="stylesheet" href="css/cad_func.css"/>
</head>
<body>
     <div class="container">
        <!-- Formuçário para cadastrar um funcionário -->
        <fieldset class="field">
        <h1>Cadastro</h1>
        <h2>Funcionários</h2>
        <form class="formu" action="salv_func.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" required/>
            <br>
            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" required/>
            <br>
            <label for="foto">Foto: </label>
            <input type="file" name="foto" id="foto" required/>
            <br>
            <button type="submit">Cadastrar</button>
        </form>
        </fieldset>
     </div>

     <address>
        <center>
            Gustavo Fratoni Boeing
        </center>
     </address>
</body>
</html>