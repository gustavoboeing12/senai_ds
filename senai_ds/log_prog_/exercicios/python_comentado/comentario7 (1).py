# Define uma função que encontra o maior e o menor valor em uma lista
def maior_menor(lista):
    # Inicializa as variáveis maior e menor com o primeiro elemento da lista
    maior = lista[0]
    menor = lista[0]
    
    # Itera sobre cada elemento na lista
    for elemento in lista:
        # Atualiza a variável maior se o elemento atual for maior
        if elemento > maior:
            maior = elemento
        # Atualiza a variável menor se o elemento atual for menor
        elif elemento < menor:
            menor = elemento
    
    # Retorna o maior e o menor valor encontrados
    return maior, menor

# Cria uma lista sem nada para armazenar os elementos
lista = []

# Inicializa o contador para 1
i = 1

# Repetição para colocar 10 elementos à lista
while i <= 10:
    # Pede ao usuário que digite um elemento da lista
    elem = int(input("Digite um elemento da lista: "))
    
    # Coloca o elemento à lista
    lista.append(elem)
    
    # Incrementa o contador
    i += 1

# Imprime a lista após a inserção dos 10 elementos
print(lista)

# Chama a função maior_menor passando a lis
