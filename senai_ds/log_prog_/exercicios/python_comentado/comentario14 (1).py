# Cria uma lista vazia para armazenar os números inseridos pelo usuário.
numeros = []

# Loop para solicitar ao usuário a entrada de 10 números inteiros.
for i in range(10):
    try:
        # Solicita ao usuário que digite um número inteiro e tenta converter a entrada para um inteiro.
        numero = int(input("Digite um número inteiro: "))
        # Adiciona o número à lista 'numeros'.
        numeros.append(numero)
    except ValueError:
        # Se ocorrer um erro de conversão (valor não é um número inteiro), imprime uma mensagem de erro.
        print("Entrada inválida!!!")

# Calcula a soma dos números na lista 'numeros'.
soma = sum(numeros)

# Calcula a média dos números na lista 'numeros'. A média é a soma dividida pelo número de elementos na lista.
# Se a lista estiver vazia, o cálculo da média pode resultar em uma divisão por zero, então você pode querer adicionar uma verificação.
media = soma / len(numeros) if numeros else 0

# Imprime a soma dos números.
print("Soma:", soma)

# Imprime a média dos números.
print("Média:", media)
