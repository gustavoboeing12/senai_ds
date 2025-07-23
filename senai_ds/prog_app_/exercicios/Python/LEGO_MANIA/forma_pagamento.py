import tkinter as tk
from tkinter import messagebox
import random
import qrcode
from PIL import Image, ImageTk
import io

def validar_cartao(numero_cartao):
    # Remove os espaços antes de validar
    numero_cartao = numero_cartao.replace(" ", "")
    
    # Simples validação de tamanho do número do cartão
    if len(numero_cartao) == 16 and numero_cartao.isdigit():
        return True
    return False

def gerar_qr_code_pix():
    # Simula um valor Pix e gera um QR Code real
    valor_pix = f"PIX-{random.randint(100000, 999999)}"
    
    # Cria o QR Code com o valor aleatório
    qr = qrcode.QRCode(
        version=1,
        error_correction=qrcode.constants.ERROR_CORRECT_L,
        box_size=7,
        border=4,
    )
    qr.add_data(valor_pix)
    qr.make(fit=True)

    # Cria uma imagem a partir do QR Code
    img = qr.make_image(fill='black', back_color='white')
    return img

def exibir_qr_code():
    # Gera o QR Code e exibe na interface
    img = gerar_qr_code_pix()

    # Converte a imagem para um formato que o Tkinter pode exibir
    img_tk = ImageTk.PhotoImage(img)

    # Atualiza o label com a imagem do QR Code
    qr_code_label.config(image=img_tk)
    qr_code_label.image = img_tk  # Referência para não perder a imagem
    qr_code_label.pack(pady=10)
    botao_voltar.pack(pady=5)  # Exibir botão de voltar

def voltar():
    qr_code_label.pack_forget()  # Esconder QR Code
    botao_voltar.pack_forget()   # Esconder botão de voltar
    pagamento_var.set("")        # Resetar seleção de pagamento
    pix_var.set("")              # Resetar opção de Pix
    cartao_entry.delete(0, tk.END)  # Limpar entrada do cartão
    cartao_label.pack_forget()
    cartao_entry.pack_forget()    

def processar_pagamento():
    metodo = pagamento_var.get()
    
    if metodo == 'Pix':
        forma_pix = pix_var.get()
        if forma_pix == 'QR Code':
            # Gera e exibe o QR Code
            exibir_qr_code()
            messagebox.showinfo("Pagamento Pix", "QR Code gerado com sucesso! Verifique na tela.")
        elif forma_pix == 'Email':
            # Usando um email fictício para o pagamento Pix
            email_pix = "emailpix.legomania@gmail.com"
            messagebox.showinfo("Pagamento Pix", f"Pagamento via Pix para o email: {email_pix}")
    
    elif metodo in ['Cartão de Crédito', 'Cartão de Débito']:
        numero_cartao = cartao_entry.get()
        if validar_cartao(numero_cartao):
            messagebox.showinfo("Pagamento", f"Pagamento realizado com {metodo}!")
        else:
            messagebox.showerror("Erro", "Número do cartão inválido!")

# Criando a janela principal
root = tk.Tk()
root.title("Formas de Pagamento")
root.geometry("450x550")
root.config(bg="#1E1E1E")  # Fundo cinza claro para a janela principal

# Variáveis para armazenar as opções selecionadas
pagamento_var = tk.StringVar()
pix_var = tk.StringVar()

# Título centralizado
titulo = tk.Label(root, text="Escolha uma forma de pagamento", font=("Fredoka One", 16, "bold"), bg="#1E1E1E", fg="white")
titulo.pack(pady=20)

# Opções de pagamento com estilo
frame_pagamento = tk.Frame(root, bg="#1E1E1E")
frame_pagamento.pack(pady=10)

tk.Radiobutton(frame_pagamento, text="Pix", variable=pagamento_var, value="Pix", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900", selectcolor="#1E1E1E", activeforeground="#868d96", activebackground="#2e2e2e").pack(anchor='w', padx=20)
tk.Radiobutton(frame_pagamento, text="Cartão de Crédito", variable=pagamento_var, value="Cartão de Crédito", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900", selectcolor="#1E1E1E", activeforeground="#868d96", activebackground="#2e2e2e").pack(anchor='w', padx=20)
tk.Radiobutton(frame_pagamento, text="Cartão de Débito", variable=pagamento_var, value="Cartão de Débito", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900", selectcolor="#1E1E1E", activeforeground="#868d96", activebackground="#2e2e2e").pack(anchor='w', padx=20)

# Widgets de pagamento Pix com estilo
pix_frame = tk.Frame(root, bg="#1E1E1E")
pix_frame.pack(pady=10)

tk.Label(pix_frame, text="Forma de pagamento Pix:", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900").pack(anchor='w', padx=20)
tk.Radiobutton(pix_frame, text="QR Code", variable=pix_var, value="QR Code", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900", selectcolor="#1E1E1E", activeforeground="#868d96", activebackground="#2e2e2e").pack(anchor='w', padx=40)
tk.Radiobutton(pix_frame, text="Email", variable=pix_var, value="Email", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900", selectcolor="#1E1E1E", activeforeground="#868d96", activebackground="#2e2e2e").pack(anchor='w', padx=40)

# Label para exibir o QR Code gerado
qr_code_label = tk.Label(root, bg="#1E1E1E")
qr_code_label.pack(pady=20)

# Entrada para Cartão (apenas visível para Cartão de Crédito ou Débito)
cartao_label = tk.Label(root, text="Número do Cartão (16 dígitos):", font=("Fredoka One", 12), bg="#1E1E1E", fg="#FF9900",)
cartao_label.pack(pady=5)
cartao_entry = tk.Entry(root, font=("Fredoka One", 12), width=25, bd=2, relief="solid", justify="center", bg="#E0E0E0",)  # Caixa de entrada cinza claro
cartao_entry.pack(pady=10)
cartao_label.pack_forget()

def mostrar_cartao_entrada():
    if pagamento_var.get() in ['Cartão de Crédito', 'Cartão de Débito']:
        cartao_label.pack(pady=5)
        cartao_entry.pack(pady=10)
    else:
        cartao_label.pack_forget()
        cartao_entry.pack_forget()

pagamento_var.trace_add("write", lambda *args: mostrar_cartao_entrada())

# Funções para hover
def on_enter(event):
    botao.config(bg="#7adf6e")  # Altera a cor para o hover

def on_leave(event):
    botao.config(bg="#4ada3a")  # Volta à cor original

# Botão estilizado de processamento de pagamento
botao = tk.Button(root, text="Realizar Pagamento", font=("Fredoka one", 14, "bold"), bg="#7adf6e", fg="white", relief="solid", width=20, height=2, command=processar_pagamento, activebackground="#4ada3a", activeforeground="#7adf6e")
botao.pack(pady=30)

# Vinculando os eventos de hover
botao.bind("<Enter>", on_enter)
botao.bind("<Leave>", on_leave)

botao_voltar = tk.Button(root, text="Voltar", font=("Fredoka one", 12, "bold"), bg="#FF5555", fg="white", relief="solid", width=15, height=1, command=voltar)

# Iniciar a aplicação
root.mainloop()