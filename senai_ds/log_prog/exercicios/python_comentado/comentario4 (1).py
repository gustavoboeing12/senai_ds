# Define a função maior3 que recebe três parâmetros: a, b e c
def maior3(a, b, c):
    # Verifica se a é maior ou igual a b e c
    if a >= b and a >= c:
        return a  # Retorna a se for o maior ou igual aos outros
    elif b >= c:
        return b  # Retorna b se for maior ou igual a c e não menor que a
    else:
        return c  # Caso contrário, retorna c

# Solicita ao usuário que insira três números inteiros
n1 = int(input("Digite um número: "))
n2 = int(input("Digite um número: "))
n3 = int(input("Digite um número: "))

# Chama a função maior3 passando os três números como argumentos e armazena o resultado
resultado = maior3(n1, n2, n3)

# Imprime o resultado, que é o maior dos três números
print(resultado)
