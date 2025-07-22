document.addEventListener('DOMContentLoaded', function() {
    // Configuração do datepicker
    flatpickr("#data", {
      dateFormat: "d/m/Y",
      locale: "pt",
      defaultDate: new Date()
    });
  
    // Vincula o select ao input hidden
    const selectTecnico = document.getElementById('tecnico-select');
    const inputTecnico = document.getElementById('tecnico');
    const selectPrioridade = document.getElementById('prioridade-select');
    const inputPrioridade = document.getElementById('prioridade');
    
    selectTecnico.addEventListener('change', function() {
      inputTecnico.value = this.value;
    });

    selectPrioridade.addEventListener('change', function() {
        inputPrioridade.value = this.value;
    });
  
    // Formulário de cadastro
    const form = document.querySelector('.form-funcionario');
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validação do técnico
      if (!inputTecnico.value) {
        alert('Por favor, selecione um técnico');
        return;
      }
      
      // Coletar dados do formulário
      const novaOS = {
        id: Date.now(),
        nome: document.getElementById('nome').value,
        marca: document.getElementById('marca').value, // Corrigido de 'cpf' para 'marca'
        tempoUso: document.getElementById('tempoUso').value, // Adicionado campo faltante
        problema: document.getElementById('problema').value, // Corrigido de 'cep'
        observacao: document.getElementById('observacao').value,
        dataRecebimento: document.getElementById('data').value,
        status: 'ABERTA',
        tecnico: inputTecnico.value,
        prioridade: inputPrioridade.value // Garantir que está sendo pego corretamente
      };
  
      // Salvar no localStorage
      salvarOS(novaOS);
      
      // Limpar formulário
      form.reset();
      inputTecnico.value = '';
      selectTecnico.value = '';
      
      // Feedback para o usuário
      alert('Ordem de Serviço cadastrada com sucesso!');
    });
  
    function salvarOS(os) {
      let ordens = JSON.parse(localStorage.getItem('ordensServico')) || [];
      ordens.push(os);
      localStorage.setItem('ordensServico', JSON.stringify(ordens));
    }
  
    // Botão Voltar
    document.getElementById('btnvoltaros').addEventListener('click', function() {
      window.location.href = '../tela_geral/tela_geral.html';
    });
  
    // Botão Conferir O.S
    document.getElementById('conferir').addEventListener('click', function() {
      window.location.href = 'ordem_abertas.html';
    });
  });