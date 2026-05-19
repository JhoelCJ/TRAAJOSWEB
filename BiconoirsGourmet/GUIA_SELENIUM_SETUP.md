# 🚀 Guía de Configuración: Selenium + Servidor Web

## PASO 1: Descargar ChromeDriver

### ✅ 1. Verificar versión de Chrome/Chromium
```bash
# En Windows (PowerShell o CMD)
"C:\Program Files\Google\Chrome\Application\chrome.exe" --version

# En Mac/Linux
google-chrome --version
# o
chromium-browser --version
```

**Ejemplo de salida:**
```
Google Chrome version 126.0.6478.123
```

### ✅ 2. Descargar ChromeDriver compatible
1. Ve a: https://chromedriver.chromium.org/
2. Haz clic en "Latest stable release"
3. Descarga la versión que coincida con tu Chrome (e.g., 126.0.6478.123)
4. Selecciona tu SO: Windows/Mac/Linux

**Enlaces directos rápidos:**
- Windows: https://edgedl.me/chromedriver-win64.zip
- Mac (Intel): https://edgedl.me/chromedriver-mac-x64.zip
- Mac (ARM): https://edgedl.me/chromedriver-mac-arm64.zip
- Linux: https://edgedl.me/chromedriver-linux64.zip

### ✅ 3. Instalar ChromeDriver
```bash
# Windows
# 1. Extrae el ZIP descargado
# 2. Copia chromedriver.exe a una carpeta accesible, por ejemplo:
#    C:\tools\chromedriver.exe
# 3. Agrega a PATH del sistema (opcional)

# Mac/Linux
# 1. Extrae el archivo
# 2. Dale permisos: chmod +x chromedriver
# 3. Copia a /usr/local/bin/ o similar
cd ~/Downloads
unzip chromedriver-mac-x64.zip
sudo mv chromedriver-mac-x64/chromedriver /usr/local/bin/
chmod +x /usr/local/bin/chromedriver
```

---

## PASO 2: Configurar Selenium Server

### Opción A: Docker (RECOMENDADO - más fácil)

```bash
# 1. Asegúrate de tener Docker instalado
# Descarga desde: https://www.docker.com/products/docker-desktop

# 2. Inicia Selenium en un contenedor
docker run -d -p 4444:4444 -p 7900:7900 selenium/standalone-chrome:latest

# 3. Verifica que esté corriendo
curl http://localhost:4444/wd/hub/status

# 4. Ver logs en tiempo real
docker logs -f <container-id>

# 5. Detener cuando termines
docker stop <container-id>
```

### Opción B: Selenium Standalone (descarga directa)

```bash
# 1. Descargar Selenium Server
# https://www.selenium.dev/downloads/
# O descarga desde: https://github.com/SeleniumHQ/selenium/releases

# Ejemplo: versión 4.15.0
wget https://github.com/SeleniumHQ/selenium/releases/download/selenium-4.15.0/selenium-server-4.15.0.jar

# 2. Requiere Java instalado
java -version

# 3. Inicia Selenium Server
java -jar selenium-server-4.15.0.jar

# Salida esperada:
# 01:23:45.678 INFO [LoggingOptions.configureLogging] - Selenium logging enabled at level: INFO
# 01:23:45.923 INFO [GridLauncher.launch] - Selenium Grid hub started
# Listening on http://127.0.0.1:4444
```

### Opción C: ChromeDriver directo (más simple)

```bash
# Inicia ChromeDriver directamente en modo webdriver
# Windows
chromedriver.exe --port=4444

# Mac/Linux
./chromedriver --port=4444

# Salida esperada:
# Starting ChromeDriver 126.0.6478.123
# Only local connections are allowed.
# Please see https://chromedriver.chromium.org/security-considerations for suggestions on keeping ChromeDriver safe.
# ChromeDriver was started successfully.
```

---

## PASO 3: Configurar y Ejecutar Servidor Web

### Opción A: XAMPP (Ya tienes instalado)

```bash
# 1. Abre XAMPP Control Panel
# 2. Click en "Start" para Apache
# 3. El servidor corre en: http://localhost/

# O usa línea de comandos:
# Windows
"C:\xampp\apache\bin\httpd.exe"

# Mac/Linux
/Applications/XAMPP/xamppfiles/bin/apachectl start
```

### Opción B: PHP Built-in Server (MÁS SIMPLE)

```bash
# 1. Navega a la carpeta del proyecto
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet\public

# 2. Inicia el servidor PHP
php -S localhost:8000

# Salida esperada:
# Development Server (http://127.0.0.1:8000)
# Press Ctrl-C to quit.

# 3. El servidor corre en: http://localhost:8000
```

### Opción C: Python SimpleHTTPServer

```bash
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet\public
python -m http.server 8000
# o si tienes Python 2
python -m SimpleHTTPServer 8000
```

---

## PASO 4: Configurar URL en los Tests

Edita el archivo `tests/SeleniumFrontendTests.php`:

```php
// Línea 15 aprox.
protected $baseUrl = 'http://localhost:8000';  // ← Cambia según tu servidor
```

Edita el archivo `tests/SeleniumE2ETests.php`:

```php
// Línea 18 aprox.
protected $baseUrl = 'http://localhost:8000';  // ← Cambia según tu servidor
```

---

## PASO 5: CONFIGURACIÓN COMPLETA - EJEMPLO

Abre **4 terminales/CMD** y ejecuta en orden:

### Terminal 1: Inicia ChromeDriver (o Selenium Server)
```bash
# Windows
C:\tools\chromedriver.exe --port=4444

# O con Docker
docker run -d -p 4444:4444 selenium/standalone-chrome:latest
```

### Terminal 2: Inicia Servidor Web
```bash
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet\public
php -S localhost:8000
```

### Terminal 3: Verifica que todo esté funcionando
```bash
# Verifica Selenium
curl http://localhost:4444/wd/hub/status

# Verifica servidor web
curl http://localhost:8000
```

### Terminal 4: Ejecuta los Tests
```bash
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet

# Backend tests (ya ejecutados)
vendor/bin/phpunit --testsuite "Backend Tests"

# Frontend tests
vendor/bin/phpunit --testsuite "Frontend UI Tests" -v

# E2E tests
vendor/bin/phpunit --testsuite "E2E Integration Tests" -v

# Todos
vendor/bin/phpunit
```

---

## VERIFICACIÓN: ¿Está todo funcionando?

```bash
# 1. ¿ChromeDriver está escuchando?
curl -X POST http://localhost:4444/wd/hub/session \
  -H "Content-Type: application/json" \
  -d '{"desiredCapabilities":{"browserName":"chrome"}}'

# Respuesta esperada: {"sessionId": "..."}

# 2. ¿Servidor web está disponible?
curl http://localhost:8000

# Respuesta: HTML del proyecto

# 3. ¿PHPUnit está instalado?
vendor/bin/phpunit --version

# Respuesta: PHPUnit 9.6.34
```

---

## TROUBLESHOOTING

### ❌ Error: "Connection refused on 4444"
```
Solución:
- ChromeDriver no está corriendo
- Inicia: chromedriver --port=4444
- O usa Docker: docker run -d -p 4444:4444 selenium/standalone-chrome
```

### ❌ Error: "Port 4444 already in use"
```
Windows:
netstat -ano | findstr :4444
taskkill /PID <PID> /F

Mac/Linux:
lsof -i :4444
kill -9 <PID>

O cambia el puerto: chromedriver --port=4445
```

### ❌ Error: "Cannot find PHP"
```
Solución:
- Verifica que XAMPP esté instalado: php --version
- Usa ruta completa: C:\xampp\php\php.exe -S localhost:8000
```

### ❌ Error: "Connection refused to server"
```
Solución:
- Verifica que el servidor esté corriendo: http://localhost:8000
- Revisa el firewall
- Usa 127.0.0.1 en lugar de localhost
```

### ❌ ChromeDriver version mismatch
```
Solución:
# Verifica versión de Chrome
chrome --version

# Descarga ChromeDriver que coincida exactamente
# https://chromedriver.chromium.org/downloads
```

---

## SCRIPT AUTOMATIZADO (WINDOWS)

Crea un archivo `run-tests.bat`:

```batch
@echo off
echo Starting Selenium...
start cmd /k "chromedriver --port=4444"

echo Waiting 3 seconds...
timeout /t 3

echo Starting PHP Server...
start cmd /k "cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet\public && php -S localhost:8000"

echo Waiting 2 seconds...
timeout /t 2

echo Starting Tests...
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet
vendor\bin\phpunit

pause
```

Luego solo ejecuta: `run-tests.bat`

---

## SCRIPT AUTOMATIZADO (MAC/LINUX)

Crea un archivo `run-tests.sh`:

```bash
#!/bin/bash

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Starting ChromeDriver...${NC}"
chromedriver --port=4444 &
CHROMEDRIVER_PID=$!

sleep 3

echo -e "${YELLOW}Starting PHP Server...${NC}"
cd /path/to/BiconoirsGourmet/public
php -S localhost:8000 &
SERVER_PID=$!

sleep 2

echo -e "${YELLOW}Running Tests...${NC}"
cd /path/to/BiconoirsGourmet
vendor/bin/phpunit

echo -e "${YELLOW}Cleaning up...${NC}"
kill $CHROMEDRIVER_PID
kill $SERVER_PID

echo -e "${GREEN}Done!${NC}"
```

Dale permisos: `chmod +x run-tests.sh`
Ejecuta: `./run-tests.sh`

---

## RESUMEN RÁPIDO

| Componente | Comando | Puerto |
|-----------|---------|--------|
| **ChromeDriver** | `chromedriver --port=4444` | 4444 |
| **Servidor Web** | `php -S localhost:8000` | 8000 |
| **Tests Backend** | `vendor/bin/phpunit --testsuite "Backend Tests"` | - |
| **Tests Frontend** | `vendor/bin/phpunit --testsuite "Frontend UI Tests"` | - |
| **Tests E2E** | `vendor/bin/phpunit --testsuite "E2E Integration Tests"` | - |

---

## ¿LISTO? EJECUTA ESTO:

```bash
# Terminal 1
chromedriver --port=4444

# Terminal 2
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet\public
php -S localhost:8000

# Terminal 3
cd C:\xampp\htdocs\WEBAVTRABGIT\TRAAJOSWEB\BiconoirsGourmet
vendor/bin/phpunit -v
```

¡Y listo! Los 90 tests se ejecutarán automáticamente 🎉
