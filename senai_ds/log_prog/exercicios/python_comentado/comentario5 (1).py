# Define a função mdc que calcula o Máximo Divisor Comum (MDC) de dois números usando o Algoritmo de Euclides
def mdc(a, b):
    # Caso base: se b for 0, retorna a como o MDC
    if b == 0:
        return a
    # Chama recursivamente a função mdc com os argumentos b e a % b
    else:
        return mdc(b, a % b)

# Solicita ao usuário que insira o primeiro número inteiro
num1 = int(input("Digite um número: "))

# Solicita ao usuário que insira o segundo número inteiro
num2 = int(input("Digite outro número: "))

# Calcula o MDC dos dois números fornecidos e armazena o resultado
resultado = mdc(num1, num2)

# Imprime o resultado do MDC
print("MDC:", resultado)
