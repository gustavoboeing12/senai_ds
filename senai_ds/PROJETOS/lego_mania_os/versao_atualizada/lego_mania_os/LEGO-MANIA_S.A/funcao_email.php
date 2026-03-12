<?php

// Define uma função chamada 'gerarSenhaTemporaria'.
// Funções são blocos de código que realizam uma tarefa específica e podem ser chamadas (usadas) várias vezes.
function gerarSenhaTemporaria($tamanho = 8) {

    // Função de embaralhar uma lista de letras e números e depois pega os primeiros '$tamanho'
    // caracteres dessa mistura para formar a senha.
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $tamanho);
}


// Define uma função chamada 'simularEnvioEmail'.
// - $destinatario: O endereço de e-mail para quem o e-mail (simulado) seria enviado.
// - $senha: A senha (provavelmente a temporária gerada pela função acima) que será incluída no corpo do e-mail.
function simularEnvioEmail($destinatario, $senha) {
    // Cria a mensagem que seria o corpo do e-mail.
    $mensagem = "Olá! Sua nova senha temporária é: $senha\n";

    // Cria uma string de registro que simula o cabeçalho e o corpo do e-mail.
    $registro = "Para: $destinatario\n$mensagem\n----------------------\n";

    // Esta função NÃO ENVIA um e-mail de verdade. Ela apenas simula o processo
    // salvando as informações do e-mail em um arquivo de texto local.
    file_put_contents("emails_simulados.txt", $registro, FILE_APPEND);
}

// FILE_APPEND utilizado para gerar novos registros sem apagar os anteriores.
?>