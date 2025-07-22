resposta = True
while(resposta == True):
  print("Vamos jogar pedra papel ou tesoura!")
  print("0 para pedra, 1 para papel e 2 para tesoura.")
  jogador1 = input("Escolha sua jogada jogador 1: \n")
  jogador2 = input("Escolha sua jogada jogador 2: \n")

  if(jogador1 == 0 and jogador2 == 2) or (jogador1 == 1 and jogador2 == 0) or (jogador1 == 2 and jogador2 == 1):
      print("Jogador 1 ganhou!")
  elif(jogador1 == jogador2):
      print("Temos 1 empate!")
  else:
      print("Jogador 2 ganhou!")

  print("Quer jogar de novo? apenas digite 'sim' ou 'nao'")
  jogar = input()
  if(jogar == "sim"):
      resposta == True
  else:
      resposta == False
      break
      