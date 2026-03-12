<?php
// Configurações para a conexão com o banco de dados

// $host: Define o endereço do servidor onde o banco de dados está hospedado.
$host = 'localhost';

// $dbname: Define o nome do banco de dados ao qual queremos nos conectar.
$dbname = 'lego_mania';

// $user: Define o nome de usuário para acessar o banco de dados.
$user = 'root';

// $pass: Define a senha para o usuário do banco de dados.
$senha = '';

// Se algo der errado ao tentar conectar ao banco de dados (dentro do 'try'),
// o código dentro do 'catch' será executado para lidar com o erro de forma controlada.
try {
    // Tenta criar uma nova conexão com o banco de dados usando PDO.
    // "mysql:host=$host;dbname=$dbname;charset=utf8mb4" é o DSN (Data Source Name).
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $senha);

    // Configura o PDO para lançar exceções em caso de erros.
    // PDO::ATTR_ERRMODE: Define o modo de relatório de erros.
    // PDO::ERRMODE_EXCEPTION: Se ocorrer um erro na comunicação com o banco (ex: consulta SQL errada),
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Se ocorrer qualquer erro (uma PDOException) durante a tentativa de conexão no bloco 'try',
    // die() interrompe a execução do script e exibe uma mensagem.
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// Função para registrar logs no sistema - VERSÃO CORRIGIDA
if (!function_exists('registrarLog')) {
    function registrarLog($id_usuario, $acao, $tabela_afetada, $id_registro = null) {
        global $pdo;
        
        // Verifica se a sessão está ativa e tem os dados necessários
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['perfil'])) {
            error_log("Tentativa de registrar log sans sessão de usuário válida");
            return false;
        }
        
        try {
            $sql = "INSERT INTO log_acao (id_usuario, id_perfil, acao, tabela_afetada, id_registro) 
                    VALUES (:id_usuario, :id_perfil, :acao, :tabela_afetada, :id_registro)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_perfil' => $_SESSION['perfil'],
                ':acao' => $acao,
                ':tabela_afetada' => $tabela_afetada,
                ':id_registro' => $id_registro
            ]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao registrar log: " . $e->getMessage());
            return false;
        }
    }
}
?>