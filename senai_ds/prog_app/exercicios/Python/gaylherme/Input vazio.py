# Aqui veremos oque fazer quando o input estiver vazio



# 1 - Usando um loop while

while True:
    nome = input("Digite seu nome: ").strip()  # Remove espaços extras
    if nome:  # Verifica se a entrada não está vazia
        break
    print("Erro: Você precisa digitar algo!")

print(f"Bem-vindo, {nome}!")



# 2 - Usando um loop e função

def obter_input(mensagem):
    while True:
        entrada = input(mensagem).strip()
        if entrada:
            return entrada
        print("Erro: Entrada vazia. Tente novamente!")

nome = obter_input("Digite seu nome: ")
print(f"Olá, {nome}!")



#3 - Usando operador ternário
# Se quiser apenas exibir uma mensagem sem repetir a pergunta:

nome = input("Digite seu nome: ").strip()
print("Nome inválido!" if not nome else f"Olá, {nome}!")