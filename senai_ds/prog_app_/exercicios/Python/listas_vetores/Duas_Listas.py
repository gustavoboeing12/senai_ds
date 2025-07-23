uma_lista = [1,2,3,4,6]
outra_lista = [8,9,7]

if([elemento for elemento in uma_lista if elemento in outra_lista]):
    iguais = [elemento for elemento in uma_lista if elemento in outra_lista]
    print(iguais)
else:
    print("Não há nenhum elemento igual.")