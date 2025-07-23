# O comando "len()" mostra o número de itens em uma lista

'''mylist = ["apple", "banana", "cherry", "sexo 3 baka gaijin"]
x = len(mylist)

print(x)
'''

while True:
    entrada = input("Digite seu nome, idade e cidade separados por vírgula: ").strip()
    dados = entrada.split(",")

    if len(dados) == 3:  # Verifica se há exatamente 3 partes
        nome = dados[0].strip()
        idade = dados[1].strip()
        cidade = dados[2].strip()
    
        if idade.isdigit():  # Verifica se a idade é um número
            idade = int(idade)
            print(f"Nome: {nome}, Idade: {idade}, Cidade: {cidade}")
        else:
            print("Erro: A idade deve ser um número.")
    else:
        print("Erro: Formato incorreto! Use Nome, Idade, Cidade.")