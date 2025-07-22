# Pede ao usuário o valor de deslocamento para a cifra de César
deslocamento = int(input("Digite o deslocamento: "))

# Pede ao usuário o texto que quer criptografar
texto = input("Digite o texto a ser criptografado: ")

# Inicializa a variável para armazenar o texto criptografado
texto_criptografado = ""

# Itera sobre cada caractere no texto fornecido
for letra in texto:
    if letra.isupper():
        # Criptografa letras maiúsculas
        letra_criptografada = chr((ord(letra) - ord('A') + deslocamento) % 26 + ord('A'))
    elif letra.islower():
        # Criptografa letras minúsculas
        letra_criptografada = chr((ord(letra) - ord('a') + deslocamento) % 26 + ord('a'))
    else:
        # Mantém caracteres que não são letras inalterados
        letra_criptografada = letra
    
    # Adiciona o caractere criptografado no texto criptografado
    texto_criptografado += letra_criptografada

# Imprime o texto criptografado resultante
print("Texto criptografado:", texto_criptografado)
