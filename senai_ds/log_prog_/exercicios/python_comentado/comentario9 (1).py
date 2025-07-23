# Define a função de ordenação por inserção (insertion sort)
def insertion_sort(lista):
    # Itera sobre cada elemento da lista a partir do segundo elemento
    for i in range(1, len(lista)):
        # Armazena o valor atual (chave) que será inserido na posição correta
        chave = lista[i]
        # Inicializa o índice j para comparar com os elementos anteriores
        j = i - 1
        # Move os elementos maiores que a chave uma posição à frente
        while j >= 0 and chave < lista[j]:
            lista[j + 1] = lista[j]
            j -= 1
        # Insere a chave na posição correta
        lista[j + 1] = chave

# Cria uma lista sem nada para armazenar os elementos inseridos pelo usuário
lista = []

# Inicializa o contador para 1
i = 1

# Repetição para adicionar 10 elementos à lista
while i <= 10:
    # Pede ao usuário que insira um elemento da lista
    elem = int(input("Digite um elemento da lista: "))
    # Adiciona o elemento à lista
    lista.append(elem)
    # Incrementa o contador
    i += 1

# Imprime a lista antes da ordenação
print("Lista antes da ordenação:", lista)

# Chama a função de ordenação por inserção passando a lista como argumento
insertion_sort(lista)

# Imprime a lista após a ordenação
print("Lista após a ordenação:", lista)
