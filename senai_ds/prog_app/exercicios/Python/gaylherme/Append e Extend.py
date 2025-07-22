# Aqui veremos append e extend, que são usados em listas



# 1 - Append: Usado para adicionar um item a uma lista:

'''vendedores = ["João", "Alon", "Amanda", "Camila"]
novas_contratacoes = ["Lira", "Lucas"] 

vendedores.append("Lira") # Se trocar o "Lira" pela outra variável "novas_contatacoes" o conteúdo dentro dela apareceria, mas ficaria com [] dentro dela, ficando estranho
print(vendedores)

# 2 - Extend: Usado para adicionar vário itens a uma lista:

vendedores = ["João", "Alon", "Amanda", "Camila"]
novas_contratacoes = ["Lira", "Lucas"] 

vendedores.extend(novas_contratacoes) # Se colocasse apena "Lira" por exemplo, o extend separaria cada letra como sendo um novo item, então ficaria 'L', 'i', 'r', 'a'
print(vendedores)

# 3 - +: Da de usar um sinal de mais para juntar os itens:

vendedores = ["João", "Alon", "Amanda", "Camila"]
novas_contratacoes = ["Lira", "Lucas"] 

vendedores = vendedores + novas_contratacoes
print(vendedores)'''

#for i in range(1,6):
    #print('*'*i)#

dias_da_semana = ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"]

dia = 7

print(dias_da_semana[dia])