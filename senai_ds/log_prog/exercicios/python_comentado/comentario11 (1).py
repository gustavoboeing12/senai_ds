# Define a classe Contato para armazenar informações de contato
class Contato:
    # Inicializa um novo objeto Contato com nome, endereço e email
    def __init__(self, nome, endereco, email):
        self.nome = nome
        self.endereco = endereco
        self.email = email

# Define a classe Agenda para gerenciar uma lista de contatos
class Agenda:
    # Inicializa um novo objeto Agenda com uma lista vazia de contatos
    def __init__(self):
        self.contatos = []

    # Adiciona um contato à lista de contatos
    def adicionar_contato(self, contato):
        self.contatos.append(contato)

    # Remove um contato da lista de contatos
    def remover_contato(self, contato):
        self.contatos.remove(contato)

    # Lista todos os contatos na agenda
    def listar_contatos(self):
        for contato in self.contatos:
            print("Nome:", contato.nome)
            print("Endereço:", contato.endereco)
            print("E-mail:", contato.email)
            print()  # Adiciona uma linha em branco para separar os contatos

# Cria uma agenda nova
agenda = Agenda()

# Cria dois novos contatos
contato1 = Contato("João", "Rua A, 123", "joao@example.com")
contato2 = Contato("Maria", "Rua B, 456", "maria@example.com")

# Adiciona os contatos à agenda
agenda.adicionar_contato(contato1)
agenda.adicionar_contato(contato2)

# Lista todos os contatos na agenda
print("Contatos na agenda:")
agenda.listar_contatos()

# Remove o contato1 da agenda
agenda.remover_contato(contato1)

# Lista todos os contatos na agenda após a remoção
print("Contatos na agenda após remoção:")
agenda.listar_contatos()
