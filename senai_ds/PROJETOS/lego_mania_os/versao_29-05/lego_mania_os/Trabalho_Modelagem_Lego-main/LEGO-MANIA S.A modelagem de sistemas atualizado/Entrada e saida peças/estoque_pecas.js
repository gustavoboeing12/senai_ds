document.addEventListener('DOMContentLoaded', function() {
      // Dados de exemplo - 4 peças com saídas
      const pecasSaida = [
        {
          id: 1,
          nome: "Bloco 2x4 Vermelho",
          codigo: "13",
          tipo: "Bloco padrão",
          dataSaida: "15/05/2025",
          quantidade: 50
        },
        {
          id: 2,
          nome: "Eixo Cinza",
          codigo: "22",
          tipo: "Eixo",
          dataSaida: "20/05/2025",
          quantidade: 30
        },
        {
          id: 3,
          nome: "Rodinha Preta",
          codigo: "8",
          tipo: "Acessório",
          dataSaida: "22/05/2025",
          quantidade: 20
        },
        {
          id: 4,
          nome: "Placa Base Verde",
          codigo: "12",
          tipo: "Placa",
          dataSaida: "25/05/2025",
          quantidade: 5
        }
      ];

      // Salva os dados no localStorage (apenas para exemplo)
      localStorage.setItem('pecasSaida', JSON.stringify(pecasSaida));

      // Carrega as peças
      carregarPecas();

      // Configuração dos datepickers
      flatpickr(".data", {
        dateFormat: "d/m/Y",
        locale: "pt",
        onChange: function(selectedDates, dateStr, instance) {
          filtrarPorData();
        }
      });

      // Configura datepicker do modal
      flatpickr("#edit-data-saida", {
        dateFormat: "d/m/Y",
        locale: "pt"
      });

      // Pesquisa por texto
      document.getElementById('search-input').addEventListener('input', function() {
        filtrarTabela(this.value.toLowerCase());
      });

      // Botão Voltar
      document.getElementById('btnvoltaros').addEventListener('click', function() {
        window.location.href = '../tela_geral/tela_geral.html';
      });
    });

    function carregarPecas() {
      const pecas = JSON.parse(localStorage.getItem('pecasSaida')) || [];
      const tbody = document.getElementById('os-table-body');
      const mensagemVazia = document.getElementById('mensagem-vazia');
      
      tbody.innerHTML = '';
      
      if (pecas.length === 0) {
        mensagemVazia.style.display = 'block';
        mensagemVazia.textContent = "Nenhuma peça encontrada";
        return;
      } else {
        mensagemVazia.style.display = 'none';
      }
      
      pecas.forEach((peca) => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-id', peca.id);
        
        tr.innerHTML = `
          <td>${peca.nome}</td>
          <td>${peca.codigo}</td>
          <td>${peca.tipo}</td>
          <td>${peca.dataSaida}</td>
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

    function adicionarEventListeners() {
      // Botões de editar
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const row = this.closest('tr');
          const id = parseInt(row.getAttribute('data-id'));
          abrirModalEdicao(id);
        });
      });
      
      // Botões de excluir
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const row = this.closest('tr');
          const id = parseInt(row.getAttribute('data-id'));
          excluirPeca(id);
        });
      });
      
      // Fechar modal
      document.querySelector('.close-btn').addEventListener('click', fecharModal);
      document.getElementById('edit-form').addEventListener('submit', salvarEdicao);
    }

    function abrirModalEdicao(id) {
      const pecas = JSON.parse(localStorage.getItem('pecasSaida')) || [];
      const peca = pecas.find(p => p.id === id);
      
      if (peca) {
        document.getElementById('edit-id').value = peca.id;
        document.getElementById('edit-nome').value = peca.nome;
        document.getElementById('edit-codigo').value = peca.codigo;
        document.getElementById('edit-tipo').value = peca.tipo;
        document.getElementById('edit-data-saida').value = peca.dataSaida;
        
        document.getElementById('edit-modal').style.display = 'block';
      }
    }

    function fecharModal() {
      document.getElementById('edit-modal').style.display = 'none';
    }

    function salvarEdicao(e) {
      e.preventDefault();
      
      const id = parseInt(document.getElementById('edit-id').value);
      const pecas = JSON.parse(localStorage.getItem('pecasSaida')) || [];
      const index = pecas.findIndex(p => p.id === id);
      
      if (index !== -1) {
        pecas[index] = {
          ...pecas[index],
          nome: document.getElementById('edit-nome').value,
          codigo: document.getElementById('edit-codigo').value,
          tipo: document.getElementById('edit-tipo').value,
          dataSaida: document.getElementById('edit-data-saida').value
        };
        
        localStorage.setItem('pecasSaida', JSON.stringify(pecas));
        carregarPecas();
        fecharModal();
        alert("Registro de saída atualizado com sucesso!");
      }
    }

    function excluirPeca(id) {
      if (confirm('Tem certeza que deseja excluir este registro de saída?')) {
        let pecas = JSON.parse(localStorage.getItem('pecasSaida')) || [];
        pecas = pecas.filter(p => p.id !== id);
        localStorage.setItem('pecasSaida', JSON.stringify(pecas));
        carregarPecas();
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
      
      if (linhasVisiveis === 0) {
        mensagemVazia.textContent = "Nenhuma peça encontrada com os critérios selecionados";
        mensagemVazia.style.display = 'block';
      } else {
        mensagemVazia.style.display = 'none';
      }
    }

    function filtrarPorData() {
      const dataInicio = document.getElementById('data-inicio').value;
      const dataFim = document.getElementById('data-fim').value;
      const mensagemVazia = document.getElementById('mensagem-vazia');
      
      if (dataInicio && dataFim) {
        const inicio = parseDate(dataInicio);
        const fim = parseDate(dataFim);
        const linhas = document.querySelectorAll('#os-table-body tr');
        let linhasVisiveis = 0;
        
        linhas.forEach(linha => {
          const dataSaidaStr = linha.cells[3].textContent;
          const dataSaida = parseDate(dataSaidaStr);
          
          if (dataSaida >= inicio && dataSaida <= fim) {
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

    function parseDate(dateStr) {
      const [day, month, year] = dateStr.split('/');
      return new Date(year, month - 1, day);
    }