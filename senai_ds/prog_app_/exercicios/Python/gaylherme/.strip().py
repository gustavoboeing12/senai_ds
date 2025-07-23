# O método .strip() em Python é usado para remover espaços em branco (ou caracteres específicos) do início e do fim de uma string.
# Ele é útil para limpar entradas de usuário e evitar problemas com espaços extras.



# 1 - Remover espaços em branco do início e do fim

texto = "   Olá, mundo!   "
print(texto.strip())  # Saída: "Olá, mundo!"



# 2 - Remover caracteres específicos
# Você pode passar um argumento para .strip() para remover caracteres específicos:

texto = "---Python é incrível!---"
print(texto.strip("-"))  # Saída: "Python é incrível!"



# 3 - .lstrip() e .rstrip()
# lstrip(): Remove apenas os espaços do lado esquerdo (início da string).
# rstrip(): Remove apenas os espaços do lado direito (fim da string).

texto = "   Olá, mundo!   "
print(texto.lstrip())  # Saída: "Olá, mundo!   "
print(texto.rstrip())  # Saída: "   Olá, mundo!"



# 4 - Aplicação comum em entrada de usuário
# Muitas vezes, o usuário pode digitar um valor com espaços extras, e .strip() ajuda a garantir que o valor esteja limpo antes de ser processado:

nome = input("Digite seu nome: ").strip()
if nome:
    print(f"Bem-vindo, {nome}!")
else:
    print("Nome inválido!")



# .strip() remove espaços/brancos do início e do fim.
# .lstrip() remove apenas do início.
# .rstrip() remove apenas do fim.
# Também pode remover caracteres específicos.