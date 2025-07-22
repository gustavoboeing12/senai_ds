document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o recarregamento
    
    const usuario = document.getElementById('usuario').value;
    const senha = document.getElementById('senha').value;
    
    if(usuario === 'admin123' && senha === 'admin123') {
      alert('Login efetuado com sucesso!');
      window.location.href = '../tela_geral/tela_geral.html';
    } else {
      alert('Usuário ou senha incorretos!');
      // Limpa os campos (opcional)
      document.getElementById('usuario').value = '';
      document.getElementById('senha').value = '';
      document.getElementById('usuario').focus();
    }
  });

