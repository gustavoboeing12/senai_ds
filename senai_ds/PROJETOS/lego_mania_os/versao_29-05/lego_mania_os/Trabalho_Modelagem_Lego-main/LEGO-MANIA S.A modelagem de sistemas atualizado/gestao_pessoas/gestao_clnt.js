// gestao_clnt.js

document.addEventListener('DOMContentLoaded', function() {
    // Variáveis globais
    let currentPage = 1;
    const rowsPerPage = 10;
    let allClients = [];
    let filteredClients = [];
    
    // Elementos do DOM
    const tableBody = document.getElementById('os-table-body');
    const searchInput = document.getElementById('search-input');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageNumberSpan = document.getElementById('page-number');
    const visibilityFilter = document.getElementById('visibility-filter');
    
    // Modal de edição
    const editModal = document.getElementById('edit-modal');
    const closeBtn = document.querySelector('.close-btn');
    const editForm = document.getElementById('edit-form');
    
    // Inicialização
    initialize();
    
    function initialize() {
        // Carrega dados iniciais (simulados)
        loadSampleData();
        
        // Configura eventos
        setupEventListeners();
        
        // Renderiza a tabela
        filterAndRenderTable();
    }
    
    function loadSampleData() {
        // Dados de exemplo
        allClients = [
            {
                id: 1,
                nome: 'Gustavo Tobler',
                cpf_cnpj: '123.456.789-00',
                cep: '01001-000',
                telefone: '(11) 98765-4321',
                email: 'gustavo_tobler@email.com',
                visible: true
            },
            {
                id: 2,
                nome: 'Maria Silva',
                cpf_cnpj: '987.654.321-00',
                cep: '04538-132',
                telefone: '(11) 91234-5678',
                email: 'maria.silva@email.com',
                visible: true
            },
            {
                id: 3,
                nome: 'João Santos',
                cpf_cnpj: '456.789.123-00',
                cep: '01310-100',
                telefone: '(11) 99876-5432',
                email: 'joao.santos@email.com',
                visible: true
            }
        ];
        
        filteredClients = [...allClients];
    }
    
    function setupEventListeners() {
        // Pesquisa
        searchInput.addEventListener('input', function() {
            currentPage = 1;
            filterAndRenderTable();
        });
        
        // Filtro de visibilidade
        visibilityFilter.addEventListener('change', function() {
            currentPage = 1;
            filterAndRenderTable();
        });
        
        // Paginação
        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        
        nextPageBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredClients.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
        
        // Modal de edição
        closeBtn.addEventListener('click', function() {
            editModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
        });
        
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveEditedClient();
        });
        
        // Botão voltar
        document.getElementById('btnvoltaros').addEventListener('click', function() {
            window.location.href = '../tela_geral/tela_geral.html';
        });
    }
    
    function filterAndRenderTable() {
        // Aplica filtro de pesquisa
        const searchTerm = searchInput.value.toLowerCase();
        
        filteredClients = allClients.filter(client => {
            const matchesSearch = 
                client.nome.toLowerCase().includes(searchTerm) ||
                client.cpf_cnpj.toLowerCase().includes(searchTerm) ||
                client.cep.toLowerCase().includes(searchTerm) ||
                client.telefone.toLowerCase().includes(searchTerm) ||
                client.email.toLowerCase().includes(searchTerm);
            
            // Aplica filtro de visibilidade
            const matchesVisibility = 
                visibilityFilter.value === 'all' ||
                (visibilityFilter.value === 'visible' && client.visible) ||
                (visibilityFilter.value === 'hidden' && !client.visible);
            
            return matchesSearch && matchesVisibility;
        });
        
        renderTable();
    }
    
    function renderTable() {
        // Limpa a tabela
        tableBody.innerHTML = '';
        
        // Calcula paginação
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const clientsToShow = filteredClients.slice(startIndex, endIndex);
        
        // Preenche a tabela
        clientsToShow.forEach(client => {
            const row = document.createElement('tr');
            row.dataset.id = client.id;
            
            if (!client.visible) {
                row.classList.add('hidden-row');
            }
            
            row.innerHTML = `
                <td>${client.nome}</td>
                <td>${client.cpf_cnpj}</td>
                <td>${client.cep}</td>
                <td>${client.telefone}</td>
                <td>${client.email}</td>
                <td class="actions-cell">
                    <button class="action-btn edit-btn" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete-btn" title="${client.visible ? 'Ocultar' : 'Mostrar'}">
                        <i class="fas ${client.visible ? 'fa-eye-slash' : 'fa-eye'}"></i>
                    </button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Configura eventos dos botões na tabela
        setupTableButtons();
        
        // Atualiza paginação
        updatePagination();
    }
    
    function setupTableButtons() {
        // Botões de editar
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const clientId = parseInt(row.dataset.id);
                const client = allClients.find(c => c.id === clientId);
                
                if (client) {
                    openEditModal(client);
                }
            });
        });
        
        // Botões de ocultar/mostrar
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const clientId = parseInt(row.dataset.id);
                toggleClientVisibility(clientId);
            });
        });
    }
    
    function openEditModal(client) {
        document.getElementById('edit-id').value = client.id;
        document.getElementById('edit-nome').value = client.nome;
        document.getElementById('edit-cpf_cnpj').value = client.cpf_cnpj;
        document.getElementById('edit-cep').value = client.cep;
        document.getElementById('edit-telefone').value = client.telefone;
        document.getElementById('edit-email').value = client.email;
        
        editModal.style.display = 'block';
    }
    
    function saveEditedClient() {
        const clientId = parseInt(document.getElementById('edit-id').value);
        const clientIndex = allClients.findIndex(c => c.id === clientId);
        
        if (clientIndex !== -1) {
            allClients[clientIndex] = {
                ...allClients[clientIndex],
                nome: document.getElementById('edit-nome').value,
                cpf_cnpj: document.getElementById('edit-cpf_cnpj').value,
                cep: document.getElementById('edit-cep').value,
                telefone: document.getElementById('edit-telefone').value,
                email: document.getElementById('edit-email').value
            };
            
            editModal.style.display = 'none';
            filterAndRenderTable();
            
            // Aqui você pode adicionar uma chamada para salvar no banco de dados
            alert('Cliente atualizado com sucesso!');
        }
    }
    
    function toggleClientVisibility(clientId) {
        const clientIndex = allClients.findIndex(c => c.id === clientId);
        
        if (clientIndex !== -1) {
            allClients[clientIndex].visible = !allClients[clientIndex].visible;
            filterAndRenderTable();
            
            // Aqui você pode adicionar uma chamada para atualizar no banco de dados
            alert(`Cliente ${allClients[clientIndex].visible ? 'mostrado' : 'ocultado'} com sucesso!`);
        }
    }
    
    function updatePagination() {
        const totalPages = Math.ceil(filteredClients.length / rowsPerPage);
        pageNumberSpan.textContent = currentPage;
        
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
    }
});