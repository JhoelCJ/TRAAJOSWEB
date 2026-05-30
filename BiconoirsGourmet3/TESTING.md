# 90 Test Cases - BiconoirsGourmet

Documentación completa de los 90 test cases automatizados para el sistema de gestión de restaurante BiconoirsGourmet.

## 📋 Estructura de Test Cases

### Total: 90 Test Cases
- **30 Frontend UI Tests** (Selenium) - Validación de interfaz de usuario
- **30 Backend Unit Tests** (PHPUnit) - Validación de lógica de negocio
- **30 E2E Integration Tests** (Selenium) - Validación de flujos completos

## 🚀 Requisitos de Instalación

### Dependencias Globales
```bash
# PHP 7.4+
php --version

# Composer
composer --version

# Chrome/Chromium para Selenium
# Descargar desde: https://chromedriver.chromium.org/
```

### Instalación de Dependencias del Proyecto
```bash
cd BiconoirsGourmet

# Instalar dependencias PHP
composer install

# Dependencias necesarias:
# - phpunit/phpunit ^9.5
# - facebook/webdriver
# - monolog/monolog
```

### composer.json
```json
{
  "require": {
    "phpunit/phpunit": "^9.5",
    "facebook/webdriver": "^1.13",
    "monolog/monolog": "^2.0"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```

## 📁 Estructura de Archivos de Test

```
BiconoirsGourmet/
├── tests/
│   ├── generate_test_cases.php        # Generador de documentación
│   ├── 90_test_cases.html            # Documentación en HTML
│   ├── 90_test_cases.txt             # Documentación en texto
│   ├── SeleniumFrontendTests.php     # 30 tests de Frontend
│   ├── PHPUnitBackendTests.php       # 30 tests de Backend
│   ├── SeleniumE2ETests.php          # 30 tests E2E
│   ├── bootstrap.php                  # Configuración de PHPUnit
│   └── coverage/                      # Reporte de cobertura
├── phpunit.xml                        # Configuración de PHPUnit
└── composer.json
```

## 🔧 Configuración Previa

### 1. Iniciar Servidor de Desarrollo
```bash
# Opción 1: PHP built-in server
cd BiconoirsGourmet/public
php -S localhost:8000

# Opción 2: Apache (en XAMPP)
# Colocar en: C:\xampp\htdocs\BiconoirsGourmet
# Acceder: http://localhost/BiconoirsGourmet
```

### 2. Configurar Selenium
```bash
# Descargar ChromeDriver
# https://chromedriver.chromium.org/

# Iniciar Selenium en su propia terminal
java -jar selenium-server-4.0.0.jar

# O usar Docker:
docker run -d -p 4444:4444 selenium/standalone-chrome
```

### 3. Base de Datos
```bash
# Crear database de prueba
mysql -u root -p
CREATE DATABASE biconoirs_test;

# Importar schema
mysql -u root -p biconoirs_test < schema.sql
```

### 4. Variables de Entorno
```bash
# Crear archivo .env.test en raíz del proyecto
cp .env .env.test

# Editar .env.test con configuración de prueba:
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=biconoirs_test
APP_URL=http://localhost:8000
```

## ▶️ Ejecutar Test Cases

### Ejecutar Todos los Tests
```bash
# Desde la raíz del proyecto
vendor/bin/phpunit

# Con output verbose
vendor/bin/phpunit -v

# Con log detallado
vendor/bin/phpunit --log-junit tests/results.xml
```

### Ejecutar por Categoría

#### 1. Tests de Frontend (UI Tests)
```bash
vendor/bin/phpunit --testsuite "Frontend UI Tests"

# O solo un test específico
vendor/bin/phpunit tests/SeleniumFrontendTests.php::SeleniumFrontendTests::testLoginValido
```

#### 2. Tests de Backend (Unit Tests)
```bash
vendor/bin/phpunit --testsuite "Backend Unit Tests"
```

#### 3. Tests E2E (Integration Tests)
```bash
vendor/bin/phpunit --testsuite "E2E Integration Tests"
```

### Ejecutar Tests Específicos

```bash
# Tests de autenticación
vendor/bin/phpunit --filter "Auth"

# Tests de carrito
vendor/bin/phpunit --filter "Carrito"

# Tests de reservas
vendor/bin/phpunit --filter "Reserva"

# Por código TC
vendor/bin/phpunit --filter "TC-01"
```

### Opciones Útiles
```bash
# Ejecutar en paralelo (más rápido)
vendor/bin/phpunit --processes 4

# Con cobertura de código
vendor/bin/phpunit --coverage-html tests/coverage

# Stop en primer error
vendor/bin/phpunit --stop-on-failure

# Mostrar solo tests que fallan
vendor/bin/phpunit --verbose --no-coverage
```

## 📊 Resultados y Reportes

### Generar Reportes de Cobertura
```bash
# HTML Report
vendor/bin/phpunit --coverage-html tests/coverage

# Abrir reporte
open tests/coverage/index.html

# Text Report
vendor/bin/phpunit --coverage-text

# XML Report (para CI/CD)
vendor/bin/phpunit --coverage-clover tests/coverage.xml
```

### Archivos de Salida
- `tests/results.xml` - Resultados en formato JUnit XML
- `tests/coverage/` - Reporte HTML de cobertura
- `tests/coverage.xml` - Reporte Clover XML

## 📝 Detalle de Test Cases

### PARTE 1: FRONTEND / SELENIUM (30 Tests)

#### Feature 1: Autenticación (5 tests)
- TC-01-01: Cargar página de login
- TC-01-02: Validar email requerido
- TC-01-03: Validar contraseña requerida
- TC-01-04: Validar formato email incorrecto
- TC-01-05: Enviar formulario válido

#### Feature 2: Registro (5 tests)
- TC-02-01: Cargar página de registro
- TC-02-02: Validar campos requeridos
- TC-02-03: Validar email duplicado
- TC-02-04: Validar contraseña corta
- TC-02-05: Registro exitoso

#### Feature 3: Carrito (5 tests)
- TC-03-01: Agregar plato al carrito
- TC-03-02: Aumentar cantidad
- TC-03-03: Eliminar plato del carrito
- TC-03-04: Calcular total carrito
- TC-03-05: Checkout con carrito vacío

#### Feature 4: Reservas (5 tests)
- TC-04-01: Abrir formulario de reserva
- TC-04-02: Validar fecha pasada
- TC-04-03: Validar cantidad de personas
- TC-04-04: Intentar reservar con slot lleno
- TC-04-05: Reserva exitosa

#### Feature 5: Encuesta (5 tests)
- TC-05-01: Cargar formulario de encuesta
- TC-05-02: Validar respuestas requeridas
- TC-05-03: Seleccionar opciones
- TC-05-04: Agregar comentario
- TC-05-05: Enviar encuesta

#### Feature 6: Administración (5 tests)
- TC-06-01: Acceso sin permiso de admin
- TC-06-02: Cargar dashboard admin
- TC-06-03: Filtrar tabla de pedidos
- TC-06-04: Buscar en tabla
- TC-06-05: Exportar datos

### PARTE 2: BACKEND / PHPUNIT (30 Tests)

#### Feature 7: Autenticación (5 tests)
- TC-07-01: Autenticar con credenciales correctas
- TC-07-02: Autenticar con credenciales incorrectas
- TC-07-03: Validar token expirado
- TC-07-04: Refresh token
- TC-07-05: Logout

#### Feature 8: Gestión de Usuarios (5 tests)
- TC-08-01: Crear usuario con datos válidos
- TC-08-02: Crear usuario con email duplicado
- TC-08-03: Actualizar perfil de usuario
- TC-08-04: Eliminar usuario (soft delete)
- TC-08-05: Listar usuarios con paginación

#### Feature 9: Gestión de Platos (5 tests)
- TC-09-01: Crear plato con ingredientes
- TC-09-02: Crear plato duplicado activo
- TC-09-03: Reutilizar plato inactivo
- TC-09-04: Soft delete plato
- TC-09-05: Bloquear eliminación por pedido activo

#### Feature 10: Gestión de Pedidos (5 tests)
- TC-10-01: Crear orden con items
- TC-10-02: Actualizar estado: Pendiente → Completado
- TC-10-03: Calcular total con descuento
- TC-10-04: Validar stock insuficiente
- TC-10-05: Cancelar orden activa

#### Feature 11: Gestión de Reservas (5 tests)
- TC-11-01: Crear reserva con capacidad disponible
- TC-11-02: Validar mesas llenas en horario
- TC-11-03: Límite 2 reservas activas por usuario
- TC-11-04: Cancelar reserva
- TC-11-05: Listar reservas por rango de fechas

#### Feature 12: Encuestas (5 tests)
- TC-12-01: Guardar encuesta con respuestas
- TC-12-02: Validar respuestas requeridas
- TC-12-03: Calcular promedio calificación
- TC-12-04: Filtrar encuestas por rango fechas
- TC-12-05: Generar reporte encuestas

### PARTE 3: E2E / SELENIUM (30 Tests)

#### Feature 13: Flujo Cliente Completo (5 tests)
- TC-13-01: Registro → Login → Dashboard
- TC-13-02: Ver menú → Agregar al carrito → Checkout
- TC-13-03: Checkout → Generar pedido → Mis Pedidos
- TC-13-04: Hacer reserva → Mis Reservas
- TC-13-05: Navegar y cerrar sesión

#### Feature 14: Flujo Administrador (5 tests)
- TC-14-01: Login admin → Ver dashboard
- TC-14-02: Crear plato → Ver en menú público
- TC-14-03: Administrar reservas → Aprobar/Cancelar
- TC-14-04: Ver reportes financieros
- TC-14-05: Gestionar usuarios → Cambiar rol

#### Feature 15: Flujo Inventario (5 tests)
- TC-15-01: Cargar suministro → Stock actualizado
- TC-15-02: Crear orden → Descontar stock
- TC-15-03: Completar orden → Stock procesado
- TC-15-04: Historial de movimientos de stock
- TC-15-05: Alerta de stock bajo

#### Feature 16: Flujo Reservas (5 tests)
- TC-16-01: Hacer reserva → Verificar en admin
- TC-16-02: Cancelar reserva → Slot se libera
- TC-16-03: Validar capacidad máxima
- TC-16-04: Recordatorio de reserva (24h)
- TC-16-05: Check-in de reserva

#### Feature 17: Casos Negativos (5 tests)
- TC-17-01: Intentar 3+ reservas simultáneas
- TC-17-02: Pedido con stock insuficiente
- TC-17-03: Acceso sin autenticación a admin
- TC-17-04: Modificar precio en carrito (XSS)
- TC-17-05: Intentar borrar usuario activo

#### Feature 18: Casos de Integración (5 tests)
- TC-18-01: Email de confirmación de pedido
- TC-18-02: Notificación de reserva confirmada
- TC-18-03: Reporte diario de ventas
- TC-18-04: Sincronización de stock entre módulos
- TC-18-05: Auditoría de cambios críticos

## 🐛 Troubleshooting

### Error: "Connection refused" en Selenium
```bash
# Verificar que Selenium esté corriendo
# En otra terminal:
java -jar selenium-server-4.0.0.jar

# O con Docker:
docker run -d -p 4444:4444 selenium/standalone-chrome
```

### Error: "DB Connection failed"
```bash
# Verificar archivo .env.test
# Asegurar que BD de prueba exista
mysql -u root -p biconoirs_test
```

### Error: "Test time out"
```bash
# Aumentar timeout en phpunit.xml
<testsuite name="Frontend UI Tests" timeout="30">
```

### Error: "Chrome driver version mismatch"
```bash
# Descargar versión compatible de ChromeDriver
# Verificar versión de Chrome:
google-chrome --version

# Descargar ChromeDriver coincidente de:
# https://chromedriver.chromium.org/
```

## 📈 Monitoreo Continuo

### GitHub Actions / CI/CD
```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:5.7
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: biconoirs_test

    steps:
      - uses: actions/checkout@v2
      - uses: php-actions/composer@v6
      - run: vendor/bin/phpunit --coverage-clover coverage.xml
      - uses: codecov/codecov-action@v2
```

## 📚 Referencias Útiles

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Selenium WebDriver PHP](https://github.com/php-webdriver/php-webdriver)
- [Testing Best Practices](https://testingjavascript.com/)

## 📝 Notas Importantes

1. **Tests deben ser independientes** - Cada test puede ejecutarse en cualquier orden
2. **Cleanup después de cada test** - Limpiar BD y archivos temporales
3. **Usar fixtures** - Crear datos de prueba reutilizables
4. **Documentar fallos** - Guardar screenshots y logs en caso de error
5. **Mantener tests actualizados** - Actualizar cuando cambie la lógica

## 👨‍💻 Autores y Mantenimiento

- Generado: 2026-05-13
- Sistema: BiconoirsGourmet v1.0
- Mantenedor: Equipo QA
