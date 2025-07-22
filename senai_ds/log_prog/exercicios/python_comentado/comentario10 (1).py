# Define uma função que obtém o último elemento de uma lista
def obter_ultimo_elemento(lista):
    # Analisa se a lista não está vazia
    if lista:
        # Retorna o último elemento da lista
        return lista[-1]
    else:
        # Retorna None se a lista estiver vazia
        return None

# Cria uma lista vazia para armazenar os elementos inseridos pelo usuário
lista = []

# Inicializa o contador para 1
i = 1

# Repetição para adicionar 5 elementos à lista
while i <= 5:
    # Pede ao usuário que insira um elemento da lista
    elem = int(input("Digite um elemento da lista: "))
    # Adiciona o elemento à lista
    lista.append(elem)
    # Incrementa o contador
    i += 1
    # Imprime a lista atual após a inserção de cada elemento
    print(lista)

# Obtém o último elemento da lista usando a função definida
ultimo_elemento = obter_ultimo_elemento(lista)

# Imprime o último elemento da lista
print("Último elemento da lista:", ultimo_elemento)
