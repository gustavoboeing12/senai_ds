# Itera sobre os números de 1 a 100 (inclusive) usando a função range.
for numero in range(1, 101):
    # Analisa se o número é ímpar. Um número é ímpar se o resto da divisão por 2 é diferente de 0.
    if numero % 2 != 0:
        # Imprime o número ímpar, seguido de um espaço, sem quebrar a linha.
        print(numero, end=" ")
