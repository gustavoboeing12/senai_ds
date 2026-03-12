<?php
require_once 'conexao.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT f.*, m.descricao as motivo_inatividade 
            FROM funcionario f 
            LEFT JOIN motivo_inatividade m ON f.id_motivo_inatividade = m.id_motivo 
            WHERE f.id_funcionario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($funcionario) {
        header('Content-Type: application/json');
        echo json_encode($funcionario);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Funcionário não encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
}
?>