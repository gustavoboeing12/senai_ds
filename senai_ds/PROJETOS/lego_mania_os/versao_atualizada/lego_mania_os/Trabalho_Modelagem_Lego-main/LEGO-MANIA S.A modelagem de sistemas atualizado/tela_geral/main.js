document.addEventListener('DOMContentLoaded', function () {
  function atualizarHorario() {
    const elementoHorario = document.querySelector('.horario');
    if (!elementoHorario) return;

    const agora = new Date();

    const dia = String(agora.getDate()).padStart(2, '0');
    const mes = String(agora.getMonth() + 1).padStart(2, '0');
    const ano = agora.getFullYear();

    const horas = String(agora.getHours()).padStart(2, '0');
    const minutos = String(agora.getMinutes()).padStart(2, '0');
    const segundos = String(agora.getSeconds()).padStart(2, '0');

    elementoHorario.innerHTML = `<p>Data: ${dia}/${mes}/${ano} <br>
    Hora: ${horas}:${minutos}:${segundos}</p>`;
  }

  atualizarHorario();
  setInterval(atualizarHorario, 1000);
});

document.addEventListener('DOMContentLoaded', () => {
  const iconeOlho   = document.getElementById('icone-olho');
  const senhaTexto  = document.getElementById('senhaTexto');

  const senhaReal      = 'admin123';   // defina aqui a senha real da sua aplicação
  const senhaMascarada = '••••••';     // Definir uma senha para apresentar

  iconeOlho.addEventListener('click', () => {
    const icon = iconeOlho.querySelector('i');
    if (icon.classList.contains('fa-eye')) {
      // mostrar senha
      senhaTexto.textContent = senhaReal;
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      // esconder senha
      senhaTexto.textContent = senhaMascarada;
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {
    // Cursor personalizado
    const cursor = document.querySelector('.custom-cursor');
    const pointer = document.querySelector('.pointer');
    const customPointer = document.querySelector('.custom-pointer');
  
    const interactiveElements = [
      'a', 'button', 'input', 'textarea', 'select',
      '[onclick]', '[role=button]', 'label[for]'
    ];
  
    document.addEventListener('mousemove', function (e) {
      if (cursor) {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
      }
    });
  
    document.querySelectorAll(interactiveElements.join(',')).forEach(el => {
      el.addEventListener('mouseenter', function () {
        if (pointer) pointer.style.display = 'none';
        if (customPointer) customPointer.style.display = 'block';
      });
  
      el.addEventListener('mouseleave', function () {
        if (customPointer) customPointer.style.display = 'none';
        if (pointer) pointer.style.display = 'block';
      });
    });
  
    document.addEventListener('mouseout', function (e) {
      if (!e.relatedTarget) {
        if (customPointer) customPointer.style.display = 'none';
        if (pointer) pointer.style.display = 'block';
      }
    });
    
});
document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#data", {
      dateFormat: "d/m/Y",
      locale: "pt"
    });
  });
 
  document.querySelectorAll('.dropdown-options div').forEach(function (option) {
    option.addEventListener('click', function () {
      if (funcaoInput && dropdown) {
        funcaoInput.value = option.textContent;
        dropdown.classList.remove('erro');
      }
    });
  });
    
    if (typeof flatpickr !== 'undefined' && document.getElementById("data-recebimento")) {
      flatpickr("#data-recebimento", {
        dateFormat: "d/m/Y",
        locale: "pt",
        allowInput: true,
        clickOpens: true,
      });
    }
  
    // Botões e redirecionamentos

    const conferirOS = document.getElementById("conferir");
    const voltarOS = document.getElementById("btnvoltaros");
    const graficopizza = document.getElementById("btnpizza");
    const graficobarra = document.getElementById("btnbarra");
    const perfil = document.getElementById("btnperfil");
    const senhaTexto = document.getElementById('senhaTexto');
    const iconeOlho = document.querySelector('.btn-olho i');
    const inputFoto = document.getElementById('inputFoto');
    const fotoUsuario = document.getElementById('fotoUsuario');

    if(inputFoto)
      inputFoto.addEventListener('change', TrocaFoto)
  
    if (conferirOS) conferirOS.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = '../Ordem_Servico/ordem_abertas.html';
    });
  
    if (voltarOS) {
      voltarOS.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Verifica se há histórico de navegação (mais que 1 página no histórico)
        if (window.history.length > 1) {
          window.history.back(); // Volta para a página anterior
        } else {
          window.location.href = '../tela_geral/tela_geral.html'; // Fallback
        }
      });
    }
  
    if (graficopizza) graficopizza.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = '../Requisito_pecas/Pecas_requisitadas_pizza.html';
    });
  
    if (graficobarra) graficobarra.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = '../Requisito_pecas/Pecas_requisitadas_barra.html';
    });
  
    if (perfil) perfil.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = '../Perfil_Usuário/Perfil_Usuário.html';
    });

    if (iconeOlho) {
      iconeOlho.addEventListener('click', mostrarSenha);
    }
  
    if (conferirOS) {
      conferirOS.addEventListener('click', AbrirOS);
    }
        
  function toggleDropdown() {
    const dropdown = document.getElementById('funcao-dropdown');
    if (dropdown) dropdown.classList.toggle('open');

    if(perfil) {
        perfil.addEventListener('click', abrirperfil);
    }
    
    function AbrirOS(event) {
        event.preventDefault();
        window.location.href = '../Ordem_Servico/ordem_abertas.html';
    }
    
    function VoltarOS(event) {
        event.preventDefault();
        window.location.href = '../tela_geral/tela_geral.html';
    }
    
    function abrirpizza(event) {
        event.preventDefault();
        window.location.href = '../Requisito_pecas/Pecas_requisitadas_pizza.html';
    }
	
	function abrirbarra(event) {
		event.preventDefault();
		window.location.href = '../Requisito_pecas/Pecas_requisitadas_barra.html';
	}
    function abrirperfil(event){
        event.preventDefault();
        window.location.href = '../Perfil_Usuário/Perfil_Usuário.html';
    }
 function mostrarSenha() {
  if (senhaTexto.textContent === 'BANANA') {
    senhaTexto.textContent = 'teste123';
    iconeOlho.classList.remove('fa-eye');
    iconeOlho.classList.toggle('fa-eye-slash'); // Alterna entre os ícones
  } 
  else {
    senhaTexto.textContent = 'admin123'; // Esconde a senha
    iconeOlho.classList.remove('fa-eye-slash'); // Remove o ícone de olho fechado
    iconeOlho.classList.add('fa-eye'); // Adiciona o ícone de olho aberto
  }
}
  }
// Adicionando evento ao botão corretamente

  function selecionarFuncao(valor) {
    const funcaoSelecionada = document.getElementById('funcaoSelecionada');
    const funcao = document.getElementById('funcao');
    if (funcaoSelecionada) funcaoSelecionada.innerText = valor;
    if (funcao) funcao.value = valor;
    toggleDropdown();
  }

   function TrocaFoto(e) {
    if (e.target.files && e.target.files[0]) {
      file = e.target.files[0];
      
      // Validações
      if (!file.type.match('image.*')) {
          alert('Por favor, selecione uma imagem válida (JPEG, PNG, etc.)');
          return;
      }
      
      if (file.size > 2 * 1024 * 1024) { // 2MB
          alert('A imagem deve ter menos de 2MB');
          return;
      }
      
      // Criar preview da imagem
      const reader = new FileReader();
      
      reader.onload = function(event) {
          fotoUsuario.src = event.target.result;
          
          // Aqui você pode chamar a função para enviar ao servidor
          // uploadFotoPerfil(file);
      };
      
      reader.readAsDataURL(file);
  }
};
async function uploadFotoPerfil(file) {
  const formData = new FormData();
  formData.append('fotoPerfil', file);
  
  try {
      const response = await fetch('/upload-foto-perfil', {
          method: 'POST',
          body: formData
      });
      
      const data = await response.json();
      
      if (!response.ok) {
          throw new Error(data.message || 'Erro ao atualizar foto');
      }
      
      console.log('Foto atualizada com sucesso:', data);
  } catch (error) {
      console.error('Erro:', error);
      alert('Erro ao enviar foto: ' + error.message);
  }
}

 
  
  let informações = [
    {
    Nome_usuario: 'bananinhas123',
    Senha: 'admin123',
    Telefone: '47 98432-9882'
  }
]