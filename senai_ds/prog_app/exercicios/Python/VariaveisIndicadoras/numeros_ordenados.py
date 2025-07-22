n = int(input("Digite uma quantidade de números para ser analisada: "))
print("Informe o número: ")
anterior = int(input())

i = 1 # Leu um número
ordenado = True # Ordenado é a variável indicadora

while (i<n) and (ordenado):
    print("Informe o número") # informa o número para a sequência
    atual = int(input())
    i += 1 # Leu mais um número
    if (atual < anterior):
        ordenado = False # Não está ordenada
    anterior = atual  # Guarda o número atual para a próxima comparação

if(ordenado):
    print("Sequência ordenada")
else:
    print("Sequência não ordenada")