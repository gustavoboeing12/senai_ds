# O comando "int" representa números inteiros, contas de divião resultam em float, mesmo que o resultado seja inteiro

int_x = 10
int_y = 3

print("\nNúmeros inteiros:\n\n----------------")
print(int_x + int_y)
print(int_x - int_y)
print(int_x * int_y,"\n----------------\n")

# O comando "float" representa números além dos inteiros, seja operaçõe mais complexas ou números com casas decimais
# O operador "//" da a parte inteira da divisão, ou seja, 10/3 ao invés de 3,333... seria apenas 3

float_x = 1.8
float_y = 0.6

print("Números reais:\n\n----------------")

print(float_x + float_y)
print(round(float_x - float_y, 2))
print(float_x * float_y)
print(float_x / float_y)
print(10 // 3) # Vai aparecer 3 ao invés de 3,333333...
print("----------------\n")

# As strings são sequências de caracteres entre '...' ou "...".

str_x = "Hello"
str_y = "World"

print("Strings:\n\n----------------")
print(str_x + " " + str_y) # Isso é a concatenação
print("Hello" + " " + "World") # Outro jeito de fazer
print("Hello World" [0:5]) # Isso é o fatiamento, sendo o primeiro número de qual letra vai começar e o segundo onde vai terminar, no caso 5 pq se for 4 vai tirar o "o"
print("----------------\n")

import random

print("Bool (verdadeiro ou falso):\n\n----------------")

aleatorio = random.randint(1,10) # Escolhe um número entre 1 e 10

if(aleatorio >= 5):
    print("Número escolhido pela máquina:",aleatorio,"\nTrue pq é maior/igual a 5 e eu quis assim\n----------------\n")
else:
    print("Número escolhido pela máquina:",aleatorio,"\nFalse pq é menor q 5 e eu quis assim\n----------------\n")