document.querySelector('.form-funcionario').addEventListener('submit', function (e) {
  e.preventDefault();

  const fornecedor = {
    id: Date.now(), // gera um ID único com base no timestamp
    nome: document.getElementById('nome').value,
    cpf_cnpj: document.getElementById('cpf').value,
    telefone: document.getElementById('Telefone').value,
    ramo: document.getElementById('salario').value,
    cep: document.getElementById('cep').value,
    email: document.getElementById('email').value,
    visible: true
  };

  // Recupera fornecedores já salvos ou cria uma lista nova
  const fornecedores = JSON.parse(localStorage.getItem('fornecedores')) || [];

  // Adiciona novo fornecedor
  fornecedores.push(fornecedor);

  // Salva de volta no localStorage
  localStorage.setItem('fornecedores', JSON.stringify(fornecedores));

  alert('Fornecedor cadastrado com sucesso!');
});

// fornecedor.js - Validação do formulário de cadastro de fornecedor

document.addEventListener('DOMContentLoaded', function() {
    // Selecionar o formulário
    const form = document.querySelector('.form-funcionario');
    
    // Selecionar os campos do formulário
    const nomeInput = document.getElementById('nome');
    const cpfCnpjInput = document.getElementById('cpf');
    const telefoneInput = document.getElementById('Telefone');
    const ramoInput = document.getElementById('salario');
    const cepInput = document.getElementById('cep');
    const emailInput = document.getElementById('email');
    
    // Aplicar máscaras e validações em tempo real
    aplicarMascaras();
    configurarValidacoesTempoReal();
    
    // ✅ ÚNICO LISTENER DE SUBMIT (remove o outro que está fora desta função)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validarFormulario()) {
            // Cadastra o fornecedor apenas se o formulário for válido
            const fornecedor = {
                id: Date.now(),
                nome: nomeInput.value,
                cpf_cnpj: cpfCnpjInput.value.replace(/\D/g, ''),
                telefone: telefoneInput.value,
                ramo: ramoInput.value,
                cep: cepInput.value,
                email: emailInput.value,
                visible: true
            };

            const fornecedores = JSON.parse(localStorage.getItem('fornecedores')) || [];
            fornecedores.push(fornecedor);
            localStorage.setItem('fornecedores', JSON.stringify(fornecedores));

            alert('Fornecedor cadastrado com sucesso!');
            form.reset();
        }
});
    
    // Função para aplicar máscaras aos campos
    function aplicarMascaras() {
        // Máscara para CPF/CNPJ com limite de caracteres
        cpfCnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Limitar o número de dígitos
            if (value.length > 14) {
                value = value.substring(0, 14);
            }
            
            if (value.length <= 11) {
                // Formato CPF: 000.000.000-00
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                // Formato CNPJ: 00.000.000/0000-00
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
        
        // Máscara para telefone: (00) 00000-0000
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = value.replace(/^(\d{2})/, '($1) ');
            }
            if (value.length > 10) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value.substring(0, 15);
        });
        
        // Máscara para CEP: 00000-000
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 5) {
                value = value.replace(/^(\d{5})/, '$1-');
            }
            
            e.target.value = value.substring(0, 9);
        });
    }
    
    // Função para configurar validações em tempo real
    function configurarValidacoesTempoReal() {
        // Validação do nome (só permite letras e espaços)
        nomeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\s]/g, '');
        });
    }
    
    // Função para validar todo o formulário
    function validarFormulario() {
        let valido = true;
        
        // Validar Nome (só letras e espaços, mínimo 2 caracteres)
        const nomeRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\s]{2,}$/;
        if (!nomeRegex.test(nomeInput.value.trim())) {
            alert('Nome do fornecedor deve conter apenas letras e espaços (mínimo 2 caracteres)!');
            nomeInput.focus();
            return false;
        }
        
        // Validar CPF/CNPJ
        const cpfCnpj = cpfCnpjInput.value.replace(/\D/g, '');
        
        // Verificar se tem a quantidade correta de dígitos
        if (cpfCnpj.length !== 11 && cpfCnpj.length !== 14) {
            alert('CPF deve ter 11 dígitos ou CNPJ deve ter 14 dígitos!');
            cpfCnpjInput.focus();
            return false;
        }
        
        if (cpfCnpj.length === 11) {
            if (!validarCPF(cpfCnpj)) {
                alert('CPF inválido!');
                cpfCnpjInput.focus();
                return false;
            }
        } else if (cpfCnpj.length === 14) {
            if (!validarCNPJ(cpfCnpj)) {
                alert('CNPJ inválido!');
                cpfCnpjInput.focus();
                return false;
            }
        }
        
        // Validar Telefone (formato (00) 00000-0000)
        if (!/^\(\d{2}\) \d{5}-\d{4}$/.test(telefoneInput.value)) {
            alert('Telefone inválido! Use o formato (00) 00000-0000');
            telefoneInput.focus();
            return false;
        }
        
        // Validar CEP (formato 00000-000)
        if (!/^\d{5}-\d{3}$/.test(cepInput.value)) {
            alert('CEP inválido! Use o formato 00000-000');
            cepInput.focus();
            return false;
        }
        
        // Validar Email
        if (!validarEmail(emailInput.value)) {
            alert('Email inválido!');
            emailInput.focus();
            return false;
        }
        
        return valido;
    }
    
    // Função para validar CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false;
        }
        
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        let digito1 = resto >= 10 ? 0 : resto;
        
        if (digito1 !== parseInt(cpf.charAt(9))) {
            return false;
        }
        
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        let digito2 = resto >= 10 ? 0 : resto;
        
        return digito2 === parseInt(cpf.charAt(10));
    }
    
    // Função para validar CNPJ
    function validarCNPJ(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');
        
        if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
            return false;
        }
        
        // Valida primeiro dígito verificador
        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado !== parseInt(digitos.charAt(0))) {
            return false;
        }
        
        // Valida segundo dígito verificador
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        return resultado === parseInt(digitos.charAt(1));
    }
    
    // Função para validar email
    function validarEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Botão Voltar
    const btnVoltar = document.getElementById('btnvoltaros');
    if (btnVoltar) {
        btnVoltar.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '../tela_geral/tela_geral.html';
        });
    }
});