document.addEventListener('DOMContentLoaded', function() {
document.getElementById('modalCartao').style.display = 'none';
document.getElementById('modalPix').style.display = 'none';
document.getElementById('modalDinheiro').style.display = 'none';

  // Elementos do modal

  const modalCartao = document.getElementById('modalCartao');
  const btnCartao = document.getElementById('btnCartao');
  const closeModal = document.querySelector('.close-modal');
  const btnVoltarModal = document.querySelector('.btn-voltar-modal');
  const formCartao = document.getElementById('formCartao');
  const tipoCartao = document.getElementById('tipoCartao');
  const parcelas = document.getElementById('parcelas');
  const valorParcela = document.getElementById('valorParcela');
  const btnvoltar = document.getElementById('btnvoltar');
  const validatitular = document.getElementById('nomeTitular');
  
  // Ao clicar no cartão

  btnCartao.addEventListener('click', function() {

      // Preenche os dados da ordem no modal

      document.getElementById('numeroOrdemModal').textContent = 
          document.getElementById('numeroOrdem').value || 'OS-2023-00542';
      document.getElementById('valorTotalModal').textContent = 
          document.getElementById('valorTotal').value || 'R$ 1.250,00';
      
      // Abre o modal

      modalCartao.style.display = 'block';
  });
  
  // Fechar modal

  closeModal.addEventListener('click', function() {
      modalCartao.style.display = 'none';
  });
  
  btnVoltarModal.addEventListener('click', function() {
      modalCartao.style.display = 'none';
  });
  
  btnvoltar.addEventListener('click', function() {
      window.history.back();
  });
  
  // Calcular valor da parcela

  parcelas.addEventListener('change', function() {
      const valorTotalText = document.getElementById('valorTotal').value || 'R$ 1.250,00';
      const valorTotal = parseFloat(valorTotalText.replace('R$ ', '').replace('.', '').replace(',', '.'));
      const numParcelas = parseInt(this.value);
      const valor = (valorTotal / numParcelas).toFixed(2);
      valorParcela.value = `R$ ${valor.replace('.', ',')}`;
  });
  
  // Formulário do cartão
  formCartao.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Lógica para processar o pagamento
      alert('Pagamento com cartão processado com sucesso!');
      modalCartao.style.display = 'none';
  });
  
  // Fechar modal ao clicar fora
  window.addEventListener('click', function(e) {
      if (e.target === modalCartao) {
          modalCartao.style.display = 'none';
      }
  });
});

// DAQUI PARA BAIXO É JAVASCRIPT DO PAGAMENTO FORMA PIX //

    const modalPix = document.getElementById('modalPix');
    const btnPix = document.getElementById('btnPix');
    const closeModal = modalPix.querySelector('.close-modal');
    const btnVoltarModal = modalPix.querySelector('.btn-voltar-modal');
    const parcelas = modalPix.querySelector('#parcelas');
    const valorParcela = modalPix.querySelector('#valorParcela');
    const pagpix = modalPix.querySelector('#pagpix');
    const qrCodeContainer = document.getElementById('qrcodePix');

    // Abrir modal ao clicar no botão
    btnPix.addEventListener('click', function () {
        // Tenta obter valores de inputs externos
        const numeroOrdemInput = document.getElementById('numeroOrdem');
        const valorTotalInput = document.getElementById('valorTotal');

        // Usa os valores dos inputs, ou valores padrão se não existir
        const numeroOrdemValor = numeroOrdemInput ? numeroOrdemInput.value : 'OS-2023-00542';
        const valorTotalValor = valorTotalInput ? valorTotalInput.value : 'R$ 1.250,00';

        // Preenche os dados no modal Pix
        document.getElementById('numeroOrdemModal').textContent = numeroOrdemValor;
        document.getElementById('valorTotalModal').textContent = valorTotalValor;
        

        // Limpa QR code anterior se houver
        if (qrCodeContainer) qrCodeContainer.innerHTML = '';

        // Abre o modal
        modalPix.style.display = 'block';
    });

    // Fechar modal ao clicar no "X"
    closeModal.addEventListener('click', function () {
        modalPix.style.display = 'none';
    });

    // Fechar modal ao clicar em "Voltar"
    btnVoltarModal.addEventListener('click', function () {
        modalPix.style.display = 'none';
    });

    // Fechar ao clicar fora do conteúdo
    window.addEventListener('click', function (e) {
        if (e.target === modalPix) {
            modalPix.style.display = 'none';
        }
    });
  
    // Calcular valor da parcela ao alterar o select
    parcelas.addEventListener('change', function () {
        const valorTotalText = document.getElementById('valorTotal')?.value || 'R$ 1.250,00';
        const valorTotal = parseFloat(valorTotalText.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
        const numParcelas = parseInt(this.value);
        const valor = (valorTotal / numParcelas).toFixed(2);
        valorParcela.value = `R$ ${valor.replace('.', ',')}`;
    });

    // Geração do QR Code e confirmação do pagamento
    pagpix.addEventListener('click', function (e) {
        e.preventDefault(); // Impede o envio do formulário

        // Obter valores
        const numeroOrdem = document.getElementById('numeroOrdem')?.value || 'OS-2023-00542';
        const valorTotal = document.getElementById('valorTotal')?.value || 'R$ 1.250,00';

        // Texto do QR Code (simples)
        const qrTexto = `Pagamento via PIX\nOrdem: ${numeroOrdem}\nValor: ${valorTotal}`;

        // Limpa QR Code anterior
        if (qrCodeContainer) qrCodeContainer.innerHTML = '';

        // Gera o QR Code
        new QRCode(qrCodeContainer, {
            text: qrTexto,
            width: 200,
            height: 200,
        });
        alert('QR Code gerado com sucesso!');
    });


    // Modal de Dinheiro 


const modalDinheiro = document.createElement('div');
modalDinheiro.id = 'modalDinheiro';
modalDinheiro.className = 'modal-cartao';
modalDinheiro.innerHTML = `
    <div class="modal-content-cartao">
        <span class="close-modal">&times;</span>
        <h2 class="pag-card">Pagamento em Dinheiro</h2>
        
        <div class="info-pagamento">
            <p><strong>Ordem:</strong> <span class="numeroOrdemModalDinheiro"></span></p>
            <p><strong>Valor Total:</strong> <span class="valorTotalModalDinheiro"></span></p>
        </div>
        
        <form class="formDinheiro">
            <div class="form-group-cartao">
                <label class="tipo-dinheiro" for="valorRecebido">Valor Recebido:</label>
                <div class="input-with-symbol">
                    <span class="currency-symbol">R$</span>
                    <input class="tipo-dinheiro-input" type="text" id="valorRecebido" placeholder="0,00" required>
                </div>
            </div>
            
            <div class="form-group-cartao">
                <label class="form-group-cartao-txt" for="troco">Troco:</label>
                <div class="input-with-symbol">
                    <span class="currency-symbol">R$</span>
                    <input class="form-group-cartao-parcelas" type="text" id="troco" readonly>
                </div>
            </div>
            
            <div class="form-buttons-dinheiro">
                <button type="button" class="btn btn-voltar-modal">Voltar</button>
                <button type="submit" class="btn btn-confirmar" id="btn-confirma-dinheiro">Confirmar Pagamento</button>
            </div>
        </form>
    </div>
`;

// Adiciona o modal ao body
document.body.appendChild(modalDinheiro);

// Elementos do modal de dinheiro
const btnDinheiro = document.getElementById('btnDinheiro');
const closeModalDinheiro = modalDinheiro.querySelector('.close-modal');
const btnVoltarModalDinheiro = modalDinheiro.querySelector('.btn-voltar-modal');
const formDinheiro = modalDinheiro.querySelector('.formDinheiro');
const valorRecebido = modalDinheiro.querySelector('#valorRecebido');
const troco = modalDinheiro.querySelector('#troco');
const btnConfirmaDinheiro = modalDinheiro.querySelector('#btn-confirma-dinheiro');

// Função para formatar valor monetário
function formatarValorMonetario(valor) {
    // Remove tudo que não é dígito
    let apenasDigitos = valor.replace(/\D/g, '');
    
    // Se estiver vazio, retorna 0,00
    if (apenasDigitos === '') return '0,00';
    
    // Adiciona zeros à esquerda se necessário para ter pelo menos 3 dígitos
    while (apenasDigitos.length < 3) {
        apenasDigitos = '0' + apenasDigitos;
    }
    
    // Separa reais e centavos
    const reais = apenasDigitos.slice(0, -2);
    const centavos = apenasDigitos.slice(-2);
    
    // Formata os reais com separadores de milhar
    const reaisFormatados = reais.length > 0 ? 
        parseInt(reais).toLocaleString('pt-BR') : '0';
    
    return `${reaisFormatados},${centavos}`;
}

// Atualiza o valor do campo enquanto digita
valorRecebido.addEventListener('input', function() {
    const posicaoCursor = this.selectionStart;
    const valorDigitado = this.value;
    
    // Formata o valor
    const valorFormatado = formatarValorMonetario(valorDigitado);
    this.value = valorFormatado;
    
    // Mantém a posição do cursor
    const diferenca = valorFormatado.length - valorDigitado.length;
    this.setSelectionRange(posicaoCursor + diferenca, posicaoCursor + diferenca);
    
    // Calcula o troco
    calcularTroco();
});

// Função para calcular o troco
function calcularTroco() {
    const valorTotalText = document.getElementById('valorTotal').value || 'R$ 1.250,00';
    const valorTotal = parseFloat(valorTotalText.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
    
    const valorRecebidoText = valorRecebido.value.replace(/\./g, '').replace(',', '.');
    const valorRecebidoNum = parseFloat(valorRecebidoText) || 0;
    
    if (valorRecebidoNum >= valorTotal) {
        const trocoValor = (valorRecebidoNum - valorTotal).toFixed(2);
        troco.value = trocoValor.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    } else {
        troco.value = '0,00';
    }
}

// Abrir modal ao clicar no botão de dinheiro
btnDinheiro.addEventListener('click', function() {
    // Preenche os dados da ordem no modal
    modalDinheiro.querySelector('.numeroOrdemModalDinheiro').textContent = 
        document.getElementById('numeroOrdem').value || 'OS-2023-00542';
    modalDinheiro.querySelector('.valorTotalModalDinheiro').textContent = 
        document.getElementById('valorTotal').value || 'R$ 1.250,00';
    
    // Limpa campos
    valorRecebido.value = '0,00';
    troco.value = '0,00';
    
    // Abre o modal
    modalDinheiro.style.display = 'block';
    
    // Foca no campo de valor recebido
    setTimeout(() => {
        valorRecebido.focus();
        valorRecebido.setSelectionRange(valorRecebido.value.length, valorRecebido.value.length);
    }, 100);
});

// Fechar modal
closeModalDinheiro.addEventListener('click', function() {
    modalDinheiro.style.display = 'none';
});

btnVoltarModalDinheiro.addEventListener('click', function() {
    modalDinheiro.style.display = 'none';
});

// Formulário de dinheiro
formDinheiro.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const valorTotalText = document.getElementById('valorTotal').value || 'R$ 1.250,00';
    const valorTotal = parseFloat(valorTotalText.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
    const valorRecebidoNum = parseFloat(valorRecebido.value.replace(/\./g, '').replace(',', '.'));
    
    if (valorRecebidoNum < valorTotal) {
        alert('O valor recebido é menor que o valor total!');
        return;
    }
    
    // Lógica para processar o pagamento
    alert('Pagamento em dinheiro processado com sucesso!');
    modalDinheiro.style.display = 'none';
});

// Fechar modal ao clicar fora
window.addEventListener('click', function(e) {
    if (e.target === modalDinheiro) {
        modalDinheiro.style.display = 'none';
    }
});

