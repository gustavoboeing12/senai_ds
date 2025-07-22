def verifica_palindromo(s):
    # Remove espaços em branco e verifica se a string é igual ao seu reverso
    if s == s[::-1]:
        return "Palíndromo"
    else:
        return "Não é palíndromo"

entrada = input("Digite uma string: ") # Lê a string do usuário

print(verifica_palindromo(entrada)) # Chama a função e imprime o resultado

 

