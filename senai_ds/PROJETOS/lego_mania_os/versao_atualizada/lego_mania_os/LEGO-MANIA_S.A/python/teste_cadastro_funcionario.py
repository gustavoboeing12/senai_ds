from selenium import webdriver
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.by import By
import time

# Configuração do WebDriver (nesse exemplo, estamos usando o Chrome)
driver = webdriver.Chrome ()

# Acessa a página de cadastro usando o caminho absoluto com o protocolo file://
# Ceritifuqe-se de que o caminho está apontando par aum arquivo HTML específico

driver.get('http://localhost/001Turma2024_2V1_TARDE/SA_Lego_Mania/Trabalho_Modelagem_Lego/LEGO-MANIA_S.A/index.php')
time.sleep(2)
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

usuario_button = driver.find_element(By. ID, "cadastro_funcionario")
usuario_button.click()
time.sleep(1)


campos = [
    driver.find_element(By. ID, "nome"),
    driver.find_element(By.ID, "salario"),
    driver.find_element(By.ID, "cpf_cnpj"),
    driver.find_element(By.ID, "endereco"),
    driver.find_element(By. ID, "bairro"),
    driver.find_element(By. ID, "cidade"),
    driver.find_element(By. ID, "cep"),
    driver.find_element(By.ID, "email"),
]

inputs = [
    "Atanazio",
    "300000",
    "12345678903",
    "Rua dos cabras 456",
    "Bairro cabreros",
    "Cabrovilles",
    "89227650",
    "Atanazio@Atanazio",
]

for campo,texto in zip(campos, inputs):
    for letra in texto:
     campo.send_keys(letra)
     time.sleep(0.1)

time.sleep(1)

data_input = driver.find_element(By. ID, "nascimento")

data_input.send_keys("03")
time.sleep(0.3)

data_input.send_keys("10")
time.sleep(0.3)

data_input.send_keys("2003")
time.sleep(0.3)


estado_input = driver.find_element(By. ID, "estado")
time.sleep(0.5)

select = Select(estado_input)
time.sleep(0.5)

select.select_by_value("SC")
time.sleep(0.5)

submit_button = driver.find_element(By. ID, "botaocadastro")
submit_button.click()
time.sleep(2)




