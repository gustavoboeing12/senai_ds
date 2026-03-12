<?php
session_start();
require_once 'conexao.php';
require_once 'php/permissoes.php';

// Verifica a permissão do usuário (ADM, Funcionário ou Técnico)
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 4) {
    echo "<script>alert('Acesso negado!');window.location.href='relatorio_pecas_estoque.php';</script>";
    exit();
}

// Verifica se o formulário foi enviado com id da peça
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id_peca_est'])) {
    $id = (int)$_POST['id_peca_est'];

    // Verifica se a peça está atribuída a alguma ordem de serviço
    $check1 = $pdo->prepare("SELECT COUNT(*) FROM ordem_servico_pecas WHERE id_peca_est = :id");
    $check1->bindParam(':id', $id, PDO::PARAM_INT);
    $check1->execute();
    $count1 = $check1->fetchColumn();

    // Verifica se a peça está em nova_ordem
    $check2 = $pdo->prepare("SELECT COUNT(*) FROM nova_ordem WHERE id_peca_est = :id");
    $check2->bindParam(':id', $id, PDO::PARAM_INT);
    $check2->execute();
    $count2 = $check2->fetchColumn();

    // Se estiver em qualquer uma delas, mostra alert
    if ($count1 > 0 || $count2 > 0) {
    echo "<script>alert('Não pode excluir essa peça, pois ela já está atribuída a uma ordem de serviço!');window.location.href='relatorio_pecas_estoque.php';</script>";
    exit();
    }

    // Se não estiver sendo usada, exclui normalmente
    $stmt = $pdo->prepare("DELETE FROM peca_estoque WHERE id_peca_est = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Peça excluída com sucesso!');window.location.href='relatorio_pecas_estoque.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir peça!');window.location.href='relatorio_pecas_estoque.php';</script>";
    }

} else {
    header('Location: relatorio_pecas_estoque.php');
    exit();
}
?>