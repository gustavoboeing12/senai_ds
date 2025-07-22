class Conta:
    numero = "00000-0"
    saldo = 0.0

    def deposito(self, valor):
        self.saldo += valor
        
    def saque(self, valor):
        if(self.saldo > 0):
            self.saldo -= valor
        else:
            print("saldo insuficiente")

if __name__ == '__main__':
    conta = Conta()
    conta.saldo = 14
    conta.numero = "13131-2"
    conta.saque(14)
    print("Seu saldo é de: ",conta.saldo)
    print(conta.numero)