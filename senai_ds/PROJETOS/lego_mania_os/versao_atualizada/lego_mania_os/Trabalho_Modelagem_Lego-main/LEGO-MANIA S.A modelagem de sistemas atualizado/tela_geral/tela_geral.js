document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll('.btn_menu');

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const submenu = button.nextElementSibling;
      const isActive = submenu.classList.contains('active');

      // Fecha todos os submenus
      document.querySelectorAll('.submenu').forEach(menu => {
        menu.classList.remove('active');
      });

      // Se o submenu clicado não estava aberto, abre ele
      if (!isActive) {
        submenu.classList.add('active');
      }
    });
  });
});
document.getElementById("btnvoltar").addEventListener("click", function() {
  window.location.href = "../tela_geral/tela_geral.html";
});
document.querySelectorAll('.floating-label input').forEach(input => {
  input.addEventListener('mouseenter', () => {
    document.querySelector('.custom-cursor.site-wide').style.display = 'block';
  });
  
  input.addEventListener('focus', () => {
    document.querySelector('.custom-cursor.site-wide').style.display = 'block';
  });
});