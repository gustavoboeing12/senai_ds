from selenium import webdriver
from selenium.webdriver.common.by import By
import time

# Configurando do WebDriver (nesse exemplo, estamos usando o Chrome)
driver = webdriver.Chrome()

# Acessa a página do cadastro usando o camiho absoluto com o protocolo file://
# Certifique-se de que o caminho está apontando para um arquivo HTML específico

driver.get("C:/Users/gustavo_f_boeing/Downloads/test_sist/produto/prod.html")

# Preenche o campo ID do produto
id_input = driver.find_element(By.ID, "id")
id_input.send_keys("A11")

# Preenche o campo Descrição
desc_input = driver.find_element(By.ID, "desc")
desc_input.send_keys("Bobbie goods, 50 páginas, capa dura")

# Preenche o campo Marca
marc_input = driver.find_element(By.ID, "marc")
marc_input.send_keys("BOEING")

# Preenche o campo Quantidade
quant_input = driver.find_element(By.ID, "quant")
quant_input.send_keys("77")

# Preenche o campo Preço
preco_input = driver.find_element(By.ID, "preco")
preco_input.send_keys("R$ 99,99")

# Clica no botão de Cadastrar
#submit_button = driver.find_element(By.CSS_SELETOR, "button[type='submit']")
#submit_button.click()

# Aguarda um momento para visualizar o resultado (em uma aplicação real, você verficaria a resposta)
time.sleep(12)

# Fecha o navegador
driver.quit()
