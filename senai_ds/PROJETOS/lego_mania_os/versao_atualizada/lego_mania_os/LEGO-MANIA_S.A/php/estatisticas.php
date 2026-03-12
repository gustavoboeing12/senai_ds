<?php
require_once 'conexao.php';

function gerarEstatisticas($tabela, $filtros = []) {
    global $pdo;
    
    $estatisticas = [];
    
    switch($tabela) {
        case 'usuario':
            $estatisticas = gerarEstatisticasUsuarios($filtros);
            break;
        case 'cliente':
            $estatisticas = gerarEstatisticasClientes($filtros);
            break;
        case 'funcionario':
            $estatisticas = gerarEstatisticasFuncionarios($filtros);
            break;
        case 'fornecedor':
            $estatisticas = gerarEstatisticasFornecedores($filtros);
            break;
        case 'ordem':
            $estatisticas = gerarEstatisticasOrdens($filtros);
            break;
        default:
            $estatisticas = ['erro' => 'Tabela não suportada'];
    }
    
    return $estatisticas;
}

function gerarEstatisticasUsuarios($filtros) {
    global $pdo;
    
    $sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos,
        p.nome_perfil,
        COUNT(u.id_perfil) as quantidade
    FROM usuario u
    LEFT JOIN perfil p ON u.id_perfil = p.id_perfil";
    
    $where = [];
    $params = [];
    
    // Aplicar filtros se existirem
    if (!empty($filtros)) {
        foreach ($filtros as $campo => $valor) {
            $where[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }
    
    $sql .= " GROUP BY u.id_perfil ORDER BY quantidade DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'total' => array_sum(array_column($dados, 'quantidade')),
        'ativos' => array_sum(array_column($dados, 'ativos')),
        'inativos' => array_sum(array_column($dados, 'inativos')),
        'perfis' => $dados
    ];
}

function gerarEstatisticasClientes($filtros) {
    global $pdo;
    
    $sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos,
        cidade,
        COUNT(*) as quantidade
    FROM cliente";
    
    $where = [];
    $params = [];
    
    // Aplicar filtros se existirem
    if (!empty($filtros)) {
        foreach ($filtros as $campo => $valor) {
            $where[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }
    
    $sql .= " GROUP BY cidade ORDER BY quantidade DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sqlGeral = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos
    FROM cliente";
    
    $stmtGeral = $pdo->prepare($sqlGeral);
    $stmtGeral->execute();
    $geral = $stmtGeral->fetch(PDO::FETCH_ASSOC);
    
    return [
        'total' => $geral['total'],
        'ativos' => $geral['ativos'],
        'inativos' => $geral['inativos'],
        'cidades' => $cidades
    ];
}

function gerarEstatisticasFuncionarios($filtros) {
    global $pdo;
    
    $sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos,
        cidade,
        COUNT(*) as quantidade
    FROM funcionario";
    
    $where = [];
    $params = [];
    
    // Aplicar filtros se existirem
    if (!empty($filtros)) {
        foreach ($filtros as $campo => $valor) {
            $where[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }
    
    $sql .= " GROUP BY cidade ORDER BY quantidade DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sqlGeral = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos
    FROM funcionario";
    
    $stmtGeral = $pdo->prepare($sqlGeral);
    $stmtGeral->execute();
    $geral = $stmtGeral->fetch(PDO::FETCH_ASSOC);
    
    return [
        'total' => $geral['total'],
        'ativos' => $geral['ativos'],
        'inativos' => $geral['inativos'],
        'cidades' => $cidades
    ];
}

function gerarEstatisticasFornecedores($filtros) {
    global $pdo;
    
    $sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) as inativos,
        ramo_atividade,
        COUNT(*) as quantidade
    FROM fornecedor";
    
    $where = [];
    $params = [];
    
    // Aplicar filtros se existirem
    if (!empty($filtros)) {
        foreach ($filtros as $campo => $valor) {
            $where[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }
    
    $sql .= " GROUP BY ramo_atividade ORDER BY quantidade DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $ramos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sqlStatus = "SELECT 
        status,
        COUNT(*) as quantidade
    FROM fornecedor";
    
    if (!empty($where)) {
        $sqlStatus .= " WHERE " . implode(" AND ", $where);
    }
    
    $sqlStatus .= " GROUP BY status";
    
    $stmtStatus = $pdo->prepare($sqlStatus);
    $stmtStatus->execute($params);
    $status = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'total' => array_sum(array_column($ramos, 'quantidade')),
        'ramos' => $ramos,
        'status' => $status
    ];
}

function gerarEstatisticasOrdens($filtros) {
    global $pdo;
    
    $sql = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_ordem = 'Aberta' THEN 1 ELSE 0 END) as abertas,
        SUM(CASE WHEN status_ordem = 'Em Andamento' THEN 1 ELSE 0 END) as andamento,
        SUM(CASE WHEN status_ordem = 'Concluído' THEN 1 ELSE 0 END) as concluidas,
        SUM(CASE WHEN status_ordem = 'Cancelada' THEN 1 ELSE 0 END) as canceladas,
        prioridade,
        COUNT(*) as quantidade
    FROM nova_ordem";
    
    $where = [];
    $params = [];
    
    // Aplicar filtros se existirem
    if (!empty($filtros)) {
        foreach ($filtros as $campo => $valor) {
            $where[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }
    
    $sql .= " GROUP BY prioridade ORDER BY quantidade DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $prioridades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sqlStatus = "SELECT 
        status_ordem,
        COUNT(*) as quantidade
    FROM nova_ordem";
    
    if (!empty($where)) {
        $sqlStatus .= " WHERE " . implode(" AND ", $where);
    }
    
    $sqlStatus .= " GROUP BY status_ordem";
    
    $stmtStatus = $pdo->prepare($sqlStatus);
    $stmtStatus->execute($params);
    $status = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'total' => array_sum(array_column($prioridades, 'quantidade')),
        'prioridades' => $prioridades,
        'status' => $status
    ];
}
?>