def linhas(s): # Cria a função para adicionar linhas á lista
    i = 1 # declara uma variável controladora
    while(i <= s): # cria um loop de vezes que irá passar baseado na variável s
        l.append(i) # adiciona um item a lista
        i += 1 # aumenta 1 á variavel, para que continue o loop, e mais tarde, adicione um número de valor 1 a mais para a lista
        print(l) 

l = [] # cria uma lista para a 'escadinha'
s = int(input("Digite um número: ")) # pede o número para quantas 'escadinhas' terá
linhas(s) # invoca a função 