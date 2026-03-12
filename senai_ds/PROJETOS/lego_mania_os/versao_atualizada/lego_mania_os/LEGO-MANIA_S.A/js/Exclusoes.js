function excluirPeca(id) {
    if (confirm('Tem certeza que deseja excluir esta peça?')) {
        // Cria um formulário oculto e envia via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'processa_exclusao_peca.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_peca_est';
        input.value = id;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    }
}