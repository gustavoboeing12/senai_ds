<?php
require_once 'conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT id_cliente,id_funcionario,nome_cliente,cpf_cnpj,endereco,bairro,cep,cidade,estado,telefone,email 
            FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cliente) {
        header('Content-Type: application/json');
        echo json_encode($cliente);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Cliente não encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
}
?>