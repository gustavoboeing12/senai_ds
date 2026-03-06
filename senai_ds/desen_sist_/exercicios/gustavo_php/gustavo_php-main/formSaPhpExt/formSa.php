<?php
     if(isset($_GET['nome'])&& isset($_GET['cpf_cnpj'])&& isset($_GET['salario'])
     && isset($_GET['cep'])&& isset($_GET['nasci'])&& isset($_GET['email'])
     && isset($_GET['senha'])){
       echo "Recebido os dados do funcionário: ". $_GET['nome']."<br/>";
       echo "com os seguintes dados: ".
       "Nome: ".$_GET['nome']."<br/>". 
       "CPF_CNPJ: ".$_GET['cpf_cnpj']."<br/>". 
       "Salário: ".$_GET['salario']."<br/>".
       "Cep: ".$_GET['cep']."<br/>". 
       "Data de nascimento: ".$_GET['nasci']."<br/>". 
       "Email: ".$_GET['email']."<br/>". 
       "Senha: ".$_GET['senha'];
     }
?>