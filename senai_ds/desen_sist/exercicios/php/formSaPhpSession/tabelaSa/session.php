<?php
        $lista_dados = array();
            if(isset($_GET['nome'])){
                $_SESSION['lista_dados'][] = $_GET['nome'];
            
            if(isset($_GET['cpf_cnpj'])){
                $_SESSION['lista_dados'][] = $_GET['cpf_cnpj'];
            
            if(isset($_GET['salario'])){
                $_SESSION['lista_dados'][] = $_GET['salario'];
            
            if(isset($_GET['cep'])){
                $_SESSION['lista_dados'][] = $_GET['cep'];
            
            if(isset($_GET['nasci'])){
                $_SESSION['lista_dados'][] = $_GET['nasci'];
            
            if(isset($_GET['email'])){
                $_SESSION['lista_dados'][] = $_GET['email'];
            
            if(isset($_GET['senha'])){
                $_SESSION['lista_dados'][] = $_GET['senha'];
            }}}}}}}
        $lista_dados = array();
        if(isset($_SESSION['lista_dados'])){
            $lista_dados = $_SESSION['lista_dados'];
        }
     ?> 
<table>
        <tr>
            <th>Dados</th>
        </tr>
        <?php foreach ($lista_dados as $dados) : ?>
        <tr>
            <td>
                <?php echo $dados; ?>
            </td>
        </tr>
        <?php endforeach; ?>
</table>