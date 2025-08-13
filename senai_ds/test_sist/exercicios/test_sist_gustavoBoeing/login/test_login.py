from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

driver.get("http://localhost:8080/test_sist_gustavoBoeing/login/login.html")

time.sleep(4)

# Preenche os campos de usuário
driver.find_element(By.ID, "username").send_keys("admin")
driver.find_element(By.ID, "password").send_keys("123456")
driver.find_element(By.CSS_SELECTOR, "button['type=submit']")

time.sleep(10)

if "Cadastro de cliente" in driver.page_source:
    print("Login realizado com sucesso")

else:
    print("Falha no login ou redirecionamento")

print("Título atual da página: ", driver.title)