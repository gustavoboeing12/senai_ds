<?php
session_start();
require_once '../conexao.php';
require_once 'permissoes.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['alterar_ordem'])) {
    // Coleta de dados
    $id_ordem = $_POST['id_ordem'];
    $nome_client_ordem = trim($_POST['nome_client_ordem']);
    $tecnico = trim($_POST['tecnico']);
    $marca_aparelho = trim($_POST['marca_aparelho']);
    $tempo_uso = trim($_POST['tempo_uso']);
    $problema = trim($_POST['problema']);
    $prioridade = trim($_POST['prioridade']);
    $observacao = trim($_POST['observacao']);
    $status_ordem = trim($_POST['status_ordem']);
    
    // Construir a query de atualização
    $sql = "UPDATE nova_ordem SET 
            nome_client_ordem = :nome_client_ordem, 
            tecnico = :tecnico, 
            marca_aparelho = :marca_aparelho, 
            tempo_uso = :tempo_uso,
            problema = :problema,
            prioridade = :prioridade,
            observacao = :observacao,
            status_ordem = :status_ordem
            WHERE id_ordem = :id_ordem";
    
    // Preparando a variável sql
    $stmt = $pdo->prepare($sql);
    // bindParam liga a variável ao parâmetro da query, enviando seu valor apenas na execução do execute().
    $stmt->bindParam(':nome_client_ordem', $nome_client_ordem);
    $stmt->bindParam(':tecnico', $tecnico);
    $stmt->bindParam(':marca_aparelho', $marca_aparelho);
    $stmt->bindParam(':tempo_uso', $tempo_uso);
    $stmt->bindParam(':problema', $problema);
    $stmt->bindParam(':prioridade', $prioridade);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->bindParam(':status_ordem', $status_ordem);
    $stmt->bindParam(':id_ordem', $id_ordem, PDO::PARAM_INT);
    
    // Executa a query preparada e retorna mensagens
    if ($stmt->execute()) {
        // REGISTRAR LOG - APÓS UPDATE BEM-SUCEDIDO
        $acao = "Alteração de ordem de serviço: " . $nome_client_ordem . " (" . $marca_aparelho . ")";
        if (function_exists('registrarLog')) {
            registrarLog($_SESSION['id_usuario'], $acao, "nova_ordem", $id_ordem);
        }
        
        echo "<script>alert('Ordem de serviço atualizada com sucesso!');window.location.href='../gestao_ordem.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar a ordem de serviço!');window.history.back();</script>";
    }
// Se o formulário não for enviado, redireciona á página
} else {
    header("Location: ../gestao_ordem.php");
    exit();
}
?>