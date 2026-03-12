<?php
session_start();
    require_once 'conexao.php';

    // Verifica se tem nível de ADM ou secretaria
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){
        header("Location: principal.php");
        exit();
    }

    // Obtem o tipo do relatório 
    $tipo = $_GET['tipo'] ?? '';

    // Faz uma Busca na tabela usuario e faz um LEFT JOIN nas tabelas perfil e motivo_inatividade.
    $sql = "SELECT u.*, p.nome_perfil, m.descricao as motivo_inatividade FROM usuario u 
            LEFT JOIN perfil p ON u.id_perfil = p.id_perfil 
            LEFT JOIN motivo_inatividade m ON u.id_motivo_inatividade = m.id_motivo
            ORDER BY u.nome_usuario ASC";
 
    // Encapsula e protege para evitar sqlInjection
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC); // Executa a consulta e retorna todos os registros como um array. 

    // Calcular estatísticas dos usuários por status.
    $total_usuarios = count($usuarios);
    $ativos = count(array_filter($usuarios, fn($u) => $u['status'] === 'Ativo'));
    $inativos = $total_usuarios - $ativos;

    // Inicializa um array vazio para armazenar a contagem por perfil
    $perfis = [];

    // Percorre cada usuário da lista de usuários
    foreach ($usuarios as $usuario) {
            // Obtém o nome do perfil do usuário atual
        $perfil = $usuario['nome_perfil'];
            // Verifica se este perfil ainda não existe no array de contagem
            if (!isset($perfis[$perfil])) {
                $perfis[$perfil] = 0;
            }
            $perfis[$perfil]++;
    }

    // Gera o relatório de acordo com o solicitado.
    switch ($tipo) {
        case 'estatisticas': // Relatorio estatistica de todos os usuários(inativos, ativos e perfil).
            gerarRelatorioEstatisticas($usuarios, $total_usuarios, $ativos, $inativos, $perfis);
            break;
        case 'pdf': // Relatorio por meio de pdf
            gerarPDF($usuarios, $total_usuarios, $ativos, $inativos, $perfis);
            break;
        case 'excel': // Relatorio por meio de excel
            gerarExcel($usuarios);
            break;
        case 'csv': // Relatorio por meio de csv
            gerarCSV($usuarios);
            break;
        default:
            header("Location: gestao_usuario.php"); // Se nenhum escolhido, retorna para gestão_usuario.php
            exit();
    }

    function gerarRelatorioEstatisticas($usuarios, $total, $ativos, $inativos, $perfis) {
    
        // Define os cabeçalhos HTTP para forçar o download de um arquivo PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="estatisticas_usuarios.pdf"');
        
        // Este código gera manualmente um PDF

        // Início da estrutura básica do documento PDF
        $conteudo = "%PDF-1.4\n\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n\n4 0 obj\n<<\n/Length 200\n>>\nstream\nBT\n/F1 12 Tf\n50 750 Td\n(Relatório de Estatísticas de Usuários) Tj\n0 -20 Td\n(Data: " . date('d/m/Y H:i:s') . ") Tj\n0 -40 Td\n(Total de Usuários: $total) Tj\n0 -20 Td\n(Usuários Ativos: $ativos) Tj\n0 -20 Td\n(Usuários Inativos: $inativos) Tj\n0 -40 Td\n(Distribuição por Perfil:) Tj\n";
        
        // Posição vertical inicial para a lista de perfis
        $y = 550;
        
        // Adiciona cada perfil e sua quantidade ao PDF
        foreach ($perfis as $perfil => $quantidade) {
            $conteudo .= "0 -20 Td\n($perfil: $quantidade) Tj\n";
            $y -= 20; // Move para a próxima linha
            
            // Previne que o texto ultrapasse a margem inferior da página
            if ($y < 50) break;
        }
        
        // Finaliza a estrutura do PDF com os metadados necessários
        $conteudo .= "ET\nendstream\nendobj\n\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000239 00000 n \ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n" . strlen($conteudo) . "\n%%EOF";
        
        // Envia o conteúdo PDF gerado para o navegador
        echo $conteudo;
        
        // Termina a execução do script para garantir que apenas o PDF seja enviado
        exit();
}
    
?>

function gerarPDF($usuarios, $total, $ativos, $inativos, $perfis) {
    // Carregar a biblioteca TCPDF
    require_once('tcpdf/tcpdf.php');
    
    // Criar novo documento PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Configurar documento
    $pdf->SetCreator('Lego Mania');
    $pdf->SetAuthor('Sistema Lego Mania');
    $pdf->SetTitle('Lista de Usuários');
    $pdf->SetSubject('Relatório de Usuários');
    
    // Adicionar uma página
    $pdf->AddPage();
    
    // Configurar fonte
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Lista de Usuários - Lego Mania', 0, 1, 'C');
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Data: ' . date('d/m/Y H:i:s'), 0, 1);
    $pdf->Ln(5);
    
    // Criar tabela
    $html = '<table border="1" cellpadding="4">
        <tr style="background-color:#f2f2f2;">
            <th width="8%">ID</th>
            <th width="25%">Nome</th>
            <th width="30%">Email</th>
            <th width="17%">Perfil</th>
            <th width="10%">Status</th>
        </tr>';
    
    foreach ($usuarios as $usuario) {
        $html .= '<tr>
            <td>' . $usuario['id_usuario'] . '</td>
            <td>' . htmlspecialchars($usuario['nome_usuario']) . '</td>
            <td>' . htmlspecialchars($usuario['email']) . '</td>
            <td>' . htmlspecialchars($usuario['nome_perfil']) . '</td>
            <td>' . $usuario['status'] . '</td>
        </tr>';
    }
    
    $html .= '</table>';
    
    // Escrever o HTML no PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Gerar o PDF e forçar download
    $pdf->Output('lista_usuarios.pdf', 'D');
    exit();
}

function gerarExcel($usuarios) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="lista_usuarios.xls"');
    
    echo "ID\tNome\tEmail\tPerfil\tStatus\tData Inatividade\tMotivo Inatividade\n";
    
    foreach ($usuarios as $usuario) {
        echo $usuario['id_usuario'] . "\t";
        echo $usuario['nome_usuario'] . "\t";
        echo $usuario['email'] . "\t";
        echo $usuario['nome_perfil'] . "\t";
        echo $usuario['status'] . "\t";
        echo $usuario['data_inatividade'] . "\t";
        echo $usuario['motivo_inatividade'] . "\n";
    }
    
    exit();
}

function gerarCSV($usuarios) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="lista_usuarios.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Nome', 'Email', 'Perfil', 'Status', 'Data Inatividade', 'Motivo Inatividade'], ';');
    
    foreach ($usuarios as $usuario) {
        fputcsv($output, [
            $usuario['id_usuario'],
            $usuario['nome_usuario'],
            $usuario['email'],
            $usuario['nome_perfil'],
            $usuario['status'],
            $usuario['data_inatividade'],
            $usuario['motivo_inatividade']
        ], ';');
    }
    
    fclose($output);
    exit();
}