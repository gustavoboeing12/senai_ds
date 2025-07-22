
def fatorial(n, resultado=1):
    '''
    Função que calcula o fatorial de um número inteiro n >= 0.
    
    Args:
    n (int): O número para o qual calcular o fatorial.
    resultado (int): Acumulador que armazena o resultado intermediário do cálculo. Inicialmente é 1.
    
    Returns:
    int: O fatorial de n.
    '''
    if n == 0 or n == 1:  # Caso base: o fatorial de 0 e 1 é 1.
        return resultado
    else:  # Passo recursivo: multiplicar o resultado atual pelo número n e continuar com n-1.
        return fatorial(n - 1, n * resultado)

# Função principal que solicita a entrada do usuário e imprime o resultado.
def main():
    n = int(input("Digite um número inteiro: "))  # Solicita ao usuário que digite um número inteiro.
    resultado = fatorial(n)  # Chama a função fatorial e armazena o resultado.
    print(20 * "#")  # Imprime uma linha de 20 caracteres '#' para separar a saída.
    print("Fatorial:", resultado)  # Imprime o resultado do fatorial.
    print(20 * "#")  # Imprime outra linha de 20 caracteres '#' para separar a saída.

# Chama a função principal para iniciar o programa.
main()
