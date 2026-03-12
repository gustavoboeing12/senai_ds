<?php
    require_once 'conexao.php';

    // BUSCA POR ID na tabela usuario nos campos citados...
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        
        // Faz uma busca no banco de dados
        $sql = "SELECT u.*, p.nome_perfil, m.descricao as motivo_inatividade 
                FROM usuario u 
                LEFT JOIN perfil p ON u.id_perfil = p.id_perfil 
                LEFT JOIN motivo_inatividade m ON u.id_motivo_inatividade = m.id_motivo
                WHERE u.id_usuario = :id";
        
        // encapsula e protege
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se a variável $usuario contém dados 
        if ($usuario) {
            header('Content-Type: application/json'); // Define o cabeçalho HTTP para indicar que a resposta será JSON
            echo json_encode($usuario); // Converte o objeto/array $usuario em JSON e envia como resposta
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido']);
    }
    //200 OK: Implícito quando o usuário é encontrado
    //400 Bad Request: ID inválido ou malformado
    //404 Not Found: Usuário não existe no banco de dados
?>