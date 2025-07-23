# Aqui veremos como arredondar números



# 1 - Arredondamento padrão com round():
# A função round() arredonda para o inteiro mais próximo. Se o número terminar em .5, ele arredonda para o número par mais próximo.

print(round(3.2))  # Saída: 3
print(round(3.8))  # Saída: 4
print(round(3.5))  # Saída: 4 (arredonda para o número par mais próximo)
print(round(2.5))  # Saída: 2



# 2 - Arredondar para um número específico de casas decimais:

print(round(3.14159, 2))  # Saída: 3.14
print(round(3.14159, 3))  # Saída: 3.142



# 3 - Arredondamento para cima (math.ceil) e para baixo (math.floor):
# Se precisar arredondar sempre para cima ou para baixo, use math.ceil() e math.floor():

import math

print(math.ceil(3.2))  # Saída: 4 (arredonda para cima)
print(math.floor(3.8)) # Saída: 3 (arredonda para baixo)



# 4 - Truncar (cortar as casas decimais)
# Se quiser apenas remover as casas decimais sem arredondar:

import math

print(math.trunc(3.9))  # Saída: 3
print(math.trunc(-3.9)) # Saída: -3



# 5 - Aqui não tá arredondando, mas está limitando a quantidade de casas decimais para 2

pi = 3.141592653589793
print(f'O valor de pi formatado é {pi:.2f}')