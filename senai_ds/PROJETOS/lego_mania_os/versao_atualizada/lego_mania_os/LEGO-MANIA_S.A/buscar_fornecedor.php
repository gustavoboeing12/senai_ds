<?php
session_start();
require_once 'conexao.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($fornecedor) {
        header('Content-Type: application/json');
        echo json_encode($fornecedor);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Fornecedor não encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
}
?>