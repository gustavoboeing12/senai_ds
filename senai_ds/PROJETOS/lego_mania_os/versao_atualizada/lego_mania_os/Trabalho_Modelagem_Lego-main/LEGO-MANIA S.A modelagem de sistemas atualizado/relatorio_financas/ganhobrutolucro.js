// Seleciona o canvas e obtém o contexto 2D para desenhar
const canvas = document.getElementById('grafico');
const ctx = canvas.getContext('2d');

// Dados das categorias e três linhas (simulando o gráfico da imagem)
const labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
const linha1 = [6500, 7600, 5000, 3000, 4000, 2000, 3500, 2500]; // Azul claro
const linha2 = [1000, 2800, 2500, 3100, 2800, 4500, 4200, 5000]; // Azul escuro
const linha3 = [5200, 6000, 4000, 2700, 2500, 1800, 1300, 900];  // Laranja

// Tamanhos e margens do gráfico
const largura = canvas.width;
const altura = canvas.height;
const margem = 60;
const maxValor = 8000; // valor máximo do eixo Y

// Função para desenhar os eixos X e Y
function desenharEixos() {
  ctx.beginPath();
  ctx.moveTo(margem, margem); // eixo Y
  ctx.lineTo(margem, altura - margem); // até base
  ctx.lineTo(largura - margem, altura - margem); // eixo X
  ctx.strokeStyle = '#000'; // cor preta
  ctx.stroke();

  // Linhas horizontais com valores
  for (let i = 0; i <= 8; i++) {
    const y = margem + (altura - 2 * margem) * i / 8;
    const valor = maxValor - i * 1000;

    // Escreve os valores no eixo Y
    ctx.fillText(valor, 10, y + 5);

    // Linhas horizontais de grade
    ctx.beginPath();
    ctx.moveTo(margem - 5, y);
    ctx.lineTo(largura - margem, y);
    ctx.strokeStyle = 'white'; // cinza claro
    ctx.stroke();
  }

  // Labels das categorias no eixo X
  const espacamentoX = (largura - 2 * margem) / (labels.length - 1);
  labels.forEach((label, i) => {
    const x = margem + i * espacamentoX;
    ctx.fillText(label, x - 5, altura - margem + 20); // posiciona abaixo do eixo X
  });
}

// Converte o valor Y dos dados para coordenada do canvas
function converterParaY(valor) {
  const escala = (altura - 2 * margem) / maxValor;
  return altura - margem - valor * escala;
}

// Desenha uma linha de dados no gráfico com uma cor específica
function desenharLinha(dados, cor) {
  const espacamentoX = (largura - 2 * margem) / (dados.length - 1);

  ctx.beginPath();
  dados.forEach((valor, i) => {
    const x = margem + i * espacamentoX;
    const y = converterParaY(valor);

    if (i === 0) ctx.moveTo(x, y); // começa no primeiro ponto
    else ctx.lineTo(x, y);         // liga aos próximos
  });

  ctx.strokeStyle = cor;
  ctx.lineWidth = 2;
  ctx.stroke();
}

// Chamada das funções para desenhar o gráfico completo
desenharEixos();                      // desenha os eixos
desenharLinha(linha1, 'cyan');       // desenha a linha 1
desenharLinha(linha2, 'blue');       // desenha a linha 2
desenharLinha(linha3, 'orange');     // desenha a linha 3