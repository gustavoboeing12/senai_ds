n = float(input("Digite o número de notas: ")) 
i = 1 # cria uma variável controladora
todas = 0 # cria uma variável igual a zero, ara posteriormente ser somado ás notas
while(i <= n): # cria um loop baseado em quantas notas o usuário armazenará
    notas = float(input("Digite a nota: "))
    todas += notas # soma todas as notas em uma variável única
    i += 1

print("\n")
print("O total das notas inseridas são: ",todas)
print("A média das ",n, " notas é: ",todas / n)