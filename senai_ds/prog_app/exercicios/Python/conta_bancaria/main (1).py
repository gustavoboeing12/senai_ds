from exemplo_banco (1) 
import ContaCorrente,CartaoCredito

conta_lira=ContaCorrente("Lira","111.222.333-45","1234","34062")
cartao_lira=CartaoCredito("Lira",conta_lira)

print(conta_lira._cartoes)
print(conta_lira._cartoes[0].numero)

print(cartao_lira.numero,
      cartao_lira.titular,
      cartao_lira.validade,
      cartao_lira.cod_seguranca,
      cartao_lira.limite)

print(cartao_lira.__dict__)