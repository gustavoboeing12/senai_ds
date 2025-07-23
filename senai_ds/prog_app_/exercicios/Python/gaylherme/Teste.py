while True:

    # Pedindo o nome
    nome = input("Digite seu nome: ")
    if nome.isdigit(): # Aqui tá verificando se o valor inserido é um número
        print("Digite um nome válido!")
    else:
        print(f"Bem vindo, {nome}!\n")
        break

while True:
    info = input("Por favor, digite sua idade, peso e altura usando vírgulas: ").strip()

    dados = info.split(",") # Aqui tá mandando colocar , entre as informações
    
    if len(dados) == 3: # Verificando se 3 valores foram inseridos

        idade = dados[0]
        peso = dados[1]
        altura = dados[2]

        if idade.isdigit(): # Vendo se é número
            idade = int(idade)
        if peso.isdigit(): # Vendo se é número
            peso = float(peso)
        if altura.isdigit(): # Vendo se é número
            altura = float(altura)
            print(f"Nome: {nome} \nIdade: {idade} anos \nPeso: {peso} kg \nAltura: {altura} metros\n")
            break

    else:
        print("Ponha suas informações, tô mandando >:(")