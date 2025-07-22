document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const tabela = document.getElementById('os-table-body');
    const searchInput = document.getElementById('search-input');
    const dataInputs = document.querySelectorAll('#data-recebimento');
    const dataInicioInput = dataInputs[0];
    const dataFimInput = dataInputs[1];
    const viewModal = document.getElementById('view-modal');
    
    // Carrega as ordens de serviço
    let ordens = JSON.parse(localStorage.getItem('ordensServico')) || [];
    
    // Função principal para preencher a tabela
function preencherTabela(filtro = '', dataInicio = null, dataFim = null) {
    tabela.innerHTML = '';
    const mensagem = document.getElementById('mensagem-vazia');
    
    let ordensFiltradas = ordens.filter(os => os.status === 'RESOLVIDA');

    // Flag para saber se algum filtro foi aplicado
    const filtroAplicado = filtro || (dataInicio && dataFim);

    if (filtro) {
        const termo = filtro.toLowerCase();
        ordensFiltradas = ordensFiltradas.filter(os =>
            (os.problema && os.problema.toLowerCase().includes(termo)) ||
            (os.tecnico && os.tecnico.toLowerCase().includes(termo)) ||
            (os.nome && os.nome.toLowerCase().includes(termo))
        );
    }

    if (dataInicio && dataFim) {
        const inicio = parseDate(dataInicio);
        const fim = parseDate(dataFim);

        ordensFiltradas = ordensFiltradas.filter(os => {
            const dataOS = parseDate(os.dataConclusao || os.dataRecebimento);
            return dataOS >= inicio && dataOS <= fim;
        });
    }

    // Exibir ou esconder a mensagem
    if (ordensFiltradas.length === 0 && filtroAplicado) {
        mensagem.style.display = 'block';
        mensagem.textContent = 'Nenhum serviço encontrado com os critérios selecionados';
    } else {
        mensagem.style.display = 'none';
    }

    // Preenche a tabela
    ordensFiltradas.forEach(os => {
        const tr = document.createElement('tr');
        tr.dataset.id = os.id;

        tr.innerHTML = `
            <td>${os.problema || 'Não informado'}</td>
            <td>${os.tecnico || 'Não informado'}</td>
            <td>${os.tipoPeca || 'Não informado'}</td>
            <td>${formatarData(os.dataRecebimento)}</td>
            <td>${formatarData(os.dataConclusao) || formatarData(os.dataRecebimento)}</td>
            <td class="actions-cell">
                <button class="action-btn view-btn" title="Visualizar">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="action-btn delete-btn" title="Excluir">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tabela.appendChild(tr);
    });
}

// Na inicialização, chame sem mostrar mensagem
preencherTabela();  
    
    // Funções auxiliares
    function parseDate(dateStr) {
        if (!dateStr) return null;
        const [day, month, year] = dateStr.split('/');
        return new Date(year, month - 1, day);
    }
    
    function formatarData(dataStr) {
        if (!dataStr) return '';
        const [day, month, year] = dataStr.split('/');
        return `${day}/${month}/${year}`;
    }
    
    // Configuração dos datepickers
    flatpickr("#data-recebimento", {
        dateFormat: "d/m/Y",
        locale: "pt",
        onChange: function(selectedDates, dateStr, instance) {
            const dataInicio = dataInicioInput.value;
            const dataFim = dataFimInput.value;
            const filtro = searchInput.value.trim();
            preencherTabela(filtro, dataInicio, dataFim);
        }
    });
    
    // Função de visualização
    function visualizarOS(id) {
        const os = ordens.find(o => o.id === id);
        if (!os) return;
    
        // Preenche o modal
        document.getElementById('view-cliente').textContent = os.nome || 'Não informado';
        document.getElementById('view-marca').textContent = `${os.marca || ''} ${os.modelo || ''}`.trim() || 'Não informado';
        document.getElementById('view-problema').textContent = os.problema || 'Não informado';
        document.getElementById('view-observacoes').textContent = os.observacao || 'Nenhuma observação';
        document.getElementById('view-tecnico').textContent = os.tecnico || 'Não informado';
        document.getElementById('view-data-recebimento').textContent = formatarData(os.dataRecebimento) || 'Não informado';
        document.getElementById('view-data-conclusao').textContent = formatarData(os.dataConclusao) || 'Não informado';
    
        // >>> LINHA REMOVIDA <<<
        // document.getElementById('view-pecas').textContent = os.pecasUtilizadas ? os.pecasUtilizadas.join(', ') : 'Nenhuma peça registrada';
    
        // Exibe o modal
        viewModal.style.display = 'block';
    }
    
    
    // Função de exclusão
    function excluirOS(id) {
        ordens = ordens.filter(o => o.id !== id);
        localStorage.setItem('ordensServico', JSON.stringify(ordens));
        const filtro = searchInput.value.trim();
        const dataInicio = dataInicioInput.value;
        const dataFim = dataFimInput.value;
        preencherTabela(filtro, dataInicio, dataFim);
    }
    
    // Event Listeners usando delegação de eventos
    tabela.addEventListener('click', function(e) {
        const target = e.target;
        
        // Verifica se clicou no botão de visualização ou em seu ícone
        const viewBtn = target.closest('.view-btn');
        if (viewBtn) {
            const tr = viewBtn.closest('tr');
            if (tr) {
                const id = parseInt(tr.dataset.id);
                visualizarOS(id);
            }
            return;
        }
        
        // Verifica se clicou no botão de exclusão ou em seu ícone
        const deleteBtn = target.closest('.delete-btn');
        if (deleteBtn) {
            const tr = deleteBtn.closest('tr');
            if (tr) {
                const id = parseInt(tr.dataset.id);
                if (confirm('Tem certeza que deseja excluir este serviço realizado?')) {
                    excluirOS(id);
                }
            }
        }
    });
    
    // Fechar modal
    document.querySelector('.close-btn').addEventListener('click', function() {
        viewModal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target == viewModal) {
            viewModal.style.display = 'none';
        }
    });
    
    // Tecla ESC para fechar modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && viewModal.style.display === 'block') {
            viewModal.style.display = 'none';
        }
    });
    
    // Função de busca
    searchInput.addEventListener('input', function() {
        const filtro = this.value.trim();
        const dataInicio = dataInicioInput.value;
        const dataFim = dataFimInput.value;
        preencherTabela(filtro, dataInicio, dataFim);
    });
    
    // Botão Voltar
    document.getElementById('btnvoltaros').addEventListener('click', function() {
        window.location.href = '../tela_geral/tela_geral.html';
    });
    
    // Inicializa a tabela
    preencherTabela();
});