// Primeiro definimos a função mostrarAlerta
function mostrarAlerta(mensagem, tipo = 'erro') {
    // Criar elemento do alerta
    const alerta = document.createElement('div');
    
    // Definir estilos baseados no tipo
    const estilos = {
        erro: {
            background: 'linear-gradient(135deg, #ff4757, #ff3838)',
            border: '2px solid #c23616',
            icon: '❌'
        },
        sucesso: {
            background: 'linear-gradient(135deg, #2ed573, #1e90ff)',
            border: '2px solid #1e90ff',
            icon: '✅'
        },
        info: {
            background: 'linear-gradient(135deg, #3742fa, #5352ed)',
            border: '2px solid #3742fa',
            icon: 'ℹ️'
        }
    };
    
    const estilo = estilos[tipo] || estilos.erro;
    
    // Aplicar estilos
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 20px 25px;
        background: ${estilo.background};
        color: white;
        border-radius: 15px;
        border: ${estilo.border};
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        font-family: 'Arial', sans-serif;
        font-size: 16px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 300px;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        backdrop-filter: blur(10px);
    `;
    
    // Adicionar conteúdo
    alerta.innerHTML = `
        <span style="font-size: 24px;">${estilo.icon}</span>
        <span>${mensagem}</span>
        <button style="
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            margin-left: auto;
            font-weight: bold;
            font-size: 16px;
        " onclick="this.parentElement.remove()">×</button>
    `;
    
    // Adicionar animação CSS se não existir
    if (!document.querySelector('#alert-styles')) {
        const style = document.createElement('style');
        style.id = 'alert-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Adicionar ao body
    document.body.appendChild(alerta);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
        if (alerta.parentElement) {
            alerta.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => alerta.remove(), 300);
        }
    }, 5000);
}

// Agora o resto do código
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - iniciando validações');
    
    const form = document.querySelector('form[action="#"]');
    if (!form) {
        console.error('Formulário não encontrado!');
        return;
    }

    // Não permitir números no campo nome
    const nomeInput = document.getElementById('nome');
    if (nomeInput) {
        nomeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[0-9]/g, '');
        });
    }

    // Máscaras para os campos
    const masks = {
        cpf_cnpj: function(value) {
            return value.replace(/\D/g, '')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})/, '$1-$2')
                .replace(/(-\d{2})\d+?$/, '$1');
        },
        cnpj: function(value) {
            return value.replace(/\D/g, '')
                .replace(/(\d{2})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2')
                .replace(/(-\d{2})\d+?$/, '$1');
        },
        cep: function(value) {
            return value.replace(/\D/g, '')
                .replace(/(\d{5})(\d)/, '$1-$2')
                .replace(/(-\d{3})\d+?$/, '$1');
        },
        telefone: function(value) {
            return value.replace(/\D/g, '')
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{5})(\d)/, '$1-$2')
                .replace(/(-\d{4})\d+?$/, '$1');
        },
        salario: function(value) {
            value = value.replace(/\D/g, '');
            if (!value) return '';
            const number = parseInt(value) / 100;
            return number.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }
    };

    // Aplicar máscaras conforme os campos existentes
    const cpfCnpjInput = document.getElementById('cpf_cnpj');
    if (cpfCnpjInput) {
        cpfCnpjInput.addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, '');
            e.target.value = value.length <= 11 ? masks.cpf_cnpj(value) : masks.cnpj(value);
        });
    }

    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            e.target.value = masks.cep(e.target.value);
        });
    }

    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            e.target.value = masks.telefone(e.target.value);
        });
    }

    const salarioInput = document.getElementById('salario');
    if (salarioInput) {
        salarioInput.addEventListener('input', function(e) {
            e.target.value = masks.salario(e.target.value);
        });
        
        salarioInput.addEventListener('focus', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }

    // Validação de CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        
        let resto = soma % 11;
        let digito1 = resto < 2 ? 0 : 11 - resto;
        
        if (digito1 !== parseInt(cpf.charAt(9))) return false;
        
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        
        resto = soma % 11;
        let digito2 = resto < 2 ? 0 : 11 - resto;
        
        return digito2 === parseInt(cpf.charAt(10));
    }

    // Validação de CNPJ
    function validarCNPJ(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');
        if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
        
        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        
        let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado !== parseInt(digitos.charAt(0))) return false;
        
        tamanho += 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        return resultado === parseInt(digitos.charAt(1));
    }

    // Validação de CEP
    function validarCEP(cep) {
        return cep.replace(/\D/g, '').length === 8;
    }

    // Validação de data de nascimento (mínimo 18 anos)
    function validarDataNascimento(data) {
        if (!data) return false;
        const nascimento = new Date(data);
        const hoje = new Date();
        const idade = hoje.getFullYear() - nascimento.getFullYear();
        
        const mes = hoje.getMonth() - nascimento.getMonth();
        if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
            return idade - 1 >= 18;
        }
        
        return idade >= 18;
    }

    // Buscar endereço pelo CEP
    const buscarCepBtn = document.getElementById('buscarCep');
    if (buscarCepBtn) {
        buscarCepBtn.addEventListener('click', function() {
            const cepInput = document.getElementById('cep');
            if (!cepInput) return;
            
            const cep = cepInput.value.replace(/\D/g, '');
            
            if (!validarCEP(cep)) {
                mostrarAlerta('CEP inválido. Digite 8 dígitos.', 'erro');
                return;
            }
            
            // Mostrar loading
            const originalHTML = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...';
            this.disabled = true;
            
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        mostrarAlerta('CEP não encontrado', 'erro');
                    } else {
                        mostrarAlerta(`Endereço encontrado:\n${data.logradouro}, ${data.bairro}\n${data.localidade} - ${data.uf}`, 'info');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    mostrarAlerta('Erro ao buscar CEP. Tente novamente.', 'erro');
                })
                .finally(() => {
                    // Restaurar botão
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                });
        });
    }

    // Validação do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const errors = [];
        
        // Validar nome
        const nome = document.getElementById('nome');
        if (nome && (nome.value.trim().length < 3 || /[0-9]/.test(nome.value))) {
            errors.push('Nome deve ter pelo menos 3 caracteres e não pode conter números');
        }
        
        // Validar CPF/CNPJ
        const cpfCnpj = document.getElementById('cpf_cnpj');
        if (cpfCnpj) {
            const cpfCnpjValue = cpfCnpj.value.replace(/\D/g, '');
            if (cpfCnpjValue.length === 11) {
                if (!validarCPF(cpfCnpjValue)) {
                    errors.push('CPF inválido');
                }
            } else if (cpfCnpjValue.length === 14) {
                if (!validarCNPJ(cpfCnpjValue)) {
                    errors.push('CNPJ inválido');
                }
            } else {
                errors.push('CPF/CNPJ inválido');
            }
        }
        
        // Validar salário (se existir)
        const salario = document.getElementById('salario');
        if (salario) {
            const salarioClean = salario.value.replace(/\D/g, '');
            if (salarioClean) {
                const salarioValue = parseFloat(salarioClean) / 100;
                if (salarioValue < 1320) {
                    errors.push('Salário deve ser pelo menos um salário mínimo (R$ 1.320,00)');
                }
            } else {
                errors.push('Salário é obrigatório');
            }
        }
        
        // Validar CEP
        const cep = document.getElementById('cep');
        if (cep && !validarCEP(cep.value)) {
            errors.push('CEP inválido');
        }
        
        // Validar data de nascimento (se existir)
        const nascimento = document.getElementById('nascimento');
        if (nascimento && !validarDataNascimento(nascimento.value)) {
            errors.push('É necessário ter pelo menos 18 anos');
        }
        
        // Validar perfil (se existir)
        const perfil = document.getElementById('perfil');
        if (perfil && !perfil.value) {
            errors.push('Selecione um perfil');
        }
        
        // Validar telefone (se existir)
        const telefone = document.getElementById('telefone');
        if (telefone && telefone.value.replace(/\D/g, '').length < 10) {
            errors.push('Telefone inválido');
        }
        
        // Validar email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email.value)) {
            errors.push('E-mail inválido');
        }
        
        // Validar senha (se existir)
        const senha = document.getElementById('senha');
        const confirmarSenha = document.getElementById('confirmar_senha');
        if (senha && confirmarSenha) {
            if (senha.value.length < 6) {
                errors.push('Senha deve ter pelo menos 6 caracteres');
            }
            
            if (senha.value !== confirmarSenha.value) {
                errors.push('Senhas não coincidem');
            }
        }
        
        if (errors.length > 0) {
            mostrarAlerta('Erros no formulário:\n\n' + errors.join('\n'), 'erro');
        } else {
            // Mensagem de sucesso personalizada conforme o contexto
            const isFuncionarioForm = document.getElementById('perfil') !== null;
            const successMessage = isFuncionarioForm ? 'Funcionário cadastrado com sucesso!' : 'Cliente cadastrado com sucesso!';
            
            mostrarAlerta(successMessage, 'sucesso');
            // form.submit(); // Descomente para enviar o formulário
        }
    });
    
    console.log('Validações configuradas com sucesso');
});


// CALENDÁRIO AVANÇADO COM SELEÇÃO RÁPIDA DE ANOS
document.addEventListener('DOMContentLoaded', function() {
    const dataInput = document.getElementById('nascimento');
    if (dataInput) {
        // Criar container do calendário
        const calendarContainer = document.createElement('div');
        calendarContainer.className = 'calendar-container';
        calendarContainer.style.cssText = `
            position: relative;
            display: inline-block;
            width: 100%;
        `;
        
        dataInput.parentNode.appendChild(calendarContainer);
        calendarContainer.appendChild(dataInput);
        
        // Criar botão do calendário
        const calendarToggle = document.createElement('button');
        calendarToggle.type = 'button';
        calendarToggle.className = 'calendar-toggle btn btn-outline-secondary';
        calendarToggle.innerHTML = '<i class="bi bi-calendar"></i>';
        calendarToggle.style.cssText = `
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            font-size: 1.2rem;
            padding: 4px 8px;
            z-index: 5;
        `;
        calendarContainer.appendChild(calendarToggle);
        
        // Criar o calendário
        const calendar = document.createElement('div');
        calendar.className = 'calendar-popup';
        calendar.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 320px;
            display: none;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        `;
        calendarContainer.appendChild(calendar);
        
        // Variáveis de estado
        let currentDate = new Date();
        let selectedDate = null;
        let viewMode = 'month'; // 'month' ou 'year'
        
        // Função para gerar visualização de meses
        function generateMonthView(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            
            calendar.innerHTML = `
                <div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: linear-gradient(135deg, #6f42c1, #d63384); border-radius: 6px; color: white;">
                    <button class="prev-month btn btn-sm btn-light">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span class="calendar-month" style="font-weight: bold; cursor: pointer; padding: 5px 10px; border-radius: 4px; background: rgba(255,255,255,0.2);">
                            ${date.toLocaleString('pt-BR', { month: 'long' })}
                        </span>
                        <span class="calendar-year" style="font-weight: bold; cursor: pointer; padding: 5px 10px; border-radius: 4px; background: rgba(255,255,255,0.2);">
                            ${year}
                        </span>
                    </div>
                    <button class="next-month btn btn-sm btn-light">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="calendar-weekdays" style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-weight: bold; margin-bottom: 10px; color: #6c757d; font-size: 0.9rem;">
                    <div>D</div><div>S</div><div>T</div><div>Q</div><div>Q</div><div>S</div><div>S</div>
                </div>
                <div class="calendar-days" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;"></div>
                <div class="calendar-footer" style="margin-top: 15px; display: flex; justify-content: center;">
                    <button class="btn btn-sm btn-outline-primary today-btn">
                        <i class="bi bi-calendar-check"></i> Hoje
                    </button>
                </div>
            `;
            
            // Dias do mês
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            const daysContainer = calendar.querySelector('.calendar-days');
            
            // Dias vazios no início
            for (let i = 0; i < startingDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.style.padding = '10px';
                emptyDay.style.minHeight = '35px';
                daysContainer.appendChild(emptyDay);
            }
            
            // Dias do mês
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('button');
                dayElement.textContent = day;
                dayElement.className = 'btn btn-sm';
                dayElement.style.cssText = `
                    padding: 8px;
                    border: none;
                    border-radius: 50%;
                    width: 35px;
                    height: 35px;
                    transition: all 0.2s;
                    font-weight: 500;
                `;
                
                const currentDay = new Date(year, month, day);
                
                // Destacar dia atual
                const today = new Date();
                if (currentDay.toDateString() === today.toDateString()) {
                    dayElement.className = 'btn btn-sm btn-primary';
                }
                
                // Destacar dia selecionado
                if (selectedDate && currentDay.toDateString() === selectedDate.toDateString()) {
                    dayElement.className = 'btn btn-sm btn-success';
                }
                
                // Dias passados com estilo diferente
                if (currentDay < new Date().setHours(0,0,0,0)) {
                    dayElement.style.opacity = '0.6';
                }
                
                dayElement.addEventListener('mouseover', () => {
                    if (!dayElement.classList.contains('btn-primary') && 
                        !dayElement.classList.contains('btn-success')) {
                        dayElement.style.background = '#e9ecef';
                    }
                });
                
                dayElement.addEventListener('mouseout', () => {
                    if (!dayElement.classList.contains('btn-primary') && 
                        !dayElement.classList.contains('btn-success')) {
                        dayElement.style.background = '';
                    }
                });
                
                dayElement.addEventListener('click', () => {
                    selectedDate = currentDay;
                    dataInput.value = currentDay.toISOString().split('T')[0];
                    calendar.style.display = 'none';
                    generateMonthView(currentDate);
                });
                
                daysContainer.appendChild(dayElement);
            }
            
            // Event listeners
            calendar.querySelector('.prev-month').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setMonth(currentDate.getMonth() - 1);
                generateMonthView(currentDate);
            });
            
            calendar.querySelector('.next-month').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setMonth(currentDate.getMonth() + 1);
                generateMonthView(currentDate);
            });
            
            calendar.querySelector('.calendar-month').addEventListener('click', (e) => {
                e.stopPropagation();
                generateMonthSelection(currentDate);
            });
            
            calendar.querySelector('.calendar-year').addEventListener('click', (e) => {
                e.stopPropagation();
                generateYearSelection(currentDate);
            });
            
            calendar.querySelector('.today-btn').addEventListener('click', (e) => {
                e.stopPropagation();
                selectedDate = new Date();
                dataInput.value = selectedDate.toISOString().split('T')[0];
                currentDate = new Date();
                generateMonthView(currentDate);
            });
        }
        
        // Função para seleção de mês
        function generateMonthSelection(date) {
            const year = date.getFullYear();
            
            calendar.innerHTML = `
                <div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: linear-gradient(135deg, #6f42c1, #d63384); border-radius: 6px; color: white;">
                    <button class="prev-year btn btn-sm btn-light">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <span class="calendar-title" style="font-weight: bold;">
                        ${year}
                    </span>
                    <button class="next-year btn btn-sm btn-light">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="months-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;"></div>
            `;
            
            const monthsGrid = calendar.querySelector('.months-grid');
            const months = [
                'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
                'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
            ];
            
            months.forEach((month, index) => {
                const monthBtn = document.createElement('button');
                monthBtn.textContent = month;
                monthBtn.className = 'btn btn-outline-primary';
                monthBtn.style.cssText = `
                    padding: 10px;
                    border-radius: 6px;
                    font-weight: 500;
                    transition: all 0.2s;
                `;
                
                if (index === currentDate.getMonth() && year === currentDate.getFullYear()) {
                    monthBtn.className = 'btn btn-primary';
                }
                
                monthBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentDate.setMonth(index);
                    generateMonthView(currentDate);
                });
                
                monthsGrid.appendChild(monthBtn);
            });
            
            // Event listeners
            calendar.querySelector('.prev-year').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setFullYear(currentDate.getFullYear() - 1);
                generateMonthSelection(currentDate);
            });
            
            calendar.querySelector('.next-year').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setFullYear(currentDate.getFullYear() + 1);
                generateMonthSelection(currentDate);
            });
        }
        
        // Função para seleção rápida de ano
        function generateYearSelection(date) {
            const currentYear = date.getFullYear();
            const startYear = currentYear - 6;
            const endYear = currentYear + 5;
            
            calendar.innerHTML = `
                <div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: linear-gradient(135deg, #6f42c1, #d63384); border-radius: 6px; color: white;">
                    <button class="prev-decade btn btn-sm btn-light">
                        <i class="bi bi-chevron-double-left"></i>
                    </button>
                    <span class="calendar-title" style="font-weight: bold;">
                        ${startYear} - ${endYear}
                    </span>
                    <button class="next-decade btn btn-sm btn-light">
                        <i class="bi bi-chevron-double-right"></i>
                    </button>
                </div>
                <div class="years-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;"></div>
            `;
            
            const yearsGrid = calendar.querySelector('.years-grid');
            
            for (let year = startYear; year <= endYear; year++) {
                const yearBtn = document.createElement('button');
                yearBtn.textContent = year;
                yearBtn.className = 'btn btn-outline-secondary';
                yearBtn.style.cssText = `
                    padding: 10px;
                    border-radius: 6px;
                    font-weight: 500;
                    transition: all 0.2s;
                `;
                
                if (year === currentDate.getFullYear()) {
                    yearBtn.className = 'btn btn-primary';
                }
                
                yearBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentDate.setFullYear(year);
                    generateMonthSelection(currentDate);
                });
                
                yearsGrid.appendChild(yearBtn);
            }
            
            // Event listeners
            calendar.querySelector('.prev-decade').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setFullYear(currentDate.getFullYear() - 12);
                generateYearSelection(currentDate);
            });
            
            calendar.querySelector('.next-decade').addEventListener('click', (e) => {
                e.stopPropagation();
                currentDate.setFullYear(currentDate.getFullYear() + 12);
                generateYearSelection(currentDate);
            });
        }
        
        // Toggle do calendário
        calendarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (calendar.style.display === 'block') {
                calendar.style.display = 'none';
            } else {
                generateMonthView(currentDate);
                calendar.style.display = 'block';
                
                // Posicionamento
                const rect = calendarContainer.getBoundingClientRect();
                calendar.style.left = '0';
                calendar.style.top = 'calc(100% + 5px)';
            }
        });
        
        // Fechar calendário ao clicar fora
        document.addEventListener('click', (e) => {
            if (!calendarContainer.contains(e.target) && calendar.style.display === 'block') {
                calendar.style.display = 'none';
            }
        });
        
        // Prevenir fechamento ao clicar no próprio calendário
        calendar.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        
        // Validação da data
        dataInput.addEventListener('change', () => {
            if (dataInput.value && !validarDataNascimento(dataInput.value)) {
                mostrarAlerta('É necessário ter pelo menos 18 anos', 'erro');
                dataInput.value = '';
            }
        });
        
        console.log('Calendário avançado configurado com sucesso');
    }
});

// Adicionar estilos CSS
const calendarStyles = `
    .calendar-popup button {
        transition: all 0.2s ease-in-out !important;
    }
    
    .calendar-popup button:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .calendar-popup .btn-light:hover {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }
    
    .calendar-container:focus-within {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }
    
    @media (max-width: 576px) {
        .calendar-popup {
            width: 300px !important;
            left: 50% !important;
            transform: translateX(-50%);
            padding: 10px !important;
        }
        
        .calendar-popup button {
            padding: 6px !important;
            font-size: 0.9rem;
        }
    }
    
    .calendar-popup .months-grid,
    .calendar-popup .years-grid {
        max-height: 250px;
        overflow-y: auto;
    }
    
    /* Scrollbar personalizada */
    .calendar-popup ::-webkit-scrollbar {
        width: 6px;
    }
    
    .calendar-popup ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .calendar-popup ::-webkit-scrollbar-thumb {
        background: #6f42c1;
        border-radius: 3px;
    }
    
    .calendar-popup ::-webkit-scrollbar-thumb:hover {
        background: #5a32a3;
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = calendarStyles;
document.head.appendChild(styleSheet);


///////////////////

// Formatação do campo de CPF/CNPJ
function mascaraCPFCNPJ(){
    document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Verifica se é CPF (até 11 dígitos) ou CNPJ (mais de 11 dígitos)
        if (value.length <= 11) {
            // Formatação para CPF
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d+)/, '$1.$2');
            }
        } else {
            // Formatação para CNPJ (limita a 14 dígitos)
            if (value.length > 14) value = value.slice(0, 14);
            
            if (value.length > 12) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d+)/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/(\d{2})(\d{3})(\d+)/, '$1.$2.$3');
            }
        }
        e.target.value = value;
    });
}
function mascaraCPF_func(){
    document.getElementById('cpf_funcionario').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Verifica se é CPF (até 11 dígitos) ou CNPJ (mais de 11 dígitos)
        if (value.length <= 11) {
            // Formatação para CPF
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d+)/, '$1.$2');
            }
        } else {
            // Formatação para CNPJ (limita a 14 dígitos)
            if (value.length > 14) value = value.slice(0, 14);
            
            if (value.length > 12) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d+)/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/(\d{2})(\d{3})(\d+)/, '$1.$2.$3');
            }
        }
        e.target.value = value;
    });

}
function mascaraTelefone(){
    // Formatação do campo de telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 10) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/(\d{2})(\d+)/, '($1) $2');
        }
        e.target.value = value;
    });
}

function buscaCEP(){
    // Buscar CEP via API
    document.getElementById('buscarCep').addEventListener('click', function() {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        
        if (cep.length !== 8) {
            alert('CEP inválido! Digite um CEP com 8 dígitos.');
            return;
        }
    
        // Mostrar loading
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...';
        this.disabled = true;
        
        // Fazer requisição para a API ViaCEP
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado!');
                    return;
                }
                
                // Preencher os campos com os dados retornados
                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                alert('Erro ao buscar CEP. Tente novamente.');
            })
            .finally(() => {
                // Restaurar botão
                this.innerHTML = '<i class="bi bi-search"></i> Buscar';
                this.disabled = false;
            });
    });
    }

    function mascaraCEP(){
        // Formatação do campo de CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            
            if (value.length > 5) {
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    function mascaraSalario(){
        document.getElementById('salario').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            value = value.replace(/(\d)(\d{3}),/g, "$1.$2,");
            e.target.value = 'R$ ' + value;
        });
    }

    function mascaraPreco(){
        document.getElementById('preco').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            value = value.replace(/(\d)(\d{3}),/g, "$1.$2,");
            e.target.value = 'R$ ' + value;
        });
    }