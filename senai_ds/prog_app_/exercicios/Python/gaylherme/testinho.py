'''while True:
        nome = input("Digite seu nome: ").strip()
        if nome.isdigit():
            print(f"O nome '{nome}' é inválido, por favor refaça!")
        else:
            print(f"Olá, {nome}")
'''

import re

while True:
    nome = input("Digite seu nome: ").strip() # Linha pedindo o nome
    validos = r'^[a-zA-Záàãâéèêíïóôõúüç\s]+$' # Esse comando tá deixando apenas os caracteres que tão ali válidos

    if re.match(validos, nome): # Aqui tá pondo o 'validos' em prática
        print(f"Olá, {nome}!")
        break
    if len(nome) < 2: # Se o nome tiver menos de 2 caracteres ele vai ser invalidado
        print("Nome inválido, deve haver mais de 1 caractere!")
    if nome.isdigit():
        print("Números não são válidos, REFAÇA!")



while True:
    info = input("Digite sua idade, peso, e altura em ordem, usando vírgulas para separá-las: ")
    dados = info.split(",") # Mandando separar as informações em vírgulas

    if len(dados) == 3: # Aqui manda ter 3 informações
        idade = int(dados[0]) # Essa linha e as 2 abaixo mostram q é pras informações serem números
        peso = float(dados[1])
        altura = float(dados[2])

        print(f"Nome: {nome} \nIdade: {idade} anos \nPeso: {peso} kg \nAltura: {altura} metros\n")
        break

    else:
        print("REFAÇAAAAAAA")


imc = peso / (altura*altura)

res = input("Gostaria de saber seu IMC?: ")
afirmacoes = r'[SIM Sim sim SS Ss ss CLARO Claro]'
if re.search(afirmacoes, res):
    if(imc < 18.5):
        Imc = "Magreza"
    if(imc >= 18.5) and (imc < 25):
        Imc = "Normal"
    if(imc >= 25) and (imc < 30):
        Imc = "Sobrepeso"
    if(imc >= 30) and (imc < 40):
        Imc = "Obesidade"
    if(imc > 40):
        Imc = "Obesidade Grave"
    print(f"De acordo com os dados informados, seu IMC é: {imc:.1f}. Seu caso é de: {Imc}")
else:
    print("Adeus!")