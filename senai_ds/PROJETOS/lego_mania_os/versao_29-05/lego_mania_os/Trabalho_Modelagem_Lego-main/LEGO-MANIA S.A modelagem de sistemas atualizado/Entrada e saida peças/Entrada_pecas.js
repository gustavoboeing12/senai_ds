document.addEventListener('DOMContentLoaded', function() {
  // Configuração dos datepickers
  const datepickers = flatpickr(".data", {
    dateFormat: "d/m/Y",
    locale: "pt",
    onChange: function(selectedDates, dateStr, instance) {
      // Quando uma data é alterada, aplicar o filtro
      filtrarPorData();
    }
  });

  // Configurar datepicker do modal
  flatpickr("#edit-dataRecebimento", {
    dateFormat: "d/m/Y",
    locale: "pt"
  });

  // Carregar peças ao iniciar
  carregarPecas();

  // Pesquisa por texto
  document.getElementById('search-input').addEventListener('input', function() {
    filtrarTabela(this.value.toLowerCase());
  });
});

function carregarPecas() {
  const pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  const tbody = document.getElementById('os-table-body');
  const mensagemVazia = document.getElementById('mensagem-vazia');
  
  tbody.innerHTML = '';
  
  if (pecas.length === 0) {
    mensagemVazia.style.display = 'block';
    return;
  } else {
    mensagemVazia.style.display = 'none';
  }
  
  pecas.forEach((peca, index) => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', index + 1);
    
    tr.innerHTML = `
      <td>${peca.nome}</td>
      <td>${peca.quantidade || '0'}</td>  <!-- Alterado para peca.quantidade -->
      <td>${peca.tipo}</td>
      <td>${peca.data}</td>
      <td class="actions-cell">
        <button class="action-btn edit-btn" title="Editar">
          <i class="fas fa-edit"></i>
        </button>
        <button class="action-btn delete-btn" title="Excluir">
          <i class="fas fa-trash-alt"></i>
        </button>
      </td>
    `;
    
    tbody.appendChild(tr);
  });
  
  adicionarEventListeners();
}

function filtrarPorData() {
  const dataInicioInput = document.querySelector('.date-field:first-child input');
  const dataFimInput = document.querySelector('.date-field:last-child input');
  const mensagemVazia = document.getElementById('mensagem-vazia');
  
  const dataInicioStr = dataInicioInput.value;
  const dataFimStr = dataFimInput.value;
  
  // Se ambos os campos estiverem preenchidos, aplicar o filtro
  if (dataInicioStr && dataFimStr) {
    const dataInicio = parseDate(dataInicioStr);
    const dataFim = parseDate(dataFimStr);
    
    const linhas = document.querySelectorAll('#os-table-body tr');
    let linhasVisiveis = 0;
    
    linhas.forEach(linha => {
      const dataPecaStr = linha.cells[3].textContent;
      const dataPeca = parseDate(dataPecaStr);
      
      if (dataPeca >= dataInicio && dataPeca <= dataFim) {
        linha.style.display = '';
        linhasVisiveis++;
      } else {
        linha.style.display = 'none';
      }
    });
    
    if (linhasVisiveis === 0) {
      mensagemVazia.textContent = "Nenhuma peça encontrada entre as datas selecionadas";
      mensagemVazia.style.display = 'block';
    } else {
      mensagemVazia.style.display = 'none';
    }
  } else {
    // Se algum campo estiver vazio, mostrar todas as linhas
    const linhas = document.querySelectorAll('#os-table-body tr');
    linhas.forEach(linha => {
      linha.style.display = '';
    });
    mensagemVazia.style.display = 'none';
  }
}

// Função para converter string de data (dd/mm/aaaa) para objeto Date
function parseDate(dateStr) {
  const [day, month, year] = dateStr.split('/');
  return new Date(year, month - 1, day);
}

function adicionarEventListeners() {
  // Botões de editar
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const row = this.closest('tr');
      const id = row.getAttribute('data-id');
      abrirModalEdicao(id);
    });
  });
  
  // Botões de excluir
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const row = this.closest('tr');
      const id = row.getAttribute('data-id');
      excluirPeca(id);
    });
  });
  
  // Fechar modal quando clicar no X
  document.querySelector('.close-btn').addEventListener('click', fecharModal);
  
  // Fechar modal quando clicar fora dele
  window.addEventListener('click', function(event) {
    const modal = document.getElementById('edit-modal');
    if (event.target === modal) {
      fecharModal();
    }
  });
  
  // Formulário de edição
  document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    salvarEdicao();
  });
}

function abrirModalEdicao(id) {
  const pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  const index = parseInt(id) - 1;
  
  if (index >= 0 && index < pecas.length) {
    const peca = pecas[index];
    const modal = document.getElementById('edit-modal');
    
    // Preencher o formulário com os dados da peça
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-funcionario').value = peca.nome;
    document.getElementById('edit-quantidade').value = peca.quantidade;
    document.getElementById('edit-salario').value = peca.tipo;
    document.getElementById('edit-dataRecebimento').value = peca.data;
    
    // Mostrar o modal
    modal.style.display = 'block';
  }
}

function fecharModal() {
  document.getElementById('edit-modal').style.display = 'none';
}

function salvarEdicao() {
  const id = document.getElementById('edit-id').value;
  const index = parseInt(id) - 1;
  
  let pecas = JSON.parse(localStorage.getItem('pecas')) || [];
  
  if (index >= 0 && index < pecas.length) {
    pecas[index] = {
      nome: document.getElementById('edit-funcionario').value,
      quantidade: document.getElementById('edit-quantidade').value,  // Certifique-se que está correto
      tipo: document.getElementById('edit-salario').value,
      data: document.getElementById('edit-dataRecebimento').value
    };
    
    localStorage.setItem('pecas', JSON.stringify(pecas));
    fecharModal();
    carregarPecas();
  }
  alert("Você alterou as informações!");
}

function excluirPeca(id) {
  if (confirm('Tem certeza que deseja excluir esta peça?')) {
    let pecas = JSON.parse(localStorage.getItem('pecas')) || [];
    const index = parseInt(id) - 1;
    
    if (index >= 0 && index < pecas.length) {
      pecas.splice(index, 1);
      localStorage.setItem('pecas', JSON.stringify(pecas));
      carregarPecas();
    }
  }
}

function filtrarTabela(termo) {
  const linhas = document.querySelectorAll('#os-table-body tr');
  const mensagemVazia = document.getElementById('mensagem-vazia');
  let linhasVisiveis = 0;
  
  linhas.forEach(linha => {
    const textoLinha = linha.textContent.toLowerCase();
    if (termo === '' || textoLinha.includes(termo)) {
      linha.style.display = '';
      linhasVisiveis++;
    } else {
      linha.style.display = 'none';
    }
  });
  
  // Mostrar mensagem se não houver linhas visíveis
  if (linhasVisiveis === 0) {
    mensagemVazia.textContent = "Nenhuma peça encontrada com os critérios selecionados";
    mensagemVazia.style.display = 'block';
  } else {
    mensagemVazia.style.display = 'none';
  }
}