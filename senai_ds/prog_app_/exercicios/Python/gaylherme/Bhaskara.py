while True:
    import math

    valores = input("Insira o valor de A, B e C na ordem e usando vírgulas entre eles: ").strip()
    valores = valores.split(",")

    if len(valores) == 3:
        a = float(valores[0])
        b = float(valores[1])
        c = float(valores[2])

        delta = b*b - 4 * a * c

        if (a == 0):
            print("Refaça")
        else:
            print(delta)
            break
    else:
        print("Refaça nigga")

if(delta > 0):
    x = (-b + math.sqrt(delta)) / (2*a)
    y = (-b - math.sqrt(delta)) / (2*a)
    print(x)
    print(y)

if(delta == 0):
    print(x)

if(delta < 0):
    print("A conta acaba por aqui")