<?php
require 'conexao.php';

if (isset($_GET['id'])) {
    $idOrdem = intval($_GET['id']);

    $sql = "SELECT no.id_ordem, 
                   no.nome_client_ordem AS nome_cliente, 
                   no.problema, 
                   no.status_ordem, 
                   no.dt_recebimento, 
                   u.nome_usuario AS nome_tecnico,  -- Adicionado nome do técnico
                   no.valor_total, 
                   no.prioridade,
                   no.marca_aparelho,
                   no.observacao,
                   no.tempo_uso
            FROM nova_ordem no
            LEFT JOIN usuario u ON no.tecnico = u.id_usuario  -- JOIN com usuário para pegar nome do técnico
            WHERE no.id_ordem = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idOrdem, PDO::PARAM_INT);
    $stmt->execute();

    $ordem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ordem) {
        echo json_encode($ordem);
    } else {
        echo json_encode(['error' => 'Ordem não encontrada']);
    }
} else {
    echo json_encode(['error' => 'ID inválido']);
}