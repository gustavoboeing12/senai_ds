# Importa bibliotecas para fazer uma lista suspenas ao invés de digitar
import tkinter as tk
from tkinter import ttk

# Cria função para buscar a cotação da moeda digitada pelo usuário, usando o dicionário como referência
def buscar_cotacao():
  moeda_preenchida = moeda.get()
  cotacao_moeda = dicionario_cotacoes.get(moeda_preenchida)
  mensagem_cotacao = tk.Label(text_ = "Cotação não encontrada")
  mensagem_cotacao.grid(now=3, column=0)
  if cotacao_moeda:
   mensagem_cotacao["text"] = f'Cotação do {moeda_preenchida} é de {cotacao_moeda} reais'

# Cria função para a caixa de texto
def buscar_cotacoes():
  texto = caixa_texto.get("1.0", tk.END)
  lista_moedas = texto.split('\n')
  mensagem_cotacoes = []
  for item in lista_moedas:
    cotacao = dicionario_cotacoes.get(item)
    if cotacao:
      mensagem_cotacoes.append(f"{item}:{cotacao}")
  mensagem4 = tk.Label(text='\n'.join(mensagem_cotacoes))
  mensagem4.grid(row=6,column=1)


   
# Cria um dicionário com as cotações de três moedas
dicionario_cotacoes = {
'Dolar': 5.47,
'Euro':6.68,
'Bitcoin':20000
}

# Cria um balão de mensagem
janela = tk.Tk()

# Coloca um título ao balão
janela.title("Cotacão de Moedas")

# Permitem que a configuração se mantenha mesmo que a tela seja aumentada pelo usuário com o mouse
janela.rowconfigure(0, weight=1)
janela.columnconfigure(0, weight=1)

# Coloca uma mensagem no balão com modificação nas linhas('row'), colunas('column'), 'columnspan'
# que permite ver se nosso label vai além de uma coluna e 'sticky' garante que não haja sobra( NSEN = norte,sul...)
mensagem = tk.Label(text="istema de Busca de Cotação de Moedas", fg='white', bg='#7E40C8')
mensagem.grid(row=0, column=0, columnspan=2, sticky="NSEN")

# Coloca uma outra mensagem no balão com também modificação nas linhas e colunas
mensagem2 = tk.Label(text = " Selecione a moeda desejada")
mensagem2.grid(row=2, column=0)

# Coloca uma terceira mensagem no balão, para adicionar a opção de mais de uma moeda por vez
mensagem3=tk.Label(text="Caso você queira pegar mais de 1 cotação mesmo tempo, digite uma moeda em cada linha")
mensagem3.grid(row=4, column=0, columnspan=2)

# Restringe o tamanho usando 'width' e 'height'
caixa_texto = tk.Text( width= 10, height=5)
caixa_texto.grid(row=5, column=0, sticky='nswe')

# Cria um espaço para digitação(retirada)
# moeda = tk.Entry()
# moeda.grid(row=1, column=1)

# Cria uma lista suspensa no objeto 'janela' e as opções oferecidas são derivadas do dicionário 'cotações'
moedas = list(dicionario_cotacoes.keys())
moeda = ttk.Combobox(janela, values=moedas)
moeda.grid(row=2, column=2)

# Cria um botão 'cotação' e comanda a função
botao = tk.Button(text="Buscar Cotação", command=buscar_cotacao)
botao.grid(row=2, column=1)

# Mostra o balão
janela.mainloop()
