programa {
  funcao inicio() {
    inteiro P, R, T, A
    escreva("Informe o valor do tempo em anos do investimento: ")
    leia(T)
    escreva("Informe o valor do capital inicial: ")
    leia(P)
    escreva("Informe a taxa de juros ao ano: ")
    leia(R)
    A=P*(1+R*T)
    escreva("O valor final do investimento é: ",A)



  }
}
