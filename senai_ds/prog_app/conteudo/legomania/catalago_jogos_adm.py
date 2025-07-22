import tkinter as tk
from tkinter import messagebox, simpledialog
from tkinter import ttk

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
    {"nome": "Lego harry potter years 5-7","preco": "R$ 42,00"},
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

# Função para atualizar a lista de jogos no Treeview
def atualizar_lista_jogos():
    for item in treeview.get_children():
        treeview.delete(item)
    
    for idx, jogo in enumerate(jogos):
        tag = 'even' if idx % 2 == 0 else 'odd'
        treeview.insert("", tk.END, values=(jogo["nome"], jogo["preco"]), tags=(tag,))

# Função para adicionar o jogo à lista de jogos escolhidos
def adicionar_jogo():
    selecionado = treeview.selection()
    if selecionado:
        index = treeview.index(selecionado)
        jogo = jogos[index]
        
        # Adiciona o jogo à lista
        jogos_escolhidos.append(jogo)
        messagebox.showinfo("Jogo Adicionado", f"{jogo['nome']} foi adicionado à sua lista!")

# Função para editar o preço de um jogo
def editar_preco(jogo):
    novo_preco = simpledialog.askstring("Editar Preço", f"Digite o novo preço para o jogo '{jogo['nome']}':", parent=root)
    if novo_preco:
        jogo['preco'] = novo_preco
        atualizar_lista_jogos()
        messagebox.showinfo("Preço Atualizado", f"O preço do jogo '{jogo['nome']}' foi atualizado para {novo_preco}.")

# Função de "Editar" (menu de opções)
def exibir_menu(event):
    selecionado = treeview.selection()
    if selecionado:
        index = treeview.index(selecionado)
        jogo = jogos[index]

        # Criar o menu de contexto (popup menu)
        menu = tk.Menu(root, tearoff=0, bg="#333", fg="white")
        menu.add_command(label="Excluir Jogo", command=lambda: excluir_jogo(jogo), background="#d9534f", activebackground="#c9302c")
        menu.add_command(label="Editar Preço", command=lambda: editar_preco(jogo), background="#0275d8", activebackground="#004085")
        menu.add_separator()
        menu.add_command(label="Adicionar Novo Jogo", command=adicionar_novo_jogo, background="#5bc0de", activebackground="#31b0d5")
        menu.add_command(label="Padrão", command=resetar_para_padrao, background="#5bc0de", activebackground="#31b0d5")
        
        menu.post(event.x_root, event.y_root)

# Função para excluir um jogo
def excluir_jogo(jogo):
    resultado = messagebox.askyesno("Confirmar Exclusão", f"Você tem certeza que deseja excluir o jogo '{jogo['nome']}'?")
    if resultado:
        jogos.remove(jogo)
        atualizar_lista_jogos()
        messagebox.showinfo("Jogo Excluído", f"O jogo '{jogo['nome']}' foi excluído com sucesso!")

# Função para adicionar um novo jogo à lista
def adicionar_novo_jogo():
    nome = simpledialog.askstring("Nome do Jogo", "Digite o nome do novo jogo:", parent=root)
    preco = simpledialog.askstring("Preço do Jogo", "Digite o preço do novo jogo:", parent=root)
    if nome and preco:
        jogos.append({"nome": nome, "preco": preco})
        atualizar_lista_jogos()
        messagebox.showinfo("Novo Jogo Adicionado", f"O jogo '{nome}' foi adicionado com sucesso!")

# Função para resetar os jogos para a lista original
def resetar_para_padrao():
    global jogos
    jogos = [
        {"nome": "Lego Marvel Super Heros:Deluxe Edition", "preco": "R$ 50,00"},
        {"nome": "Lego Batman 3: Beyond Gotham", "preco": "R$ 80,00"},
        {"nome": "Lego Star Wars:The Skywalker saga deluxe edition", "preco": "R$ 30,00"},
        {"nome": "Lego horizon adventures - digital deluxe edition", "preco": "R$ 60,00"},
        {"nome": "Lego 2K drive awesome rivals edition - versão epic", "preco": "R$ 80,00"},
        {"nome": "Lego the hobbit", "preco": "R$ 41,00"},
        {"nome": "Lego harry potter: years 1-4 ","preco": "R$ 21,00"},
        {"nome": "Lego the lord of the rings","preco": "R$ 43,00"},
        {"nome": "Lego harry potter years 5-7","preco": "R$ 42,00"},
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
    atualizar_lista_jogos()

# Função principal
root = tk.Tk()
root.title("Lista de Jogos")
root.geometry("700x600")
root.configure(bg="#232F3E")

# Definindo uma fonte personalizada
fonte_titulo = ("Fredoka One", 18)
fonte_detalhe = ("Arial", 12)

# Título com fundo azul escuro
titulo = tk.Label(root, text="Selecione um Jogo", font=fonte_titulo, fg="#f1faee", bg="#003366", background="#232F3E")
titulo.pack(pady=10)

# Estilo do Treeview
style = ttk.Style()
style.configure("Treeview", background="#131921", foreground="white", font=("Arial", 12), rowheight=35)
style.configure("Treeview.Heading", font=("Arial", 14, "bold"), background="#131921", foreground="black")
style.map("Treeview", background=[('selected', '#FF9900')])

# Treeview para exibir a lista de jogos
treeview = ttk.Treeview(root, columns=("Nome", "Preço"), show="headings", selectmode="browse")
treeview.heading("Nome", text="Nome", anchor="center")
treeview.heading("Preço", text="Preço", anchor="center")

# Centralizando o texto nas colunas
treeview.column("Nome", anchor="center", width=450)
treeview.column("Preço", anchor="center", width=150)

treeview.pack(fill=tk.BOTH, expand=True, padx=20, pady=20)

# Inserir os jogos no Treeview
atualizar_lista_jogos()

# Menu de contexto
treeview.bind("<Button-3>", exibir_menu)

root.mainloop()
