n = int(input("Digite um número inteiro positivo: "))
numero = 2  # Começa a verificar divisores a partir de 2
divisores = 0  # Começa a contar os divisores

while (numero <= n-1):
    if(n % numero == 0):  # Se n for divisível por numero
        divisores += 1
    numero += 1 # Vai para o próximo número

if(divisores == 0): # Se não tiver nenhum divisor
    print("É primo.")
elif(divisores == 1): # Se tiver 1 divisor
    print("Não é primo, possui 1 divisor diferente de 1.")
else: # Se tiver mais de 1 divisor
    print("Não é primo, possui ",divisores," divisores diferentes de 1")