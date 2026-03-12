from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
import time

# Configuração do WebDriver (nesse exemplo, estamos usando o Chrome)
driver = webdriver.Chrome ()

# Acessa a página de cadastro usando o caminho absoluto com o protocolo file://
# Ceritifuqe-se de que o caminho está apontando par aum arquivo HTML específico

driver.get('http://localhost/001Turma2024_2V1_TARDE/SA_Lego_Mania/Trabalho_Modelagem_Lego/LEGO-MANIA_S.A/index.php')
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

usuario_button = driver.find_element(By. ID, "cadastro_usuario")
usuario_button.click()
time.sleep(1)

campos = [
    driver.find_element(By. ID, "nome_usuario"),
    driver.find_element(By.ID, "email"),
    driver.find_element(By. ID, "senha"),
    
]

inputs = [
    "João Vitor Atanazio",
    "atanazio@atanazio",
    "12345678"
]

for campo,texto in zip(campos, inputs):
    for letra in texto:
     campo.send_keys(letra)
     time.sleep(0.12)


perfil_input = driver.find_element(By.ID, "id_perfil")
perfil_input.click()
time.sleep(2)

select = driver.find_element(By. ID, "tecoption")
select.click()
time.sleep(2)

submit_button = driver.find_element(By. ID, "botaocadastro")
submit_button.click()
time.sleep(2)




