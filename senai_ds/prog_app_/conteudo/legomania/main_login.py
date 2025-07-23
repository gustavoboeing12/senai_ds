import tkinter as tk
from tkinter import messagebox, ttk
import os
import webbrowser
from PIL import Image, ImageTk

# Caminho para salvar os dados
caminho_dados = os.path.join(os.path.expanduser("~"), "Downloads", "LEGO-MANIA", "__pycache__", "login.txt")

def toggle_senha():
    if entry_senha_login.cget('show') == "*":
        entry_senha_login.config(show="")
        btn_toggle_senha.config(text="👁")
    else:
        entry_senha_login.config(show="*")
        btn_toggle_senha.config(text="👁‍🗨")

def abrir_link(event):
    webbrowser.open("https://linktr.ee/legomania887")

# Configuração da janela
root = tk.Tk()
root.title("Sistema de Login")
root.geometry("700x400")
root.configure(bg="#1E1E1E")

# Frame para a imagem principal (LEGO MANIA)
frame_imagem = tk.Frame(root, width=350, height=400, bg="#D2B48C")
frame_imagem.pack(side="left", fill="both", expand=True)

# Carregar e exibir a imagem "LEGO MANIA" (Grande)
caminho_imagem = os.path.join(os.path.expanduser("~"), "Downloads", "LEGO-MANIA", "image.png")
imagem = Image.open(caminho_imagem)
imagem = imagem.resize((750, 400))  # Mantendo o tamanho grande
logo = ImageTk.PhotoImage(imagem)
label_logo = tk.Label(frame_imagem, image=logo, bg="#D2B48C")
label_logo.image = logo
label_logo.place(relx=0.4, rely=0.45, anchor="center")  # Mantendo centralizado

# Carregar e exibir a imagem "linktree.png" no canto inferior esquerdo
caminho_linktree = os.path.join(os.path.expanduser("~"), "Downloads", "LEGO-MANIA", "linktree.png")
imagem_linktree = Image.open(caminho_linktree)
imagem_linktree = imagem_linktree.resize((40, 40))  # Pequena no canto
logo_linktree = ImageTk.PhotoImage(imagem_linktree)
label_linktree = tk.Label(frame_imagem, image=logo_linktree, bg="#D2B48C", cursor="hand2")
label_linktree.image = logo_linktree
label_linktree.place(relx=0.05, rely=0.9, anchor="w")  # Posição no canto inferior esquerdo

# Adicionar evento de clique para abrir o link
label_linktree.bind("<Button-1>", abrir_link)

# Frame para login
frame_login = tk.Frame(root, width=350, height=400, bg="#2E2E2E")
frame_login.pack(side="right", fill="both", expand=True)

# Título
label_titulo = tk.Label(frame_login, text="Sistema de Login", fg="white", bg="#2E2E2E", font=("Fredoka One", 16))
label_titulo.pack(pady=20)

# Campos de entrada
label_usuario = tk.Label(frame_login, text="Nome de usuário", fg="lightgray", bg="#2E2E2E", font=("Fredoka One", 10))
label_usuario.pack(anchor="w", padx=40)
entry_usuario_login = ttk.Entry(frame_login, font=("Fredoka One", 12))
entry_usuario_login.pack(padx=40, pady=5, fill="x")

label_senha = tk.Label(frame_login, text="Senha de usuário", fg="lightgray", bg="#2E2E2E", font=("Fredoka One", 10))
label_senha.pack(anchor="w", padx=40)

frame_senha = tk.Frame(frame_login, bg="#2E2E2E")
frame_senha.pack(padx=40, pady=5, fill="x")
entry_senha_login = ttk.Entry(frame_senha, show="*", font=("Fredoka One", 12))
entry_senha_login.pack(side="left", expand=True, fill="x")

btn_toggle_senha = tk.Button(frame_senha, text="👁‍🗨", command=toggle_senha, font=("Fredoka One", 10), bg="#2E2E2E", fg="white", relief="flat")
btn_toggle_senha.pack(side="right")

# Botão de login
btn_login = tk.Button(frame_login, text="LOGIN", font=("Fredoka One", 12), bg="#091f4c", fg="#1d60e8", width=15, height=2, relief="flat")
btn_login.pack(pady=20)

# Botão de cadastro
btn_cadastro = tk.Button(frame_login, text="CADASTRAR", font=("Fredoka One", 12), bg="#091f4c", fg="#1d60e8", width=15, height=2, relief="flat")
btn_cadastro.pack(pady=10)

root.mainloop()
