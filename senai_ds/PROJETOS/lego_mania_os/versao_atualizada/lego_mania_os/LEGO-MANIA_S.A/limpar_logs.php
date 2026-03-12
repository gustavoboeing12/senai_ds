<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário é administrador
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='logs.php';</script>";
    exit();
}

try {
    // Remove todos os registro do LOG 
    $sql = "TRUNCATE TABLE log_acao";
    $pdo->exec($sql);
    
    // Registra a ação de limpar logs (sem a coluna detalhes)
    registrarLog("Limpeza completa dos logs do sistema", "log_acao", null);
    
    echo "<script>alert('Logs limpos com sucesso!'); window.location.href='logs.php';</script>";
} catch (PDOException $e) {
    echo "<script>alert('Erro ao limpar logs: " . $e->getMessage() . "'); window.location.href='logs.php';</script>";
}
?>