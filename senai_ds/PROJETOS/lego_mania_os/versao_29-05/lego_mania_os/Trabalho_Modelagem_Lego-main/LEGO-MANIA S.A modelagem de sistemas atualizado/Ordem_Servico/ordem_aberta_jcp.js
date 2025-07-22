document.addEventListener('DOMContentLoaded', function() {
    let ordens = JSON.parse(localStorage.getItem('ordensServico')) || [];
    const tabela = document.getElementById('os-table-body');
    
    // Função para salvar e atualizar a lista de ordens
    function salvarOrdens(novasOrdens) {
    ordens = novasOrdens;
    localStorage.setItem('ordensServico', JSON.stringify(ordens));
    
    // Verifica se há um termo de pesquisa ativo
    const termoPesquisa = document.querySelector('.search-box input').value;
    if (termoPesquisa) {
        filtrarTabela(termoPesquisa);
    } else {
        preencherTabela();
    }
}

    // Preenche a tabela
    function preencherTabelaComDados(dados) {
    tabela.innerHTML = '';
    
    dados.forEach(os => {
        const tr = document.createElement('tr');
        tr.dataset.id = os.id;
        
        tr.innerHTML = `
            <td>${os.tecnico || 'Não atribuído'}</td>
            <td><span class="priority-badge ${getPriorityClass(os.prioridade || 'MÉDIA')}">${os.prioridade}</span></td>
            <td>${os.marca || ''}</td>
            <td>${os.problema || ''}</td>
            <td><span class="status-badge ${getStatusClass(os.status || 'ABERTA')}">${os.status || 'ABERTA'}</span></td>
            <td>${os.dataRecebimento || ''}</td>
            <td class="actions-cell">
                <button class="action-btn edit-btn" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn delete-btn" title="Excluir">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        
        tabela.appendChild(tr);
    });
}

// Mantenha a função original para quando não há filtro
function preencherTabela() {
    preencherTabelaComDados(ordens);
}

function mostrarMensagemNenhumResultado() {
    // Remove a mensagem existente se houver
    esconderMensagemNenhumResultado();
    
    const tr = document.createElement('tr');
    tr.id = 'mensagem-nenhum-resultado';
    tr.innerHTML = `
        <td colspan="7" style="text-align: center; padding: 20px;">
            Nenhum serviço encontrado com os critérios procurados
        </td>
    `;
    
    tabela.innerHTML = '';
    tabela.appendChild(tr);
}

function esconderMensagemNenhumResultado() {
    const mensagemExistente = document.getElementById('mensagem-nenhum-resultado');
    if (mensagemExistente) {
        mensagemExistente.remove();
    }
}

// Mantenha a função original para quando não há filtro
function preencherTabela() {
    preencherTabelaComDados(ordens);
}

    function getPriorityClass(prioridade) {
    switch(prioridade.toUpperCase()) {
        case 'ALTA': return 'high';
        case 'MÉDIA': return 'medium';
        case 'BAIXA': return 'low';
        default: return 'medium';
    }
}

    // Implementação com delegação de eventos
    tabela.addEventListener('click', function(e) {
        const btn = e.target.closest('.action-btn');
        if (!btn) return;
        
        const tr = btn.closest('tr');
        const id = parseInt(tr.dataset.id);
        
        if (btn.classList.contains('edit-btn')) {
            abrirModalEdicao(id);
        } else if (btn.classList.contains('delete-btn')) {
            if (confirm('Tem certeza que deseja excluir esta OS?')) {
                excluirOS(id);
            }
        }
    });

    function filtrarTabela(termo) {
    termo = termo.toLowerCase();
    
    const ordensFiltradas = ordens.filter(os => {
        return (
            (os.tecnico && os.tecnico.toLowerCase().includes(termo)) ||
            (os.marca && os.marca.toLowerCase().includes(termo)) ||
            (os.problema && os.problema.toLowerCase().includes(termo)) ||
            (os.prioridade && os.prioridade.toLowerCase().includes(termo)) ||
            (os.status && os.status.toLowerCase().includes(termo))
        );
    });
    
    if (ordensFiltradas.length === 0 && termo !== '') {
        mostrarMensagemNenhumResultado();
    } else {
        esconderMensagemNenhumResultado();
        // Usamos uma cópia das ordens filtradas para não perder os dados originais
        preencherTabelaComDados(ordensFiltradas);
    }
}

    function excluirOS(id) {
        const novasOrdens = ordens.filter(o => o.id !== id);
        salvarOrdens(novasOrdens);
    }

    function getStatusClass(status) {
        switch(status) {
            case 'RESOLVIDA': return 'completed';
            case 'EM ANDAMENTO': return 'in-progress';
            default: return 'open';
        }
    }

    function abrirModalEdicao(id) {
        const os = ordens.find(o => o.id === id);
        if (!os) return;
        
        // Preenche o modal
        document.getElementById('edit-id').value = os.id;
        document.getElementById('edit-funcionario').value = os.tecnico;
        document.getElementById('edit-prioridade').value = os.prioridade;
        document.getElementById('edit-equipamento').value = os.marca;
        document.getElementById('edit-problema').value = os.problema;
        document.getElementById('edit-status').value = os.status;
        
        document.getElementById('edit-modal').style.display = 'block';
    }
    
    // Formulário de edição
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = parseInt(document.getElementById('edit-id').value);
        const osIndex = ordens.findIndex(o => o.id === id);
        
        if (osIndex !== -1) {
            // Cria uma cópia do array para garantir a imutabilidade
            const novasOrdens = [...ordens];
            
            // Atualiza os dados na cópia
            novasOrdens[osIndex] = {
                ...novasOrdens[osIndex],
                tecnico: document.getElementById('edit-funcionario').value,
                prioridade: document.getElementById('edit-prioridade').value,
                marca: document.getElementById('edit-equipamento').value,
                problema: document.getElementById('edit-problema').value,
                status: document.getElementById('edit-status').value,
                dataConclusao: document.getElementById('edit-status').value === 'RESOLVIDA' 
                    ? new Date().toLocaleDateString('pt-BR') 
                    : null
            };
            
            // Salva as alterações
            salvarOrdens(novasOrdens);
            
            // Fecha o modal
            document.getElementById('edit-modal').style.display = 'none';
        }
    });
    
    // Fechar modal
    document.querySelector('.close-btn').addEventListener('click', function() {
        document.getElementById('edit-modal').style.display = 'none';
    });
    
    // Botão Nova OS
    document.getElementById('nova-os-btn').addEventListener('click', function() {
        window.location.href = 'nova_ordem.html';
    });

    // Barra de pesquisar
    document.querySelector('.search-box input').addEventListener('input', function(e) {
       filtrarTabela(e.target.value);
    });
    
    // Inicializa a tabela
    preencherTabela();
});