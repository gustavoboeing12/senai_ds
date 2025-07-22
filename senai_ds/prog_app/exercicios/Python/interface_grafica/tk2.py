import tkinter as tk
janela = tk.Tk()


def abrir_janela():
  janela2 = tk.Toplevel()
  janela2.title("Janela nova")
  botao_fechar = tk.Button(janela2, text = "fechar", command = janela2.destroy)
  botao_fechar.grid(row = 1, column = 0)

def enviar():
  print(var_promocoes.get())
  botao_enviar = tk.Button (text="Enviar" ,command = enviar)
  botao_enviar.grid(row=1 ,column=0)
  janela.mainloop()

def enviar():
  print(var_aviao.get())
  if(var_promocoes.get()):
    print('Usuário deseja receber promoções')
  else:
    print('Usuário NÃO deseja redecer promoções')
  if var_politicas.get():
    print( 'Usuário concordou com Termos de USO ePoliticas de Privacidade')
  else:
    print('Usuário NAO concordou')

var_aviao = tk.StringVar()

botao_classeeconomica = tk.Radiobutton(text="Classe Económicа", variable=var_aviao, value ="Classe econômica" )
botao_classexecutiva = tk.Radiobutton(text="Classe Executiva", variable=var_aviao, value ="Classe executiva" )
botao_primeiraclasse = tk.Radiobutton(text = "Primeira Classe", variable=var_aviao, value ="Primeira classe" )
botao_classeeconomica.grid(row=0, column=0)
botao_classexecutiva.grid(row=0, column=1)
botao_primeiraclasse.grid(row=0, coTumn=2)

tk.mainloop()

botao_enviar = tk.Button(janela, text="Enviar", command=enviar)
botao_enviar.grid(row=2, column=0)

var_promocoes = tk.IntVar ()
checkbox_promocoes = tk.Checkbutton(text="Deseja receber e-mail promocionais?", variable = var_promocoes)
checkbox_promocoes.grid(row=0,column=0)

var_politicas = tk.IntVar ()
checkbox_politicas = tk.Checkbutton(text="Aceitar termos de Uso e Politicas de Privacidade", variable = var_politicas)
checkbox_politicas.grid(row=1, column=0)

botao = tk.Button(janela, text = "Ir para outrra janela", command = abrir_janela)
botao.grid(row = 2, column = 3)

janela.mainloop()