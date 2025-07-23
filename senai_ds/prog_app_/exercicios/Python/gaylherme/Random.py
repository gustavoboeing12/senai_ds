# Aqui veremos o atributo ou sla Random, q é aleatório pra qm n entende
import random

print("\n")

# 1 - Número inteiro aleatório dentro de um intervalo
# Use random.randint(inicio, fim) para gerar um número inteiro entre inicio e fim (ambos inclusivos).

numero = random.randint(1, 100)  # Escolhe um número entre 1 e 100
print("1:",numero)



# 2 - Número decimal aleatório entre 0 e 1:

import random
numero = random.random()  # Gera um número decimal entre 0 e 1
print("2:",round(numero, 2))



# 3 - Número aleatório em ponto flutuante (float):
# Use random.uniform(inicio, fim) para gerar um número decimal dentro de um intervalo.

numero = random.uniform(1.5, 5.5)  # Escolhe um número entre 1.5 e 5.5
print("3:",round(numero, 2)) # Tá arredondado pra 2 casas após a vírgula



# 4 - Escolher um número aleatório de uma lista (Funciona com strings tbm):

numeros = [10, 20, 30, 40, 50]
lista = random.choice(numeros)  # Escolhe um número aleatório da lista
print("4:",lista)



# 5 - Embaralhar uma lista:
# Use random.shuffle(lista) para embaralhar os elementos de uma lista.

cartas = [1, 2, 3, 4, 5]
random.shuffle(cartas)
print("5:",cartas)



# 6 - Gerar uma sequência de números aleatórios (exemplo: 5 números entre 1 e 100):

numeros = random.sample(range(1, 101), 5)  # Gera uma lista com 5 números aleatórios únicos
print("6:",numeros,"\n")