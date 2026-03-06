<?php
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'bd_imagens';
$username = 'root';
$password = '';

    try {
        // Conexão com o banco via PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Define que erros vão lançar exceções
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recupera todos os funcionários do banco de dados
        $sql = "SELECT id,nome FROM funcionarios";
        // Preapara a instrução sql para execução
        $stmt = $pdo -> prepare($sql);
        // Executa a instrução mysql
        $stmt -> execute();
        // Busca todos os resultados como uma matriz associativa
        $funcionarios = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Verifica se foi solicitado a exclusão de um funcionário
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir_id'])){
            $excluir_id = $_POST['excluir_id'];
            $sql_excluir = "DELETE FROM funcionarios WHERE id = :id";
            $stmt_excluir = $pdo -> prepare($sql_excluir);
            $stmt_excluir -> bindParam(':id',$excluir_id,PDO::PARAM_INT);
            $stmt_excluir -> execute();

            // Redireciona para evitar reenvio do formulário
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    } catch(PDOException $e){
        // Exibe a mensagem de erro se a conexão ou a consulta falhar
        echo "Erro: ".$e -> getMessage();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar funcionários</title>
</head>
<body>
     <h1>Consulta de funcionários</h1>

     <ul>
        <?php foreach($funcionarios as $funcionario): ?>
            <li>
                <!-- A linha abaixo exibe o link para visualizar os detalhes dos
                 funcionários com base no ID -->
                <a href="visu_func.php?id=<?=$funcionario['id']?>">
                    <!-- A linha abaixo exibe o nome do funcionário -->
                    <?= htmlspecialchars($funcionario['nome']) ?>
                </a>
                <!-- Fromulário para excluir funcionários -->
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="excluir_id" value="<?= $funcionario['id']?>">
                    <button type="submit">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
     </ul>
     <a href="cad_func.php">Voltar á cadastro</a>
     
     <address>
        <center>
            Gustavo Fratoni Boeing
        </center>
     </address>
</body>
</html>