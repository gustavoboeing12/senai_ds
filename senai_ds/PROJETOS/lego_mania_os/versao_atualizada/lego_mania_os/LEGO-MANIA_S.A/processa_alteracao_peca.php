<?php
    session_start();
    require_once 'conexao.php';
    require_once 'php/permissoes.php';

    if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2 && $_SESSION['perfil']!=4){
        echo "<script> alert ('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }
    
    // Verifica se o formulario foi enviado e se o metodo for igual a POST
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        $id_peca = $_POST['id_peca_est'];
        $nome_peca = trim($_POST['nome_peca']);
        $tipo = trim($_POST['tipo']);
        $fornecedor = trim($_POST['id_fornecedor']);
        $preco = $_POST['preco'] ?? '0';

        // remove símbolos (se houver)
        $preco = str_replace(['R$', ' '], '', $preco);
        
        // troca vírgula por ponto
        $preco = str_replace(',', '.', $preco);
        
        // agora sim converte para float
        $preco = (float)$preco;
        $quantidade = (int)$_POST['quantidade'];
        $quantidade_minima = isset($_POST['quantidade_minima']) ? (int)$_POST['quantidade_minima'] : 0;

        // ATUALIZA OS DADOS DO USUÁRIO
        $sql="UPDATE peca_estoque SET nome_peca = :nome_peca,tipo=:tipo,id_fornecedor=:fornecedor,preco=:preco,qtde=:quantidade,qtde_minima=:quantidade_minima WHERE id_peca_est = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_peca',$nome_peca);
        $stmt->bindParam(':tipo',$tipo);
        $stmt->bindParam(':fornecedor',$fornecedor);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':quantidade',$quantidade);
        $stmt->bindParam(':quantidade_minima',$quantidade_minima);
        $stmt->bindParam(':id',$id_peca);

        // Alerta de quantidade minina no estoque.
        if($quantidade < $quantidade_minima){
            echo "<script>alert('A quantidade em estoque não pode ser menor que a quantidade mínima!');window.location.href='relatorio_pecas_estoque.php?id=$id_peca';</script>";
            exit();
        }

        // Alerta de peça atualizada ou erro ao atualizar peça.
    if($stmt->execute()){
        echo "<script>alert('Peça atualizado com sucesso!');window.location.href='relatorio_pecas_estoque.php';</script>";
    } else{
        echo "<script>alert('Erro ao atualizar peça');window.location.href='relatorio_pecas_estoque.php?id=$id_peca';</script>";
    }
    }
    
        
?>