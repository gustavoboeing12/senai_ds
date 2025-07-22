def letra_mais_comum(s):
    # Filtra apenas as letras da string e converte para minúsculas
    s = ''.join(filter(str.isalpha, s)).lower()
    
    # Cria um dicionário para armazenar a contagem das letras
    contagem = {}
    
    # Conta a frequência de cada letra
    for letra in s:
        if letra in contagem:
            contagem[letra] += 1
        else:
            contagem[letra] = 1
    
    # Encontra a letra mais comum
    letra_comum = max(contagem, key=contagem.get)
    
    return letra_comum


s = input("Digite um texto: ")
pontuacao = ["." , "," , ":" , ";" , "?"]
for p in pontuacao:
    texto = s.replace(p," ")

letra = len(s)
print("Tem um total de",letra,"letras")
print("E a letra mais comum é a :",letra_mais_comum(s))
