n = int(input("Digite uma quantidade de números para ser analisada: "))
print("Informe o número")
anterior = int(input())

i = 1
ordenado = 0

while (i < n) and (ordenado == 0):
   print("Informe o número")
   atual = int(input())
   i += 1
   if(atual <= ordenado):
      ordenado += 1
   anterior = atual

if(ordenado == 0):
   print("Sequência está ordenada")
else:
   print("Sequência não está ordenada")