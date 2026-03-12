// oculta.js

function toggleDescricao(btn) {
    const span = btn.parentElement.querySelector('.descricao-texto');
    if (span.classList.contains('d-none')) {
        span.classList.remove('d-none');
        btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        span.classList.add('d-none');
        btn.innerHTML = '<i class="bi bi-eye"></i>';
    }
}
