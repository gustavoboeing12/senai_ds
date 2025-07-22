document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector('#formFuncionario');
  const nomeInput = document.querySelector('#nome');
  const cpfCnpjInput = document.querySelector('#cpfCnpj');
  const salarioInput = document.querySelector('#salario');
  const cepInput = document.querySelector('#cep');
  const idadeInput = document.querySelector('#idade');
  const emailInput = document.querySelector('#email');
  const funcaoInput = document.querySelector('#funcao');
  const dropdown = document.querySelector('.dropdown');

  // Função para exibir erro
  function marcarErro(input, condicaoInvalida, mensagem) {
    const errorMessage = input.nextElementSibling;  // Assume que a mensagem de erro está após o campo
    if (condicaoInvalida) {
        input.classList.add('erro');
      if (errorMessage) errorMessage.textContent = mensagem;
      return true;
    } else {
      input.classList.remove('erro');
      if (errorMessage) errorMessage.textContent = '';
      return false;
    }
  }

  nomeInput.addEventListener('input', function () {
    this.value = this.value.replace(/[0-9]/g, '');
    this.classList.remove('erro');
  });

  salarioInput.addEventListener('input', function () {
    let valor = this.value.replace(/\D/g, '');
    valor = (parseFloat(valor) / 100).toFixed(2);
    const valorFormatado = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
      currency: 'BRL'
    }).format(valor);
    this.value = valorFormatado;

    // Validação adicional após formatação
    if (!valorFormatado.includes('R$')) {
      this.classList.add('erro');
    } else {
      this.classList.remove('erro');
    }
  });

  cepInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9\-]/g, '');
    this.classList.remove('erro');
  });

  idadeInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
    this.classList.remove('erro');
  });

  emailInput.addEventListener('input', function () {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(this.value)) {
      this.setCustomValidity('Por favor, insira um e-mail válido.');
      this.classList.add('erro');
    } else {
      this.setCustomValidity('');
      this.classList.remove('erro');
    }
  });

  cpfCnpjInput.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length <= 11) {
         v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
      v = v.replace(/^(\d{2})(\d)/, '$1.$2');
      v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
      v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
      v = v.replace(/(\d{4})(\d)/, '$1-$2');
    }
    this.value = v;
    this.classList.remove('erro');
  });

  
       
  // Função para selecionar a função e atualizar o input
  function selecionarFuncao(funcao) {
    funcaoInput.value = funcao;  // Atualiza o campo do input
    dropdown.classList.remove('erro');  // Remove o erro se a função for válida
  }

  // Exemplo de como associar isso ao seu dropdown
  document.querySelectorAll('.dropdown-options div').forEach(function(option) {
    option.addEventListener('click', function() {
      selecionarFuncao(option.textContent);
    });
  });

  // Validação ao enviar
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let erro = false;

    erro = marcarErro(nomeInput, nomeInput.value.trim() === '', 'Nome é obrigatório.') || erro;
    erro = marcarErro(cpfCnpjInput, cpfCnpjInput.value.trim().length < 14, 'CPF ou CNPJ inválido.') || erro;
    erro = marcarErro(salarioInput, salarioInput.value.trim() === '' || !salarioInput.value.includes('R$'), 'Salário inválido.') || erro;
    erro = marcarErro(cepInput, cepInput.value.trim().length < 8, 'CEP inválido.') || erro;
    erro = marcarErro(idadeInput, idadeInput.value.trim() === '' || idadeInput.value < 11 || idadeInput.value > 100, 'Idade inválida.') || erro;
    erro = marcarErro(emailInput, !emailInput.checkValidity(), 'E-mail inválido.') || erro;

    // Ajuste na validação de funcaoInput
    erro = marcarErro(funcaoInput, funcaoInput.value.trim() === '', 'Selecione uma função.') || erro;

    if (funcaoInput.value.trim() === '') {
      dropdown.classList.add('erro');
      erro = true;
    } else {
      dropdown.classList.remove('erro');
    }
  if (!erro) {
      alert('Funcionário cadastrado com sucesso!');
      form.submit();
    }
  });
});

const formFuncionario = document.querySelector('#formFuncionario');
        const formCliente = document.querySelector('#formCliente');
        
        // Função genérica de validação (adicione os campos de cliente aqui)
        function validarFormulario(form) {
          // fazer validações aqui com base no tipo de formulário
          alert('Formulário válido!');
        
          // Se estiver tudo certo:
          return true;
        }
        
        // Exemplo para cliente:
        if (formCliente) {
          formCliente.addEventListener('submit', function (e) {
            e.preventDefault();
            const valido = validarFormulario(formCliente);
            if (valido) {
              alert('Cliente cadastrado com sucesso!');
              // Se quiser, envie: formCliente.submit();
            }
          });
        }
      
   form.addEventListener('submit', function (e) {
    let erro = false;
  
    // todas as validações...
  
    if (erro) {
      e.preventDefault(); // só bloqueia o envio se tiver erro
    } else {
      alert('Funcionário cadastrado com sucesso!');
      // envio segue normalmente
    }
  });