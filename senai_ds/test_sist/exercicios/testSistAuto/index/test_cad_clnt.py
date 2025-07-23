from selenium import webdriver
from selenium.webdriver.common.by import By
import time

# Configurando do WebDriver (nesse exemplo, estamos usando o Chrome)
driver = webdriver.Chrome()

# Acessa a página do cadastro usando o camiho absoluto com o protocolo file://
# Certifique-se de que o caminho está apontando para um arquivo HTML específico

driver.get("C:/Users/gustavo_f_boeing/Downloads/test_sist/index/index.html")

# Preenche o campo Nome
nome_input = driver.find_element(By.ID, "name")
nome_input.send_keys("Dalton bumbum gostoso")

# Preenche o campo CPF
cpf_input = driver.find_element(By.ID, "cpf")
cpf_input.send_keys("12345678901")

# Preenche o campo Endereço
endereco_input = driver.find_element(By.ID, "address")
endereco_input.send_keys("Rua das flores, 123")

# Preenche o campo Telefone
telefone_input = driver.find_element(By.ID, "phone")
telefone_input.send_keys("11987654321")

# Clica no botão de Cadastrar
#submit_button = driver.find_element(By.CSS_SELETOR, "button[type='submit']")
#submit_button.click()

# Aguarda um momento para visualizar o resultado (em uma aplicação real, você verficaria a resposta)
time.sleep(8)

# Fecha o navegador
driver.quit()
