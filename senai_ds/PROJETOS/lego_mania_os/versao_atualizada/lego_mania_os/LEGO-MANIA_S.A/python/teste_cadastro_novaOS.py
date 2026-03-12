from selenium import webdriver
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
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
    time.sleep(0.15) 

for letra in senha_texto_login:
    senha_input_login.send_keys(letra)
    time.sleep(0.15) 

login_button = driver.find_element(By. ID, "entrar_button")
login_button.click()
time.sleep(1)

cadastro_button = driver.find_element(By. ID, "menu-ordem-de-serviços")
cadastro_button.click()
time.sleep(1)

usuario_button = driver.find_element(By. ID, "nova_ordem")
usuario_button.click()
time.sleep(1)

cliente_input = driver.find_element(By. ID, "nome_cliente_ordem")
cliente_input.send_keys("J")
time.sleep(0.5)
cliente_input.send_keys(Keys.ARROW_DOWN)
time.sleep(0.3)
cliente_input.send_keys(Keys.ENTER)
time.sleep(0.5)

tecnico_seleciona = driver.find_element(By. ID, "tecnico")
time.sleep(0.5)

select = Select(tecnico_seleciona)
time.sleep(0.5)

select.select_by_value("33")
time.sleep(0.5)

campos = [
    driver.find_element(By.ID, "marca_aparelho"),
    driver.find_element(By.ID, "tempo_uso"),
    driver.find_element(By.ID, "problema"),
    driver.find_element(By. ID, "observacao"),
]

inputs = [
    "Motorola",
    "3 anos",
    "Microfone não funciona, e celular não carrega",
     "Celular caiu na piscina e esteve por aproximadamente 1 minuto dentro da água",
]

for campo,texto in zip(campos, inputs):
    for letra in texto:
     campo.send_keys(letra)
     time.sleep(0.080)

data_input = driver.find_element (By. ID, "dt_recebimento")
data_input.clear()

data_input.send_keys("29")
time.sleep(0.3)

data_input.send_keys("08")
time.sleep(0.3)

data_input.send_keys("2025")
time.sleep(0.3)

time.sleep(1)

prioridade_select = driver.find_element(By. ID, "prioridade")
time.sleep(0.5)

select = Select(prioridade_select)
time.sleep(0.5)

select.select_by_value("Alta")
time.sleep(0.5)

valor_total = driver.find_element(By. ID, "preco")
valor_total.send_keys("23000")

forma_pagamento_select = driver.find_element(By. ID, "forma_pagamento")
time.sleep(0.5)

select_pagamento = Select(forma_pagamento_select)
time.sleep(0.5)

select_pagamento.select_by_value("debito")
time.sleep(1.5)


submit_button = driver.find_element(By. ID, "botaocadastro")
submit_button.click()
time.sleep(2)




