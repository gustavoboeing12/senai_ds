  // Variável global para a instância do gráfico
  let myChartInstance = null;

  document.addEventListener('DOMContentLoaded', function() {
    // Configuração dos datepickers
    const datepickers = flatpickr(".data", {
      dateFormat: "d/m/Y",
      locale: "pt",
      onChange: function(selectedDates, dateStr, instance) {
        atualizarGrafico();
      }
    });

    // Definir datas padrão (primeiro dia do mês até hoje)
    const hoje = new Date();
    const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
    
    // Formatar datas para o padrão dd/mm/aaaa
    function formatarData(date) {
      const dia = String(date.getDate()).padStart(2, '0');
      const mes = String(date.getMonth() + 1).padStart(2, '0');
      const ano = date.getFullYear();
      return `${dia}/${mes}/${ano}`;
    }

    document.getElementById('data-inicio').value = formatarData(primeiroDiaMes);
    document.getElementById('data-fim').value = formatarData(hoje);

    // Registrar plugin
    Chart.register(ChartDataLabels);

    // Inicializar o gráfico
    atualizarGrafico();
  });

  // Função para obter as peças cadastradas (agora procurando pela chave correta)
  function obterPecasCadastradas() {
    const pecasCadastradas = JSON.parse(localStorage.getItem('pecas')) || [];
    console.log('Peças encontradas no localStorage:', pecasCadastradas); // Debug
    
    const pecasAgrupadas = {};
    
    pecasCadastradas.forEach(peca => {
      if (!pecasAgrupadas[peca.nome]) {
        pecasAgrupadas[peca.nome] = {
          nome: peca.nome,
          quantidade: 0,
          tipo: peca.tipo,
          data: peca.data
        };
      }
      // Usando peca.quantidade (que vem do campo 'id' no formulário)
      pecasAgrupadas[peca.nome].quantidade += parseInt(peca.quantidade || peca.id || 0);
      
      // Manter a data mais recente para filtro
      if (peca.data && (!pecasAgrupadas[peca.nome].data || parseDate(peca.data) > parseDate(pecasAgrupadas[peca.nome].data))) {
        pecasAgrupadas[peca.nome].data = peca.data;
      }
    });
    
    return Object.values(pecasAgrupadas);
  }

  // Função para converter string de data para Date object
  function parseDate(dateStr) {
    if (!dateStr) return null;
    const [day, month, year] = dateStr.split('/');
    return new Date(year, month - 1, day);
  }

  // Função principal para atualizar o gráfico
  function atualizarGrafico() {
  const dataInicio = document.getElementById('data-inicio').value;
  const dataFim = document.getElementById('data-fim').value;
  const noDataMessage = document.getElementById('no-data-message');
  const pecasContainer = document.getElementById('pecas-container');
  const chartCanvas = document.getElementById('myChart');
  const totalPecasElement = document.getElementById('total-pecas');

  // Obter todas as peças
  const todasPecas = obterPecasCadastradas();
  
  // Filtrar por data se ambas as datas estiverem preenchidas
  let pecasFiltradas = todasPecas;
  if (dataInicio && dataFim) {
    pecasFiltradas = filtrarPecasPorData(todasPecas, dataInicio, dataFim);
  }
  
  // Calculate total pieces
  const totalPecas = pecasFiltradas.reduce((sum, peca) => sum + peca.quantidade, 0);
  
  if (pecasFiltradas.length === 0) {
    // Modo "sem dados"
    noDataMessage.style.display = 'block';
    pecasContainer.style.display = 'none';
    chartCanvas.style.display = 'none';
    totalPecasElement.style.display = 'none';
    
    if (myChartInstance) {
      myChartInstance.destroy();
      myChartInstance = null;
    }
  } else {
    // Modo normal com dados
    noDataMessage.style.display = 'none';
    pecasContainer.style.display = 'flex';
    chartCanvas.style.display = 'block';
    totalPecasElement.style.display = 'block';
    
    // Update total display
    totalPecasElement.textContent = `Total de peças no período escolhido: ${totalPecas}`;
    
    // Calcular porcentagens
    const pecasComPorcentagem = calcularPorcentagens(pecasFiltradas);
    const topPecas = pecasComPorcentagem.slice(0, 5);
    
    atualizarRetangulosPecas(topPecas);
    renderizarGrafico(topPecas);
  }
}

  // Função para filtrar peças por data
  function filtrarPecasPorData(pecas, dataInicioStr, dataFimStr) {
    const dataInicio = parseDate(dataInicioStr);
    const dataFim = parseDate(dataFimStr);
    
    return pecas.filter(peca => {
      if (!peca.data) return false;
      const dataPeca = parseDate(peca.data);
      return dataPeca >= dataInicio && dataPeca <= dataFim;
    });
  }

  // Função para calcular porcentagens
  function calcularPorcentagens(pecas) {
    const total = pecas.reduce((sum, peca) => sum + peca.quantidade, 0);
    
    return pecas.map(peca => {
      return {
        ...peca,
        porcentagem: total > 0 ? ((peca.quantidade / total) * 100).toFixed(1) : 0
      };
    }).sort((a, b) => b.quantidade - a.quantidade); // Ordenar por quantidade
  }

  // Função para atualizar os retângulos com as peças
  function atualizarRetangulosPecas(topPecas) {
    const cores = [
      'rgba(255, 99, 132, 0.7)',
      'rgba(54, 162, 235, 0.7)',
      'rgba(255, 206, 86, 0.7)',
      'rgba(75, 192, 192, 0.7)',
      'rgba(153, 102, 255, 0.7)'
    ];
    
    const container = document.getElementById('pecas-container');
    container.innerHTML = '';
    
    topPecas.forEach((peca, index) => {
      const pecaElement = document.createElement('div');
      pecaElement.className = 'peca-info';
      pecaElement.style.backgroundColor = cores[index % cores.length];
      
      pecaElement.innerHTML = `
        <span class="peca-nome">${peca.nome}</span>
        <span class="peca-quantidade">${peca.quantidade} unidades</span>
        ${peca.data ? `<span class="peca-data">Data: ${peca.data}</span>` : ''}
        <span class="peca-porcentagem">${peca.porcentagem}% do total</span>
      `;
      
      container.appendChild(pecaElement);
    });
  }

  // Função para renderizar o gráfico
  function renderizarGrafico(topPecas) {
    const ctx = document.getElementById('myChart').getContext('2d');
    
    // Destruir gráfico existente se houver
    if (myChartInstance) {
      myChartInstance.destroy();
    }
    
    // Preparar dados para o gráfico
    const labels = topPecas.map(peca => peca.nome);
    const data = topPecas.map(peca => parseFloat(peca.porcentagem));
    const quantidades = topPecas.map(peca => peca.quantidade);
    
    const cores = [
      'rgba(255, 99, 132, 0.7)',
      'rgba(54, 162, 235, 0.7)',
      'rgba(255, 206, 86, 0.7)',
      'rgba(75, 192, 192, 0.7)',
      'rgba(153, 102, 255, 0.7)'
    ];
    
    // Criar novo gráfico
    myChartInstance = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: cores,
          borderColor: '#fff',
          borderWidth: 1,
          hoverBackgroundColor: cores.map(c => c.replace('0.7', '1')),
          hoverBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const quantidade = quantidades[context.dataIndex];
                const porcentagem = context.raw;
                return `${label}: ${quantidade} unidades (${porcentagem}%)`;
              }
            }
          },
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              usePointStyle: true,
              pointStyle: 'circle'
            }
          },
          datalabels: {
            formatter: (value, ctx) => {
              return `${value}%`;
            },
            color: '#fff',
            font: {
              weight: 'bold',
              size: 12
            }
          }
        }
      }
    });
  }