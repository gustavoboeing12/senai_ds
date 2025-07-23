programa {

  cadeia cad_titulo[999], cad_autor[999], cad_genero[999], cad_anopublicacao[999]
  inteiro contador_1, contador_2, contador_3, opcao

    funcao inicio() {
    cadeia titulo, autor, nome, genero, anopublicacao, status, livros[100]
    inteiro contadorLivros, 

    contador_1 = -1
    contador_3 = -1
    
      escreva("Bem vindo à Sablewood sua biblioteca digital. \n")
      escreva("Para acessar a biblioteca, digite seu nome: ")
      leia(nome)
      escreva("Bem-vindo ", nome, ", Gostaria de cadastrar um novo livro ou deseja ver os já cadastrados? \n")
      limpa()
      escreva("O'que você deseja fazer hoje?\n")
      escreva("1 - Cadastrar Livro\n")
      escreva("2 - Conferir biblioteca\n:")
      leia(opcao)

      limpa()
      
      enquanto(opcao != 3){
      se(opcao == 1){

        contador_1 = contador_1 + 1
        contador_3 = contador_3 + 1

      escreva("Faça o cadastro do livro.\n")
      escreva("Digite o título do livro: ")
      leia(titulo)

      cad_titulo[contador_1] = titulo

      escreva("Digite o autor do livro: ")
      leia(autor)

      cad_autor[contador_1] = autor

      escreva("Digite o gênero do livro: ")
      leia(genero)

      cad_genero[contador_1] = genero

      escreva("Digite o ano de publicação: ")
      leia(anopublicacao)

      cad_anopublicacao[contador_1] = anopublicacao

      escreva("Alugado ou na biblioteca?: ")
      leia(status)

      cad_anopublicacao[contador_1] = anopublicacao

      limpa()

      escreva("O livro '", titulo, "' foi cadastrado com sucesso!!")
      
      rpt()

      limpa()

      }
      se (opcao == 2){
      
      limpa()

      gts()

      rpt()

      limpa()

      }
      se(opcao == 3){
        escreva("sistema finalizado")

      }
      }
      }

      funcao rpt(){
      escreva("Gostaria de mais alguma coisa? \n")
      escreva("1 - Cadastrar outro Livro\n")
      escreva("2 - conferir biblioteca\n")
      escreva("3 - sair\n:")

      leia(opcao)
        
      }
      funcao gts(){


      escreva("1°LIVRO\n Titulo= Gatinhos\n Autor= Yan\n Gênero= Infantil\n Ano de Publição= 2008\n")
      escreva("GATINHOS\n")
      escreva("Era uma vez um gatinho triste, que sempre foi triste. \n Mas um dia um lindo e alegre gato rosa o encontrou. \n GATO ROSA:") 
      escreva("\n Oi gatinho triste, quero ser seu amigo para sempre. \n GATO TRISTE: \n AEEEEEEE!!! Não sou mais triste!!!\n GATO ROSA:\n") 
      escreva("AMIGOSSSSS! \n GATO FELIZ\n AMIGOSSSS! \n Final feliz:)\n\n\n\n")

      para(contador_2 = 0; contador_2 <= contador_3; contador_2++){

        escreva("Titulo:",cad_titulo[contador_2])
        escreva("\nAutor:",cad_autor[contador_2])
        escreva("\nGenero:",cad_genero[contador_2])
        escreva("\nAno de publicação:",cad_anopublicacao[contador_2],"\n\n\n\n")

      }
      }
      }
    