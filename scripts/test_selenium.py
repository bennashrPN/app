from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options

# Tentukan path ke WebDriver
chrome_driver_path = 'C:/Program Files/chromedriver'  # Ganti dengan path ke ChromeDriver Anda

# Set opsi untuk Chrome
chrome_options = Options()
chrome_options.add_argument("--headless")  # Opsi ini menjalankan Chrome tanpa antarmuka grafis

# Buat instance WebDriver
service = Service(executable_path=chrome_driver_path)
driver = webdriver.Chrome(service=service, options=chrome_options)

# Buka situs web
driver.get('https://www.google.com')

# Ambil judul halaman
print(driver.title)

# Tutup browser
driver.quit()
