# Define a função que converte quilômetros para metros
def converter_quilometros_para_metros(quilometros):
    # Converte a distância de quilômetros para metros
    metros = quilometros * 1000
    return metros

# Tenta executar o código a seguir
try:
    # Solicita ao usuário que insira uma distância em quilômetros
    quilometros = float(input("Digite a distância em quilômetros: "))
    
    # Converte a distância para metros usando a função definida
    metros = converter_quilometros_para_metros(quilometros)
    
    # Imprime a distância convertida em metros
    print("metros:", metros)
    
# Captura qualquer erro de conversão de valor e exibe uma mensagem de erro
except ValueError:
    print("Entrada inválida!")


