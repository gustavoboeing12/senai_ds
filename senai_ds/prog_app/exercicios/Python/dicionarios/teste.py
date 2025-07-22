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

# Exemplo de uso
s = "Exemplo de string com várias letras!"
print("A letra mais comum é:", letra_mais_comum(s))