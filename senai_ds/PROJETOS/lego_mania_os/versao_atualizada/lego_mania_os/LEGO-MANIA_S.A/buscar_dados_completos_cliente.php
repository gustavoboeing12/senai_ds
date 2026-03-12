<?php
session_start();
require_once 'conexao.php';

// Verifica permissão (ADM ou SECRETARIA)
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    echo json_encode(["erro" => "Acesso negado"]);
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT 
                c.id_cliente,
                c.nome_cliente,
                c.cpf_cnpj,
                c.endereco,
                c.bairro,
                c.cep,
                c.cidade,
                c.estado,
                c.telefone,
                c.email,
                c.status,
                c.data_inatividade,
                c.observacao_inatividade
            FROM cliente c
            WHERE c.id_cliente = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["erro" => "Cliente não encontrado"]);
    }
} else {
    echo json_encode(["erro" => "ID inválido"]);
}
?>