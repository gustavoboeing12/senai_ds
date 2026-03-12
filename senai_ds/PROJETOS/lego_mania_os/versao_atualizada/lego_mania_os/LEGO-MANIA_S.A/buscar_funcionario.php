<?php
require_once 'conexao.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM funcionario WHERE id_funcionario = :id";
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