async function verifyCode() {
  const digit1 = document.getElementById('digit1').value;
  const digit2 = document.getElementById('digit2').value;
  const digit3 = document.getElementById('digit3').value;
  const digit4 = document.getElementById('digit4').value;
  const digit5 = document.getElementById('digit5').value;
  const digit6 = document.getElementById('digit6').value;
  
  const enteredCode = digit1 + digit2 + digit3 + digit4 + digit5 + digit6;
  
  try {
      document.getElementById('verify-btn').disabled = true;
      document.getElementById('error-message').textContent = 'Verificando...';
      
      const response = await fetch('http://localhost:3000/verificar-codigo', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              email: userEmail,
              codigo: enteredCode
          })
      });
      
      // Handle HTTP errors (like 404, 500, etc.)
      if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (data.success && data.verified) {
          document.getElementById('error-message').textContent = '';
          document.getElementById('success-message').textContent = 'Código verificado com sucesso!';
          document.getElementById('password-fields').style.display = 'block';
      } else {
          document.getElementById('error-message').textContent = data.message || 'Código incorreto. Por favor, tente novamente.';
          document.getElementById('success-message').textContent = '';
          document.getElementById('verify-btn').disabled = false;
      }
  } catch (error) {
      console.error('Erro:', error);
      document.getElementById('error-message').textContent = 'Não foi possível conectar ao servidor. Verifique sua conexão ou tente novamente mais tarde.';
      document.getElementById('verify-btn').disabled = false;
  }
}


// Daqui para baixo é apenas javascript do recuperação via telefone //




