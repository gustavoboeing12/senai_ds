from datetime import datetime
import pytz
from random import randint

class ContaCorrente():
    
    '''
    Cria um objeto ContaCorrente para gerenciar as contas dos clientes.
    
    Atributos:
        nome (str): Nome do Cliente
        cpf (str): CPF do Cliente. Deve ser inserido com pontos e traços (xxx.xxx.xxx-xx)
        agencia (str): Número da agência
        num_conta(str): Número da Conta Corrente do Cliente
        saldo: Saldo disponível pelo Cliente
        limite: Limite de cheque especial daquele Cliente
        _transacoes: Histórico de Transações do Cliente
    '''
    @staticmethod
    def _data_hora():
        fuso_BR = pytz.timezone("Brazil/East")
        horario_BR = datetime.now(fuso_BR)
        return horario_BR.strftime("%d/%m/%y %H:%M:%S")
    def __init__(self, nome, cpf, agencia, num_conta):
        self._nome = nome
        self._cpf = cpf
        self._saldo = 0
        self._limite = None
        self._agencia = agencia
        self._num_conta = num_conta
        self._transacoes = []
        self._cartoes =[]
    def consultar_saldo(self):
        print("Seu saldo atual é de R$ {:,.2f}".format(self._saldo))
        pass
    def depositar_dinheiro(self,valor):
        self._saldo += valor
        self._transacoes.append((valor,self._saldo, ContaCorrente._data_hora()))
        
    def _limite_conta(self):
        self._limite = -1000
        return self._limite
        
    def sacar_dinheiro(self,valor):
        if self._saldo - valor  < self._limite_conta():
            print("Você não tem saldo suficiente")
            self.consultar_saldo()
        else:
            self._saldo -=valor
            self._transacoes.append((valor,self._saldo, ContaCorrente._data_hora()))
            
    def consultar_historico_transacoes(self):
        print("Histórico de Transações: ")
        for transacao in self._transacoes:
            print(transacao)
    
    def transferir(self, valor, conta_destino):
        self._saldo -= valor
        self._transacoes.append((-valor,self._saldo, ContaCorrente._data_hora()))
        conta_destino._saldo += valor
        conta_destino._transacoes.append((valor, conta_destino._saldo, ContaCorrente._data_hora()))
        
              
conta_lira = ContaCorrente("Lira","111.222.333-45", "123","123-3")
conta_lira.consultar_saldo()

conta_maeLira= ContaCorrente("Beth","222.333.444-55","5555","656565")
conta_lira.transferir(1000,conta_maeLira)

conta_lira.depositar_dinheiro(10000)
conta_lira.consultar_saldo()

conta_lira.sacar_dinheiro(11000)
conta_lira.consultar_saldo()
print(conta_lira._cpf, conta_lira._agencia)

conta_lira.consultar_historico_transacoes()

#Saldo Via Métodos
print(conta_lira._saldo)
#Tentando mudar o valor do saldo "por fora" do programa
conta_lira._saldo=8000
#Novo valor após tentativa de "burlar" o sistema
print(conta_lira._saldo)


class CartaoCredito():
    
    @staticmethod
    def _data_hora():
        fuso_BR = pytz.timezone("Brazil/East")
        horario_BR = datetime.now(fuso_BR)
        return horario_BR
    
    def __init__(self,titular,conta_corrente):
        self.numero= randint(1000000000000000, 9999999999999999)
        self.titular=titular
        self.validade= '{}/{}'.format(CartaoCredito._data_hora().month, CartaoCredito._data_hora().year +4 )
        self.cod_seguranca= '{}{}{}'.format(randint(0,9),randint(0,9),randint(0,9))
        self.limite=1000
        self.conta_corrente=conta_corrente
        conta_corrente._cartoes.append(self)
                
conta_lira=ContaCorrente("Lira","111.222.333-45","1234","34062")
cartao_lira=CartaoCredito("Lira",conta_lira)

print(conta_lira._cartoes)
print(conta_lira._cartoes[0].numero)

print(cartao_lira.numero,
      cartao_lira.titular,
      cartao_lira.validade,
      cartao_lira.cod_seguranca,
      cartao_lira.limite
      )
print(cartao_lira.__dict__)