# Cria um balão de mensagem
import tkinter as tk
janela = tk.Tk()

# Coloca um título ao balão
janela.title("Cotação de Moedas")

# Coloca uma mensagem no balão com cores personalizaveis e a mostra
mensagem = tk.Label(text = "Sistema de busca de cotação de moedas",fg='white', bg='#7E40C8')
mensagem.pack()

# Coloca uma outra mensagem no balão com uma 'estilização' sendo 'height' = Altura e 'width' = Largura, e a mostra
mensagem2 = tk.Label(text = "Selecione a moeda desejada", height=15 , width=70)
mensagem2.pack()

# Coloca uma entrada de dados ao balão
moeda = tk.Entry()
moeda.pack()

# Deixa amostradinho balão
janela.mainloop()
