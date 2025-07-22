import tkinter as tk
from tkinter import messagebox
from tkinter import ttk
from PIL import Image, ImageTk
import re
import subprocess # Adicionado para abrir o login

# Lista de jogos com informações
jogos = [
    {"nome": "Lego Marvel Super Heros:Deluxe Edition", "preco": "R$ 50,00"},
    {"nome": "Lego Batman 3: Beyond Gotham", "preco": "R$ 80,00"},
    {"nome": "Lego Star Wars:The Skywalker saga deluxe edition", "preco": "R$ 30,00"},
    {"nome": "Lego horizon adventures - digital deluxe edition", "preco": "R$ 60,00"},
    {"nome": "Lego 2K drive awesome rivals edition - versão epic", "preco": "R$ 80,00"},
    {"nome": "Lego teh hobbit", "preco": "R$ 41,00"},
    {"nome": "Lego harry potter: years 1-4 ","preco": "R$ 21,00"},
    {"nome": "Lego the lord of the rings","preco": "R$ 43,00"},
    {"nome": "Lego harrry potter years 5-7","preco": "R$ 42,00"},
    {"nome": "Lego the incredibles","preco": "R$ 49,00"},
    {"nome": "Lego batman 3: beyond gotham","preco": "R$ 29,00"},
    {"nome": "Lego Worlds","preco": "R$ 19,00"},
    {"nome": "Lego indiana jones 2: the adventure continues","preco": "R$ 32,00"},
    {"nome": "Lego star wars; the skywaler","preco": "R$ 39,00"},
    {"nome": "Lego horizon adventures","preco": "R$ 40,00"},
    {"nome": "Lego bricktales","preco": "R$ 11,00"},
    {"nome": "Lego horizon adventures - PS5","preco": "R$ 22,00"},
    {"nome": "Lego DC super - villains season pass","preco": "R$ 45,00"},
    {"nome": "Lego star wars: the skywalker saga character collection","preco": "R$ 62,00"},
    {"nome": "Lego marvel vingadores deluxe edition","preco": "R$ 38,00"},
    {"nome": "Lego city undercover","preco": "R$ 41,00"},
    {"nome": "Lego batman","preco": "R$ 15,00"},
    {"nome": "Lego batman 2:DC super heroes","preco": "R$ 17,00"},
    {"nome": "Lego jurassic world","preco": "R$ 20,00"},
    {"nome": "Lego star wars: the force awakens","preco": "R$ 21,00"},
    {"nome": "Lego marvel super heroes 2","preco": "R$ 24,00"},
    {"nome": "Lego marvel vingadores","preco": "R$ 25,00"},
    {"nome": "Lego 2K drive - Versão steam","preco": "R$ 13,00"},
    {"nome": "Lego trailmarkers","preco": "R$ 11,00"},
    {"nome": "Bundle star wars","preco": "R$ 27,00"},
    {"nome": "Lego 2k drive awesome edition - versão epic","preco": "R$ 31,00"}
]

# Lista para armazenar os jogos escolhidos pelo usuário
jogos_escolhidos = []

# Função para abrir a janela de login
def abrir_login():
    root.destroy()  # Fecha a "Loja de Jogos"
    import main_login  # Abre o painel de login

# Função para remover o último jogo do carrinho
def remover_jogo(carrinho_window):
    if jogos_escolhidos:
        jogo_removido = jogos_escolhidos.pop()
        messagebox.showinfo("Jogo Removido", f"{jogo_removido['nome']} foi removido do carrinho!")
        atualizar_carrinho(carrinho_window)
    else:
        messagebox.showwarning("Carrinho Vazio", "Não há jogos no carrinho para remover.")

# Função para adicionar o jogo à lista de jogos escolhidos
def adicionar_jogo():
    selecionado = treeview.selection()
    if selecionado:
        index = treeview.index(selecionado)
        jogo = jogos[index]
        jogos_escolhidos.append(jogo)
        messagebox.showinfo("Jogo Adicionado", f"{jogo['nome']} foi adicionado à sua lista!")

# Função para atualizar a lista de jogos no carrinho
def atualizar_carrinho(carrinho_window):
    for widget in carrinho_window.winfo_children():
        widget.destroy()

    label_carrinho = tk.Label(carrinho_window, text="Jogos no Carrinho:", font=("Fredoka One", 14), bg="#232F3E", fg="white")
    label_carrinho.pack(pady=10)
    
    total = 0
    # Caixa para todos os jogos selecionados com padding horizontal (left e right)
    frame_jogos = tk.Frame(carrinho_window, bg="white", padx=20, pady=10, width=500)  # Diminui a largura da caixa
    frame_jogos.pack(fill="both", expand=True, pady=5, padx=20)  # Garantindo que a caixa ocupe o espaço e se expanda
    
    for jogo in jogos_escolhidos:
        item = f"{jogo['nome']} - {jogo['preco']}"
        tk.Label(frame_jogos, text=item, font=("Fredoka One", 12), bg="white", fg="black").pack(anchor="center")
        preco = float(jogo['preco'].replace("R$ ", "").replace(",", "."))
        total += preco

    # Exibir o preço total
    tk.Label(carrinho_window, text=f"Total: R$ {total:.2f}", font=("Fredoka One", 14), bg="#232F3E", fg="white").pack(pady=10)

    # Botão de "Formas de Pagamento" verde e clicável, mas sem ação
    btn_pagamento = tk.Button(carrinho_window, text="Formas de Pagamento", font=("Fredoka One", 12), bg="#47bb6c", fg="white")
    btn_pagamento.pack(pady=10)

    # Botão para remover o último jogo do carrinho
    btn_remover = tk.Button(carrinho_window, text="Remover Último Jogo", font=("Fredoka One", 12), command=lambda: remover_jogo(carrinho_window), bg="#d9534f", fg="white")
    btn_remover.pack(pady=10)

def verifica_conta():
    if not jogos_escolhidos:
        messagebox.showwarning("Login necessário", "Cadastre-se para prosseguir")
        return

    # Função para mostrar o carrinho de compras
def ver_carrinho():
    carrinho_window = tk.Toplevel(root)
    carrinho_window.title("Carrinho de Compras")
    carrinho_window.geometry("600x400")
    carrinho_window.configure(bg="#232F3E")  # Definindo o fundo do carrinho como #003366
    
    atualizar_carrinho(carrinho_window)

# Configuração da janela principal
root = tk.Tk()
root.title("Lista de Jogos")
root.geometry("600x600")
fonte_padrao = ("Fredoka One", 16)
root.configure(bg="#232F3E")

# Estilo do Treeview
style = ttk.Style()
style.configure("Treeview", background="#131921", foreground="white", font=("Arial", 12), rowheight=35)
style.configure("Treeview.Heading", font=("Arial", 14, "bold"), background="#131921", foreground="black")
style.map("Treeview", background=[('selected', '#FF9900')])

# Definindo uma fonte personalizada
fonte_titulo = ("Fredoka One", 18)
fonte_detalhe = ("Arial", 12)

# Criando um frame para o título e botão de login
frame_topo = tk.Frame(root, bg="#232F3E")
frame_topo.pack(fill="x", padx=10, pady=5)

# Título com fundo azul escuro
titulo = tk.Label(root, text="Selecione um Jogo", font=fonte_titulo, fg="#f1faee", bg="#003366", background="#232F3E")
titulo.pack(pady=10)
# Botão de Entrar
btn_entrar = tk.Button(frame_topo, text="Entrar", font=("Arial", 12), bg="#457b9d", fg="white",width="9", command=abrir_login)
btn_entrar.pack(side="right")

# Criando o Treeview para mostrar os jogos
treeview = ttk.Treeview(root, columns=("Nome", "Preço"), show="headings", height=10)
treeview.column("Nome", width=400, anchor="w")
treeview.column("Preço", width=150, anchor="e")
treeview.heading("Nome", text="Nome")
treeview.heading("Preço", text="Preço")
treeview.pack(pady=10)

# Adicionando os jogos ao Treeview
for idx, jogo in enumerate(jogos):
    tag = 'even' if idx % 2 == 0 else 'odd'
    treeview.insert("", tk.END, values=(jogo["nome"], jogo["preco"]), tags=(tag,))

# Definindo uma fonte personalizada
fonte_titulo = ("Fredoka One", 18)
fonte_detalhe = ("Fredoka One", 12)


# Botão para adicionar jogo
btn_adicionar = tk.Button(root, text="Adicionar Jogo ao carrinho", font=("Fredoka one", 12), command=verifica_conta, bg="#47BB6C", fg="white", )
btn_adicionar.pack(pady=10)

# Botão para ver o carrinho
btn_ver_carrinho = tk.Button(root, text="Ver Carrinho", font=fonte_detalhe, command=verifica_conta, bg="#457b9d", fg="white")
btn_ver_carrinho.pack(pady=10)

root.mainloop()
