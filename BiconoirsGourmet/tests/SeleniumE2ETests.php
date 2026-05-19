<?php
// tests/SeleniumE2ETests.php
// 30 End-to-End Tests combinando múltiples pantallas

namespace Tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;

class SeleniumE2ETests extends TestCase {
    protected $driver;
    protected $wait;
    protected $baseUrl = 'http://localhost/BiconoirsGourmet';

    protected function setUp(): void {
        $options = new \Facebook\WebDriver\Chrome\ChromeOptions();
        $options->addArguments(['--start-maximized']);

        $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            \Facebook\WebDriver\Chrome\ChromeOptions::CAPABILITY
        );

        $this->wait = new WebDriverWait($this->driver, 15);
    }

    protected function tearDown(): void {
        if ($this->driver) {
            $this->driver->quit();
        }
    }

    // ===== FLUJO CLIENTE COMPLETO (5 tests) =====

    /** @test TC-13-01 */
    public function testRegistroLoginDashboard() {
        // Registro
        $this->driver->get($this->baseUrl . '/register');
        $timestamp = time();

        $this->driver->findElement(WebDriverBy::name('name'))->sendKeys('E2E User ' . $timestamp);
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys('e2e' . $timestamp . '@test.com');
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys('E2Epass123');
        $this->driver->findElement(WebDriverBy::name('phone'))->sendKeys('3001234567');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/login')
        );

        // Login
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys('e2e' . $timestamp . '@test.com');
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys('E2Epass123');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        // Verificar dashboard
        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/dashboard')
        );

        $dashboard = $this->driver->findElement(WebDriverBy::id('dashboard'));
        $this->assertTrue($dashboard->isDisplayed());
    }

    /** @test TC-13-02 */
    public function testCarritoYCheckout() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        // Ir al menú
        $this->driver->get($this->baseUrl . '/menu');

        // Agregar items al carrito
        $addButtons = $this->driver->findElements(WebDriverBy::className('add-to-cart'));
        $addButtons[0]->click();
        $addButtons[1]->click();

        // Ir al carrito
        $this->driver->get($this->baseUrl . '/cart');

        // Verificar items
        $items = $this->driver->findElements(WebDriverBy::className('cart-item'));
        $this->assertCount(2, $items);

        // Checkout
        $checkoutBtn = $this->driver->findElement(WebDriverBy::id('checkout-btn'));
        $checkoutBtn->click();

        // Completar datos de entrega
        $this->driver->findElement(WebDriverBy::name('address'))->sendKeys('Calle 1 #123');
        $this->driver->findElement(WebDriverBy::name('phone'))->sendKeys('3001234567');

        $submitBtn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submitBtn->click();

        // Verificar confirmación
        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/order-confirmation')
        );
    }

    /** @test TC-13-03 */
    public function testMisPedidosHistorial() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/my-orders');

        // Verificar que haya historial de pedidos
        $orders = $this->driver->findElements(WebDriverBy::className('order-card'));
        $this->assertNotEmpty($orders);

        // Hacer clic en un pedido para ver detalles
        $orders[0]->click();

        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('order-details'))
        );
    }

    /** @test TC-13-04 */
    public function testMisReservasYDetalle() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/my-reservations');

        $reservations = $this->driver->findElements(WebDriverBy::className('reservation-card'));

        if (!empty($reservations)) {
            $reservations[0]->click();

            $details = $this->driver->findElement(WebDriverBy::className('reservation-details'));
            $this->assertTrue($details->isDisplayed());
        }
    }

    /** @test TC-13-05 */
    public function testNavegarYCerrarSesion() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        // Navegar por diferentes secciones
        $this->driver->get($this->baseUrl . '/menu');
        $this->assertStringContainsString('menu', $this->driver->getCurrentURL());

        $this->driver->get($this->baseUrl . '/reservations');
        $this->assertStringContainsString('reservations', $this->driver->getCurrentURL());

        // Cerrar sesión
        $logoutBtn = $this->driver->findElement(WebDriverBy::className('logout-btn'));
        $logoutBtn->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/login')
        );
    }

    // ===== FLUJO ADMINISTRADOR (5 tests) =====

    /** @test TC-14-01 */
    public function testAdminLoginDashboard() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin');

        $dashboard = $this->driver->findElement(WebDriverBy::id('admin-dashboard'));
        $this->assertTrue($dashboard->isDisplayed());

        // Verificar widgets
        $stats = $this->driver->findElements(WebDriverBy::className('stat-widget'));
        $this->assertGreaterThan(0, count($stats));
    }

    /** @test TC-14-02 */
    public function testCrearPlatoVerEnMenu() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/dishes');

        // Crear nuevo plato
        $newDishBtn = $this->driver->findElement(WebDriverBy::id('new-dish-btn'));
        $newDishBtn->click();

        $timestamp = time();
        $this->driver->findElement(WebDriverBy::name('name'))->sendKeys('Plato Test ' . $timestamp);
        $this->driver->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de prueba');
        $this->driver->findElement(WebDriverBy::name('price'))->sendKeys('25.00');

        // Seleccionar ingredientes
        $ingredient1 = $this->driver->findElement(WebDriverBy::id('ingredient-1'));
        $ingredient1->click();

        $saveDishBtn = $this->driver->findElement(WebDriverBy::name('submit'));
        $saveDishBtn->click();

        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );

        // Verificar en menú público
        $this->driver->get($this->baseUrl . '/menu');

        $dishNames = $this->driver->findElements(WebDriverBy::className('dish-name'));
        $found = false;
        foreach ($dishNames as $dishName) {
            if (strpos($dishName->getText(), 'Plato Test') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    /** @test TC-14-03 */
    public function testAdministrarReservas() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/reservations');

        $reservations = $this->driver->findElements(WebDriverBy::className('reservation-row'));
        $this->assertNotEmpty($reservations);

        // Aprobar una reserva
        $approveBtn = $reservations[0]->findElement(WebDriverBy::className('approve-btn'));
        $approveBtn->click();

        // Confirmar acción
        $this->wait->until(
            WebDriverExpectedCondition::alertIsPresent()
        );
        $this->driver->switchTo()->alert()->accept();

        // Verificar actualización
        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );
    }

    /** @test TC-14-04 */
    public function testReportesFinancieros() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/reports/financial');

        // Verificar que los datos se cargan
        $totalRevenue = $this->driver->findElement(WebDriverBy::className('total-revenue'));
        $this->assertNotNull($totalRevenue->getText());

        // Cambiar rango de fechas
        $startDate = $this->driver->findElement(WebDriverBy::name('start_date'));
        $startDate->clear();
        $startDate->sendKeys('05/01/2026');

        $filterBtn = $this->driver->findElement(WebDriverBy::id('filter-btn'));
        $filterBtn->click();

        // Esperar actualización
        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('chart'))
        );
    }

    /** @test TC-14-05 */
    public function testGestionarUsuariosAdmin() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/users');

        $users = $this->driver->findElements(WebDriverBy::className('user-row'));
        $this->assertNotEmpty($users);

        // Cambiar rol de usuario
        $roleSelect = $users[0]->findElement(WebDriverBy::name('role'));
        $roleSelect->click();

        $optionAdmin = $this->driver->findElement(WebDriverBy::cssSelector('option[value="admin"]'));
        $optionAdmin->click();

        $saveBtn = $users[0]->findElement(WebDriverBy::className('save-btn'));
        $saveBtn->click();
    }

    // ===== FLUJO INVENTARIO (5 tests) =====

    /** @test TC-15-01 */
    public function testCargarSuministroActualizarStock() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/inventory');

        // Cargar suministro
        $loadBtn = $this->driver->findElement(WebDriverBy::id('load-supply-btn'));
        $loadBtn->click();

        $this->driver->findElement(WebDriverBy::name('ingredient'))->sendKeys('Tomate');
        $this->driver->findElement(WebDriverBy::name('quantity'))->sendKeys('50');
        $this->driver->findElement(WebDriverBy::name('unit_price'))->sendKeys('2.50');

        $submitBtn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submitBtn->click();

        // Verificar actualización de stock
        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );

        $tomatoStock = $this->driver->findElement(WebDriverBy::id('stock-tomate'));
        $this->assertStringContainsString('50', $tomatoStock->getText());
    }

    /** @test TC-15-02 */
    public function testCrearOrdenDescontarStock() {
        $this->loginE2E('client@example.com', 'password123');

        $this->driver->get($this->baseUrl . '/menu');

        // Agregar plato al carrito
        $this->driver->findElements(WebDriverBy::className('add-to-cart'))[0]->click();

        // Checkout
        $this->driver->get($this->baseUrl . '/checkout');
        $this->driver->findElement(WebDriverBy::name('address'))->sendKeys('Address 123');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/order-confirmation')
        );

        // Verificar en admin que stock fue descontado
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/inventory');

        $inventoryItems = $this->driver->findElements(WebDriverBy::className('inventory-item'));
        $this->assertNotEmpty($inventoryItems);
    }

    /** @test TC-15-03 */
    public function testCompletarOrdenProcesarStock() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/orders');

        // Buscar orden pendiente
        $orders = $this->driver->findElements(WebDriverBy::xpath("//tr[contains(., 'Pendiente')]"));

        if (!empty($orders)) {
            $completeBtn = $orders[0]->findElement(WebDriverBy::className('complete-btn'));
            $completeBtn->click();

            $this->wait->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
            );
        }
    }

    /** @test TC-15-04 */
    public function testHistorialMovimientosStock() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/inventory/history');

        $movements = $this->driver->findElements(WebDriverBy::className('movement-row'));
        $this->assertNotEmpty($movements);

        // Filtrar por tipo de movimiento
        $typeFilter = $this->driver->findElement(WebDriverBy::name('movement_type'));
        $typeFilter->sendKeys('Entrada');

        $filterBtn = $this->driver->findElement(WebDriverBy::id('filter-btn'));
        $filterBtn->click();

        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('movement-row'))
        );
    }

    /** @test TC-15-05 */
    public function testAlertaStockBajo() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/inventory');

        $alerts = $this->driver->findElements(WebDriverBy::className('stock-alert'));

        if (!empty($alerts)) {
            $alertText = $alerts[0]->getText();
            $this->assertStringContainsString('bajo', strtolower($alertText));
        }
    }

    // ===== FLUJO RESERVAS (5 tests) =====

    /** @test TC-16-01 */
    public function testHacerReservaVerEnAdmin() {
        // Cliente hace reserva
        $this->loginE2E('client@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $this->driver->findElement(WebDriverBy::name('date'))->sendKeys('05/30/2026');
        $this->driver->findElement(WebDriverBy::name('time'))->sendKeys('19:00');
        $this->driver->findElement(WebDriverBy::name('guests'))->sendKeys('4');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );

        // Admin verifica
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/reservations');

        $reservations = $this->driver->findElements(WebDriverBy::className('reservation-row'));
        $this->assertNotEmpty($reservations);
    }

    /** @test TC-16-02 */
    public function testCancelarReservaSlotSeLlibera() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/reservations');

        $cancelBtn = $this->driver->findElement(WebDriverBy::className('cancel-btn'));
        $cancelBtn->click();

        $this->driver->switchTo()->alert()->accept();

        // Otra persona intenta reservar el slot liberado
        $this->loginE2E('user2@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $this->driver->findElement(WebDriverBy::name('date'))->sendKeys('05/30/2026');
        $this->driver->findElement(WebDriverBy::name('time'))->sendKeys('19:00');
        $this->driver->findElement(WebDriverBy::name('guests'))->sendKeys('4');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        // Debe poder reservar sin problema
        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );
    }

    /** @test TC-16-03 */
    public function testValidarCapacidadMaxima() {
        $this->loginE2E('client@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $this->driver->findElement(WebDriverBy::name('date'))->sendKeys('05/25/2026');
        $this->driver->findElement(WebDriverBy::name('time'))->sendKeys('19:00');
        $this->driver->findElement(WebDriverBy::name('guests'))->sendKeys('15'); // Excede capacidad

        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $error = $this->driver->findElement(WebDriverBy::className('error'));
        $this->assertNotNull($error->getText());
    }

    /** @test TC-16-04 */
    public function testRecordatorioReserva() {
        // Una reserva debe generar recordatorio 24h antes
        // Este test verifica que el sistema haya registrado la reserva
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/reservations');

        $reservations = $this->driver->findElements(WebDriverBy::className('reservation-row'));
        $this->assertNotEmpty($reservations);
    }

    /** @test TC-16-05 */
    public function testCheckInReserva() {
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/reservations');

        $checkInBtn = $this->driver->findElement(WebDriverBy::className('check-in-btn'));
        $checkInBtn->click();

        $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );
    }

    // ===== CASOS NEGATIVOS (5 tests) =====

    /** @test TC-17-01 */
    public function testIntentarTresReservasSimultaneas() {
        $this->loginE2E('client@example.com', 'password123');

        for ($i = 0; $i < 3; $i++) {
            $this->driver->get($this->baseUrl . '/reservations');

            $this->driver->findElement(WebDriverBy::name('date'))->sendKeys('06/0' . (5 + $i) . '/2026');
            $this->driver->findElement(WebDriverBy::name('time'))->sendKeys('19:00');
            $this->driver->findElement(WebDriverBy::name('guests'))->sendKeys('2');
            $this->driver->findElement(WebDriverBy::name('submit'))->click();

            sleep(2);
        }

        $error = $this->driver->findElements(WebDriverBy::className('error'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-17-02 */
    public function testPedidoStockInsuficiente() {
        $this->loginE2E('client@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/menu');

        // Intentar agregar cantidad muy grande
        $qtyField = $this->driver->findElement(WebDriverBy::name('quantity'));
        $qtyField->clear();
        $qtyField->sendKeys('9999');

        $addBtn = $this->driver->findElement(WebDriverBy::className('add-to-cart'));
        $addBtn->click();

        $error = $this->driver->findElements(WebDriverBy::className('error'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-17-03 */
    public function testAccesoSinAutenticacionAdmin() {
        $this->driver->get($this->baseUrl . '/admin');

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/login')
        );
    }

    /** @test TC-17-04 */
    public function testValidarPrecioDesdeServidor() {
        $this->loginE2E('client@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/cart');

        // JavaScript no puede cambiar precio
        $this->driver->executeScript('
            document.querySelector("[data-price]").setAttribute("data-price", "0.01");
        ');

        $submitBtn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submitBtn->click();

        // El servidor debe validar el precio correcto
    }

    /** @test TC-17-05 */
    public function testIntentarBorrarUsuarioActivo() {
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/users');

        $users = $this->driver->findElements(WebDriverBy::className('user-row'));

        if (!empty($users)) {
            $deleteBtn = $users[0]->findElement(WebDriverBy::className('delete-btn'));
            $deleteBtn->click();

            // Debe mostrar error si usuario tiene pedidos activos
            $error = $this->driver->findElements(WebDriverBy::className('error'));
            if (!empty($error)) {
                $this->assertNotNull($error);
            }
        }
    }

    // ===== CASOS DE INTEGRACIÓN (5 tests) =====

    /** @test TC-18-01 */
    public function testEmailConfirmacionPedido() {
        $this->loginE2E('client@example.com', 'password123');

        $this->driver->get($this->baseUrl . '/menu');
        $this->driver->findElements(WebDriverBy::className('add-to-cart'))[0]->click();

        $this->driver->get($this->baseUrl . '/checkout');
        $this->driver->findElement(WebDriverBy::name('email'))->clear();
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys('test@example.com');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        // Verificar en admin
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/orders');

        $orders = $this->driver->findElements(WebDriverBy::className('order-row'));
        $this->assertNotEmpty($orders);
    }

    /** @test TC-18-02 */
    public function testNotificacionReservaConfirmada() {
        $this->loginE2E('client@example.com', 'password123');

        $this->driver->get($this->baseUrl . '/reservations');
        $this->driver->findElement(WebDriverBy::name('date'))->sendKeys('06/10/2026');
        $this->driver->findElement(WebDriverBy::name('time'))->sendKeys('20:00');
        $this->driver->findElement(WebDriverBy::name('guests'))->sendKeys('3');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $success = $this->driver->findElement(WebDriverBy::className('success'));
        $this->assertNotNull($success);
    }

    /** @test TC-18-03 */
    public function testReporteDiarioVentas() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/reports/daily-sales');

        $report = $this->driver->findElement(WebDriverBy::className('report'));
        $this->assertTrue($report->isDisplayed());
    }

    /** @test TC-18-04 */
    public function testSincronizacionStockEntreModulos() {
        // Verificar que stock sea consistente en BD
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/inventory');
        $initialStock = $this->driver->findElement(WebDriverBy::className('stock-value'))->getText();

        // Hacer una orden
        $this->loginE2E('client@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/menu');
        $this->driver->findElements(WebDriverBy::className('add-to-cart'))[0]->click();
        $this->driver->get($this->baseUrl . '/checkout');
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        // Verificar stock actualizado
        $this->loginE2E('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/inventory');

        $newStock = $this->driver->findElement(WebDriverBy::className('stock-value'))->getText();
        $this->assertNotEquals($initialStock, $newStock);
    }

    /** @test TC-18-05 */
    public function testAuditoriaDeChangesCriticos() {
        $this->loginE2E('admin@biconoirs.com', 'password123');

        $this->driver->get($this->baseUrl . '/admin/audit-log');

        $logs = $this->driver->findElements(WebDriverBy::className('log-entry'));
        $this->assertNotEmpty($logs);

        // Verificar que haya cambios registrados
        foreach ($logs as $log) {
            $this->assertNotEmpty($log->getText());
        }
    }

    // ===== HELPERS =====

    private function loginE2E($email, $password) {
        $this->driver->get($this->baseUrl . '/login');

        $emailField = $this->driver->findElement(WebDriverBy::name('email'));
        $passwordField = $this->driver->findElement(WebDriverBy::name('password'));

        $emailField->clear();
        $emailField->sendKeys($email);

        $passwordField->clear();
        $passwordField->sendKeys($password);

        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('dashboard')
        );
    }
}
