document.addEventListener('DOMContentLoaded', function() {
    // Configuração do datepicker
    flatpickr("#data-recebimento", {
      dateFormat: "d/m/Y",
      locale: "pt",
      defaultDate: new Date() // Define a data atual como padrão
    });
  
    // Manipulador do formulário de cadastro
    form.addEventListener('submit', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation(); // Adicione esta linha para evitar outros handlers
  
  if (this.dataset.submitted) return; // Prevenir múltiplos submits
  this.dataset.submitted = true;
      
      // Validar todos os campos antes de prosseguir
      if (validarFormulario()) {
        // Obter valores do formulário
        const peca = {
          nome: document.getElementById('nome').value.trim(),
          data: document.getElementById('data-recebimento').value,
          quantidade: document.getElementById('id').value,
          tipo: document.getElementById('text').value.trim()
        };
  
        // Salvar no localStorage
        salvarPeca(peca);
        
        // Limpar formulário
        form.reset();
        
        // Feedback para o usuário
        alert('Peça cadastrada com sucesso!');
      }
    });
  
    // Função para validar todo o formulário
    function validarFormulario() {
      let valido = true;
      
      // Validar campo Nome da Peça
      const nomePeca = document.getElementById('nome');
      if (!validarNomePeca(nomePeca.value)) {
        marcarInvalido(nomePeca, 'Por favor, insira um nome válido para a peça (mínimo 3 caracteres)');
        valido = false;
      } else {
        marcarValido(nomePeca);
      }
  
      // Validar campo Data
      const dataRecebimento = document.getElementById('data-recebimento');
      if (!validarData(dataRecebimento.value)) {
        marcarInvalido(dataRecebimento, 'Por favor, selecione uma data válida');
        valido = false;
      } else {
        marcarValido(dataRecebimento);
      }
  
      // Validar campo Quantidade
      const quantidade = document.getElementById('id');
      if (!validarQuantidade(quantidade.value)) {
        marcarInvalido(quantidade, 'Por favor, insira uma quantidade válida (número inteiro positivo)');
        valido = false;
      } else {
        marcarValido(quantidade);
      }
  
      // Validar campo Tipo
      const tipoPeca = document.getElementById('text');
      if (!validarTipoPeca(tipoPeca.value)) {
        marcarInvalido(tipoPeca, 'Por favor, insira um tipo válido (mínimo 3 caracteres)');
        valido = false;
      } else {
        marcarValido(tipoPeca);
      }
  
      return valido;
    }
  
    // Funções de validação específicas para cada campo
    function validarNomePeca(nome) {
      return nome.trim().length >= 3 && /^[a-zA-Z0-9\sáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\-]+$/.test(nome);
    }
  
    function validarData(data) {
      // Verifica se a data está no formato dd/mm/aaaa
      const regexData = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/;
      if (!regexData.test(data)) return false;
      
      // Verifica se é uma data válida
      const partes = data.split('/');
      const dia = parseInt(partes[0], 10);
      const mes = parseInt(partes[1], 10) - 1; // Mês é 0-indexado
      const ano = parseInt(partes[2], 10);
      const dataObj = new Date(ano, mes, dia);
      
      return dataObj.getFullYear() === ano && 
             dataObj.getMonth() === mes && 
             dataObj.getDate() === dia;
    }
  
    function validarQuantidade(qtd) {
      return /^\d+$/.test(qtd) && parseInt(qtd) > 0;
    }
  
    function validarTipoPeca(tipo) {
      return tipo.trim().length >= 3 && /^[a-zA-Z\sáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\-]+$/.test(tipo);
    }
  
    // Funções para marcar campos como válidos/inválidos
    function marcarInvalido(campo, mensagem) {
      campo.style.borderColor = '#ff4444';
      const grupo = campo.closest('.form-group');
      
      // Remove mensagens de erro anteriores
      const erroAnterior = grupo.querySelector('.mensagem-erro');
      if (erroAnterior) erroAnterior.remove();
      
      // Adiciona nova mensagem de erro
      const mensagemErro = document.createElement('small');
      mensagemErro.className = 'mensagem-erro';
      mensagemErro.style.color = '#ff4444';
      mensagemErro.textContent = mensagem;
      grupo.appendChild(mensagemErro);
    }
  
    function marcarValido(campo) {
      campo.style.borderColor = '#00C851';
      const grupo = campo.closest('.form-group');
      const erroAnterior = grupo.querySelector('.mensagem-erro');
      if (erroAnterior) erroAnterior.remove();
    }
  
    // Função para salvar peça
    function salvarPeca(peca) {
  // Verificar se os campos obrigatórios estão preenchidos
  if (!peca.nome || !peca.data || !peca.id || !peca.tipo) {
    console.warn('Tentativa de salvar peça incompleta:', peca);
    return false;
  }
  
  let pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  pecas.push(peca);
  localStorage.setItem('pecas', JSON.stringify(pecas));
  return true;
}

// Função para calcular o total de peças
function calcularTotalPecas() {
  const pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  let total = 0;
  
  pecas.forEach(peca => {
    const qtd = parseInt(peca.quantidade || peca.id) || 0; // Usa peca.quantidade ou peca.id (para compatibilidade)
    total += qtd;
  });
  
  document.getElementById('totalPecas').textContent = total;
}

// Modifique a função salvarPeca para atualizar o total após salvar
function salvarPeca(peca) {
  // Verificar se os campos obrigatórios estão preenchidos
  if (!peca.nome || !peca.data || !peca.quantidade || !peca.tipo) {
    console.warn('Tentativa de salvar peça incompleta:', peca);
    return false;
  }
  
  let pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  pecas.push(peca);
  localStorage.setItem('pecas', JSON.stringify(pecas));
  
  // Atualiza o total de peças
  calcularTotalPecas();
  return true;
}

// Chame a função para calcular o total quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
  calcularTotalPecas();
});
  
    // Adiciona validação em tempo real para melhor UX
    document.getElementById('nome').addEventListener('input', function() {
      if (validarNomePeca(this.value)) {
        marcarValido(this);
      }
    });
  
    document.getElementById('id').addEventListener('input', function() {
      if (validarQuantidade(this.value)) {
        marcarValido(this);
      }
    });
  
    document.getElementById('text').addEventListener('input', function() {
      if (validarTipoPeca(this.value)) {
        marcarValido(this);
      }
    });
  
  });
  
  // Cabeçalho horário
  // Função para atualizar data e hora
function atualizarHorario() {
  const elementoHorario = document.querySelector('.horario');
  const agora = new Date();
  
  // Formatar data (dd/mm/aaaa)
  const dia = String(agora.getDate()).padStart(2, '0');
  const mes = String(agora.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
  const ano = agora.getFullYear();
  
  // Formatar hora (hh:mm:ss)
  const horas = String(agora.getHours()).padStart(2, '0');
  const minutos = String(agora.getMinutes()).padStart(2, '0');
  const segundos = String(agora.getSeconds()).padStart(2, '0');
  
  // Atualizar o elemento HTML
  elementoHorario.innerHTML = `<p>Data: ${dia}/${mes}/${ano} - Hora: ${horas}:${minutos}:${segundos}</p>`;
}

// Atualizar imediatamente quando a página carregar
atualizarHorario();

// Atualizar a cada segundo (1000ms)
setInterval(atualizarHorario, 1000);


    // Adicione este código ao seu arquivo JavaScript do cadastro
document.addEventListener('DOMContentLoaded', function() {
  // Configuração do datepicker
  flatpickr("#data-recebimento", {
    dateFormat: "d/m/Y",
    locale: "pt"
  });

  // Manipulador do formulário de cadastro
  const form = document.querySelector('.form-pecas');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Obter valores do formulário
    const peca = {
  nome: document.getElementById('nome').value.trim(),
  data: document.getElementById('data-recebimento').value,
  quantidade: document.getElementById('quantidade').value, // Alterado de 'id' para 'quantidade'
  tipo: document.getElementById('text').value.trim()
};

    // Salvar no localStorage
    salvarPeca(peca);
    
    // Limpar formulário
    form.reset();
    
    // Feedback para o usuário
    alert('Peça cadastrada com sucesso!');
  });

  // Função para salvar peça
  function salvarPeca(peca) {
    let pecas = JSON.parse(localStorage.getItem('pecas')) || [];
    pecas.push(peca);
    localStorage.setItem('pecas', JSON.stringify(pecas));
  }
});