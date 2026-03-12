from selenium import webdriver
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.by import By
import time

# Configuração do WebDriver (nesse exemplo, estamos usando o Chrome)
driver = webdriver.Chrome ()

# Acessa a página de cadastro usando o caminho absoluto com o protocolo file://
# Ceritifuqe-se de que o caminho está apontando par aum arquivo HTML específico

driver.get('http://localhost/001Turma2024_2V1_TARDE/SA_Lego_Mania/Trabalho_Modelagem_Lego/LEGO-MANIA_S.A/index.php')
time.sleep(1)
#Preenche o campo Nome

email_input_login = driver.find_element(By.ID, "email")
email_texto_login = "admin@admin"

senha_input_login = driver.find_element(By. ID, "senha")
senha_texto_login = "123"

for letra in email_texto_login:
    email_input_login.send_keys(letra)
    time.sleep(0.2) 

for letra in senha_texto_login:
    senha_input_login.send_keys(letra)
    time.sleep(0.2) 

login_button = driver.find_element(By. ID, "entrar_button")
login_button.click()
time.sleep(1)

cadastro_button = driver.find_element(By. ID, "menu-cadastro")
cadastro_button.click()
time.sleep(1)

usuario_button = driver.find_element(By. ID, "cadastro_pecas")
usuario_button.click()
time.sleep(1)


campos = [
    driver.find_element(By. ID, "nome_peca"),
    driver.find_element(By.ID, "descricao_peca"),
    driver.find_element(By.ID, "quantidade"),
    driver.find_element(By.ID, "quantidade_minima"),
    driver.find_element(By.ID, "preco"),
]

inputs = [
    "Fone de Ouvido Bluetooth",
    "Um fone de ouvido bluetooth com 36h de duração de bateria",
    "8",
    "3",
    "R$149,99",
]

for campo,texto in zip(campos, inputs):
    for letra in texto:
     campo.send_keys(letra)
     time.sleep(0.090)

time.sleep(1)

tipo_input = driver.find_element(By. ID, "tipo")
time.sleep(0.5)

select = Select(tipo_input)
time.sleep(0.5)

select.select_by_value("perifericos")
time.sleep(1.5)

fornecedor_input = driver.find_element(By. ID, "id_fornecedor")

select = Select(fornecedor_input)
time.sleep(0.5)

select.select_by_value("3")
time.sleep(1.5)

submit_button = driver.find_element(By. ID, "botaocadastro")
submit_button.click()
time.sleep(2)




