# Função para validar o nome
def validar_nome(nome):
    # Verifica se o nome tem pelo menos 2 caracteres
    if len(nome) < 2:
        print("Nome inválido! O nome deve ter pelo menos 2 caracteres.")
    else:
        print("Nome válido!")

# Testando a função
nome = input("Digite seu nome: ")
validar_nome(nome)
