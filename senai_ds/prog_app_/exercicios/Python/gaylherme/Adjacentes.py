while True:
    resposta = input('Digite 5 números: ')

    if len(resposta) == 5:

        num1 = resposta[0]
        num2 = resposta[1]
        num3 = resposta[2]
        num4 = resposta[3]
        num5 = resposta[4]

        if(num1 == num2 or num2 == num3 or num3 == num4 or num4 == num5):
            print(f'Os números {resposta} são adjacentes')
            break
        else:
            print(f'Os números {resposta} não são adjacentes')
            break
    else:
        print(f'Não há a quantia de números pedida')









nums_escolhidos = []

while True:
    import re

    lista_nums = input("Escolha um número: ")
    nums_escolhidos.append(int(lista_nums))

    if lista_nums == lista_nums:
        teste = lista_nums

    escolhido = input(f'Número escolhido: {lista_nums}, deseja escolher mais algum?: ')

    escolha = re.search(r'\bsim|ss|claro|gostaria\b',escolhido, re.IGNORECASE)
    if(escolha):
        print(f'Numeros escolhidos: {nums_escolhidos}')
    else:
        print(f'Os números escolhidos foram: {nums_escolhidos}')
        if teste:
            print(f'Os números {nums_escolhidos } são adjacentes')
            break
        else:
            print(f'Os números {nums_escolhidos} não são adjacentes')
            break

#print(lista_nums[0:4])