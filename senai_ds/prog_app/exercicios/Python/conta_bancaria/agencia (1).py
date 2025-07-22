from random import randint

class Agencia:
    def __init__(self, telefone, cnpj, numero):
        self.telefone = telefone
        self.cnpj = cnpj
        self.numero = numero
        self.clientes = []
        self.caixa = 0
        self.emprestimos = []
        pass

    def verificar_caixa(self):
        if self.caixa < 1000000:
            print ("Caixa abaixo do Nível recomendado. Caixa atual: {}".format(self.caixa))
        else:
            print ("O valor de Caixa está OK. Caixa atual: {}".format(self.caixa))
            
    def emprestar_dinheiro(self, valor, cpf, juros):
        if self.caixa>valor:
            self.emprestimos.append((valor, cpf, juros))
            print("Emprétimo Efetuado")
        else:
            print("Empréstimo não é possível. Dinheiro não diponível em caixa")
            
    def adicionar_cliente(self, nome, cpf, patrimonio):
        self.clientes.append((nome, cpf, patrimonio))

class AgenciaVirtual(Agencia):
    def __init__(self,site,telefone,cnpj,numero):
        self.site=site
        super().__init__(telefone,cnpj, numero)
    

class AgenciaComum(Agencia):
    def __init__(self,telefone,cnpj):
        super().__init__(telefone,cnpj, randint(1001,9999))
        self.caixa=1000000
        
class AgenciaPremium(Agencia):
    pass

agencia1 = Agencia(22223333,200000000,4568)
agencia1.caixa=1000000
print(agencia1.__dict__)
agencia1.verificar_caixa()
agencia1.emprestar_dinheiro(10,11122233344,0.1)    

agencia1.adicionar_cliente("Lira",11122233344,10000)
print(agencia1.clientes)

agencia_virtual = AgenciaVirtual("www.agenciavirtual.com.br",22224444,1520000000,1000)
agencia_virtual.verificar_caixa()
print(agencia_virtual.__dict__)

agencia_comum = AgenciaComum(33334444,2220000000)
agencia_comum.verificar_caixa()
print(agencia_comum.__dict__)