<?php
session_start();
require_once 'conexao.php';

// Forçar um usuário administrativo para teste (apenas para desenvolvimento)
$_SESSION['id_usuario'] = 12; // ID do admin
$_SESSION['perfil'] = 1; // Perfil de administrador

// Testar a função de log
if (function_exists('registrarLog')) {
    $resultado = registrarLog("Teste de log do sistema", "sistema", 999);
    if ($resultado) {
        echo "Log registrado com sucesso! Verifique a página logs.php";
    } else {
        echo "Falha ao registrar log";
    }
} else {
    echo "Função registrarLog não encontrada!";
}
?>