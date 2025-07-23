class Pessoa:
    
    # Definindo funções para mudanças nas váriaveis peso, idade e altura 
  
    # Função para aumentar o peso, mais_peso é a variavel indicadora 

    def engordar(self, mais_peso ):
           self.peso += mais_peso

    # Função para aumentar a idade, mais_idade é a variavel indicadora 

    def envelhecer(self, mais_idade):
         self.idade += mais_idade

    # Função para diminuir o peso, menos_peso é a variavel indicadora 

    def emagrecer(self, menos_peso):
         self.peso -= menos_peso

    # Função para aumentar a altura, mais_altura é a variavel indicadora 

    def crescer(self, mais_altura):
            if(self.idade < 21):
             self.altura += mais_altura
      
    # Declaração dos "atributos" da pessoa 

if __name__ == '__main__':
    pessoa = Pessoa()
    pessoa.peso = 80
    pessoa.idade = 22
    pessoa.altura = 180

    # Valores a serem adicionados nas funções, em suas variáveis indicadoras

    pessoa.engordar (6)
    pessoa.envelhecer (1)
    pessoa.crescer (1)

    print("A altura é de ",pessoa.altura)
    print("Sua idade é: ",pessoa.idade)
    print("E seu peso é :",pessoa.peso)
   
    



    
   