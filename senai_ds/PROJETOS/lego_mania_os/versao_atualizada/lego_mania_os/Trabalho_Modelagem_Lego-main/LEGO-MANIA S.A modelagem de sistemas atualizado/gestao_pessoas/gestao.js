document.addEventListener('DOMContentLoaded', function() {
    // Variáveis globais
    let currentPage = 1;
    const rowsPerPage = 10;
    let allEmployees = [];
    let filteredEmployees = [];
    
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
        // Dados de exemplo para funcionários
        allEmployees = [
            {
                id: 1,
                nome: 'Inácio',
                cpf: '143.140.073-14',
                salario: '10000,00',
                dataNascimento: '04/12/1988',
                cep: '46288-123',
                funcao: 'Administrador',
                email: 'inacio@gmail.com',
                visible: true
            },
            {
                id: 2,
                nome: 'Maria Silva',
                cpf: '987.654.321-00',
                salario: '3500,00',
                dataNascimento: '15/05/1990',
                cep: '04538-132',
                funcao: 'Técnica',
                email: 'maria.silva@email.com',
                visible: true
            },
            {
                id: 3,
                nome: 'João Santos',
                cpf: '456.789.123-00',
                salario: '4200,00',
                dataNascimento: '22/09/1985',
                cep: '01310-100',
                funcao: 'Atendente',
                email: 'joao.santos@email.com',
                visible: true
            }
        ];
        
        filteredEmployees = [...allEmployees];
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
            const totalPages = Math.ceil(filteredEmployees.length / rowsPerPage);
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
            saveEditedEmployee();
        });
        
        // Botão voltar
        document.getElementById('btnvoltaros').addEventListener('click', function() {
            window.location.href = '../tela_geral/tela_geral.html';
        });
    }
    
    function filterAndRenderTable() {
        // Aplica filtro de pesquisa
        const searchTerm = searchInput.value.toLowerCase();
        
        filteredEmployees = allEmployees.filter(employee => {
            const matchesSearch = 
                employee.nome.toLowerCase().includes(searchTerm) ||
                employee.cpf.toLowerCase().includes(searchTerm) ||
                employee.salario.toLowerCase().includes(searchTerm) ||
                employee.dataNascimento.toLowerCase().includes(searchTerm) ||
                employee.cep.toLowerCase().includes(searchTerm) ||
                employee.funcao.toLowerCase().includes(searchTerm) ||
                employee.email.toLowerCase().includes(searchTerm);
            
            // Aplica filtro de visibilidade
            const matchesVisibility = 
                visibilityFilter.value === 'all' ||
                (visibilityFilter.value === 'visible' && employee.visible) ||
                (visibilityFilter.value === 'hidden' && !employee.visible);
            
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
        const employeesToShow = filteredEmployees.slice(startIndex, endIndex);
        
        // Preenche a tabela
        employeesToShow.forEach(employee => {
            const row = document.createElement('tr');
            row.dataset.id = employee.id;
            
            if (!employee.visible) {
                row.classList.add('hidden-row');
            }
            
            row.innerHTML = `
                <td>${employee.nome}</td>
                <td>${employee.cpf}</td>
                <td>${employee.salario}</td>
                <td>${employee.dataNascimento}</td>
                <td>${employee.cep}</td>
                <td>${employee.funcao}</td>
                <td>${employee.email}</td>
                <td class="actions-cell">
                    <button class="action-btn edit-btn" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete-btn" title="${employee.visible ? 'Ocultar' : 'Mostrar'}">
                        <i class="fas ${employee.visible ? 'fa-eye-slash' : 'fa-eye'}"></i>
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
                const employeeId = parseInt(row.dataset.id);
                const employee = allEmployees.find(e => e.id === employeeId);
                
                if (employee) {
                    openEditModal(employee);
                }
            });
        });
        
        // Botões de ocultar/mostrar
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const employeeId = parseInt(row.dataset.id);
                toggleEmployeeVisibility(employeeId);
            });
        });
    }
    
    function openEditModal(employee) {
        document.getElementById('edit-id').value = employee.id;
        document.getElementById('edit-funcionario').value = employee.nome;
        document.getElementById('edit-cpf').value = employee.cpf;
        document.getElementById('edit-salario').value = employee.salario;
        document.getElementById('edit-dataNascimento').value = employee.dataNascimento;
        document.getElementById('edit-cep').value = employee.cep;
        document.getElementById('edit-funcao').value = employee.funcao;
        document.getElementById('edit-email').value = employee.email;
        
        editModal.style.display = 'block';
    }
    
    function saveEditedEmployee() {
        const employeeId = parseInt(document.getElementById('edit-id').value);
        const employeeIndex = allEmployees.findIndex(e => e.id === employeeId);
        
        if (employeeIndex !== -1) {
            allEmployees[employeeIndex] = {
                ...allEmployees[employeeIndex],
                nome: document.getElementById('edit-funcionario').value,
                cpf: document.getElementById('edit-cpf').value,
                salario: document.getElementById('edit-salario').value,
                dataNascimento: document.getElementById('edit-dataNascimento').value,
                cep: document.getElementById('edit-cep').value,
                funcao: document.getElementById('edit-funcao').value,
                email: document.getElementById('edit-email').value
            };
            
            editModal.style.display = 'none';
            filterAndRenderTable();
            
            // Aqui você pode adicionar uma chamada para salvar no banco de dados
            alert('Funcionário atualizado com sucesso!');
        }
    }
    
    function toggleEmployeeVisibility(employeeId) {
        const employeeIndex = allEmployees.findIndex(e => e.id === employeeId);
        
        if (employeeIndex !== -1) {
            allEmployees[employeeIndex].visible = !allEmployees[employeeIndex].visible;
            filterAndRenderTable();
            
            // Aqui você pode adicionar uma chamada para atualizar no banco de dados
            alert(`Funcionário ${allEmployees[employeeIndex].visible ? 'mostrado' : 'ocultado'} com sucesso!`);
        }
    }
    
    function updatePagination() {
        const totalPages = Math.ceil(filteredEmployees.length / rowsPerPage);
        pageNumberSpan.textContent = currentPage;
        
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
    }
});