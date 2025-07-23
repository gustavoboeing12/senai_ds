n = int(input("Digite uma quantidade de números para ser analisada: "))
print("Informe o número: ")
anterior = int(input())

ordenado = True # Ordenado é a variável indicadora

for i in range(n-1):
    print("Informe o número") # informa o número para a sequência
    atual = int(input())
    if (atual < anterior):
        ordenado = False # Não está ordenada
        break
    anterior = atual  # Guarda o número atual para a próxima comparação

if(ordenado):
    print("Sequência ordenada")
else:
    print("Sequência não ordenada")