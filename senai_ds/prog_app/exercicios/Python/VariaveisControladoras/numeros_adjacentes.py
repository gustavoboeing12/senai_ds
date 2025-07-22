n = int(input("Digite uma quantidade de números para ser analisada: "))
print("Informe o número: ")
anterior = int(input())

i = 1 # Leu um número
adjacente = True # adjacente é a variável indicadora

while (i<n):
    print("Informe o número") # informa o número para a sequência
    atual = int(input())
    i += 1 # Leu mais um número
    if (atual == anterior):
        adjacente = False # Tem números adjacentes iguais
   
if(adjacente):
    print("Sem nenhum números iguais adjacentes")
else:
    print("Tem números iguais adjacentes")
   