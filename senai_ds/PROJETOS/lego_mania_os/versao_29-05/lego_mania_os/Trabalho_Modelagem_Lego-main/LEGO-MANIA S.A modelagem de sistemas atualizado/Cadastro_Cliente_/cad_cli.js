document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector('#formFuncionario');
  const nomeInput = document.querySelector('#nome');
  const cpfCnpjInput = document.querySelector('#cpfCnpj');
  const cepInput = document.querySelector('#cep');
  const telefoneInput = document.querySelector('#telefone');
  const emailInput = document.querySelector('#email');

  function marcarErro(input, condicaoInvalida, mensagem) {
    let spanErro = input.parentElement.querySelector('span.erro-msg');
    if (!spanErro) {
      spanErro = document.createElement('span');
      spanErro.classList.add('erro-msg');
      input.parentElement.appendChild(spanErro);
    }

    if (condicaoInvalida) {
      input.classList.add('erro');
      spanErro.textContent = mensagem;
      return true;
    } else {
      input.classList.remove('erro');
      spanErro.textContent = '';
      return false;
    }
  }

  nomeInput.addEventListener('input', function () {
    this.value = this.value.replace(/[0-9]/g, '');
    marcarErro(this, false, '');
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
    marcarErro(this, false, '');
  });

  cepInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9\-]/g, '');
    marcarErro(this, false, '');
  });

  telefoneInput.addEventListener('keydown', function (e) {
    const teclasPermitidas = [8, 9, 37, 38, 39, 40, 46];
    if (!((e.key >= '0' && e.key <= '9') || teclasPermitidas.includes(e.keyCode))) {
      e.preventDefault();
    }
  });

  telefoneInput.addEventListener('input', function () {
    let valor = this.value.replace(/\D/g, '');
    if (valor.length <= 10) {
      valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else {
      valor = valor.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    }
    this.value = valor.slice(0, 15);
    
    // Validação em tempo real
    const telefoneValido = valor.replace(/\D/g, '').length >= 10;
    marcarErro(this, !telefoneValido, 'Telefone inválido.');
  });

  emailInput.addEventListener('input', function () {
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
    marcarErro(this, !emailValido, 'E-mail inválido.');
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let erro = false;

    erro = marcarErro(nomeInput, nomeInput.value.trim() === '', 'Nome é obrigatório.') || erro;
    erro = marcarErro(cpfCnpjInput, cpfCnpjInput.value.trim().length < 14, 'CPF ou CNPJ inválido.') || erro;
    erro = marcarErro(cepInput, cepInput.value.replace(/\D/g, '').length < 8, 'CEP inválido.') || erro;
    
    const telefoneValido = telefoneInput.value.replace(/\D/g, '').length >= 10;
    erro = marcarErro(telefoneInput, !telefoneValido, 'Telefone inválido.') || erro;

    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
    erro = marcarErro(emailInput, !emailValido, 'E-mail inválido.') || erro;

    if (!erro) {
      alert('Cliente cadastrado com sucesso!');
      form.submit();
    }
  });
});