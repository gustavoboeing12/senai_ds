# Define uma função que dobra cada elemento de uma lista
def dobrar_lista(lista):
    # Cria uma nova lista sem nada para armazenar os elementos dobrados
    nova_lista = []
    
    # Itera sobre cada elemento da lista fornecida
    for elemento in lista:
        # Dobra o valor do elemento
        novo_elemento = elemento * 2
        # Adiciona o novo valor dobrado à nova lista
        nova_lista.append(novo_elemento)
    
    # Retorna a nova lista com os elementos dobrados
    return nova_lista

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

# Imprime a lista original após a inserção dos 10 elementos
print("Lista original:", lista)

# Chama a função dobrar_lista passando a lista como argumento e armazena o resultado
nova_lista = dobrar_lista(lista)

# Imprime a nova lista com os elementos dobrados
print("Nova lista:", nova_lista)
