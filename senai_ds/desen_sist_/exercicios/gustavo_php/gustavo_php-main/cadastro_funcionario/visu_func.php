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

        // Verifica se o id foi passado na URL
        if(isset($_GET['id'])){
            // Obtém o id do usuário através da URL
            $id = $_GET['id'];
            // Recupera os dados do funcionário no banco de dados
            $sql = "SELECT nome,telefone,tipo_foto,foto FROM funcionarios WHERE id = :id";
            $stmt = $pdo -> prepare($sql);
            // Vincula o valor do ID ao parâmetro :id
            $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
            // Executa a instrução sql
            $stmt -> execute();

            // Verifica se encontrou o funcionário
            if($stmt -> rowCount() > 0){
                // Busca os dados dos funcionários com um array associativo
                $funcionario = $stmt -> fetch(PDO::FETCH_ASSOC);
                // Verifica se foi solicitado a exclusão do funcionário
                // Verifica se os dados foram enviados via formulário via método POST
                // isset verifica se há um valor definido na variável
                // Verifica se o formulário foi enviado via POST e se existe o campo 'excluir_id'
                if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['excluir_id'])){
                    // Pega o valor id que foi enviado pelo formulário (id do funcionário
                    // a ser excluído)
                    $excluir_id = $_POST['excluir_id'];
                    // Monta a query sql para deletar o funcionário com o id correspondente
                    $sql_excluir = "DELETE FROM funcionarios WHERE id = :id";

                    // Prepara a query para a execução segura evitando sqlinjection
                    $stmt_excluir = $pdo -> prepare($sql_excluir);
                    // Associa o valor id ao parâmetro :id na query, garantindo que será
                    // Tratado como um número
                    $stmt_excluir -> bindParam(':id',$excluir_id, PDO::PARAM_INT);

                    // Executa a query excluíndo o funcionário do banco de dados
                    $stmt_excluir -> execute();

                    // Redireciona o arquivo e fecha
                    header("Location: cons_func.php");
                    exit();
                }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar funcionários</title>
</head>
<body>
     <h1>Visualizar Funcionários</h1>
     <p>Nome: <?= htmlspecialchars($funcionario['nome'])?></p>
     <p>Telefone: <?= htmlspecialchars($funcionario['telefone'])?></p>
     <p>Foto:</p>
     <img src="data:<?$funcionario['tipo_foto']?>;base64, <?=base64_encode
     ($funcionario['foto'])?>" alt="Foto do funcionário">
     <!-- Formulário para excluir funcionário -->
     <form method="POST">
        <input type="hidden" name="excluir_id" value="<?=$id ?>">
        <button type="submit">Excluir</button>
        <a href="cons_func.php">Voltar á consulta</a>
     </form>

     <address>
        <center>
            Gustavo Fratoni Boeing
        </center>
     </address>
</body>
</html>
<?php            
            } else{
                echo "Funcionário não encontrado.";
            }
        } else{
           echo "ID do usuário não foi fornecido";
        }
    } catch(PDOException $e){
        echo "Erro: ".$e -> getMessage();
    }
?>

