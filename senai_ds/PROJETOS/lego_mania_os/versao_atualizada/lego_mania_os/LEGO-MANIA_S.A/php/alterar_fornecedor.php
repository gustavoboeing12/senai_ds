<?php
session_start();
require_once '../conexao.php';
require_once 'permissoes.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta de dados
    // Obter o ID do fornecedor (deve vir de um campo hidden no formulário)
    $id_fornecedor = $_POST['id_fornecedor'];
    // TRIM retira os espaços de uma string
    $nome_fornecedor = trim($_POST['nome_fornecedor']);
    // preg_replace remove oq não for número da string, deixando apenas digitos de 0-9
    $cpf_cnpj = preg_replace('/[^0-9]/', '', $_POST['cpf_cnpj']);
    $ramo_atividade = trim($_POST['ramo_atividade']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $bairro = trim($_POST['bairro']);
    $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $email = trim($_POST['email']);
    
    // Construir a query de atualização
    $sql = "UPDATE fornecedor SET 
            nome_fornecedor = :nome_fornecedor, 
            cpf_cnpj = :cpf_cnpj, 
            ramo_atividade = :ramo_atividade, 
            telefone = :telefone,
            endereco = :endereco,
            bairro = :bairro,
            cep = :cep,
            cidade = :cidade,
            estado = :estado,
            email = :email
            WHERE id_fornecedor = :id_fornecedor";
    
    // Preparando a variável sql
    $stmt = $pdo->prepare($sql);
    // bindParam liga a variável ao parâmetro da query, enviando seu valor apenas na execução do execute().
    $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
    $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
    $stmt->bindParam(':ramo_atividade', $ramo_atividade);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':email', $email);
    // PARAM_INT Especifica a variável como número inteiro
    $stmt->bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
    
    // Executa a query preparada e retorna mensagens
    if ($stmt->execute()) {
        // REGISTRAR LOG - APÓS UPDATE BEM-SUCEDIDO
        $acao = "Alteração de fornecedor: " . $nome_fornecedor . " (" . $email . ")";
        if (function_exists('registrarLog')) {
            registrarLog($_SESSION['id_usuario'], $acao, "fornecedor", $id_fornecedor);
        }
        
        echo "<script>alert('Fornecedor atualizado com sucesso!');window.location.href='../gestao_fornecedor.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o fornecedor!');window.history.back();</script>";
    }
// Se o formulário não for enviado, redireciona á página
} else {
    header("Location: ../gestao_fornecedor.php");
    exit();
}
?>