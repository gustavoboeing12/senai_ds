programa {
  funcao inicio() {
    real P, FP, VF1, VF2, VF3, D1, D3
    escreva("Informe o valor da compra: ")
    leia(P)
    escreva("Para escolher a forma de pagamento,informe 1 para á vista com 10% de desconto,2 para parcelado em duas vezes, e 3 para parcelado em três vezes com 10% de juros: ")

    leia(FP)

    se (FP==1)
    {
    D1=P*0.10
    VF1=P-D1
    escreva("O valor final do produto é: ",VF1)
    }
    senao se (FP==2)
    {
    VF2=P/2
    escreva("O valor final é de duas parcelas de ",VF2)
    }
    senao se (FP==3)
    {
    VF3=P/3
    escreva("O valor final é de três parcelas de ",VF3)
    }

  }
}
