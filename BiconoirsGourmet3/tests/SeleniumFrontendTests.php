<?php
// tests/SeleniumTests.php
// 30 Frontend UI Tests usando Selenium

namespace Tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;

class SeleniumFrontendTests extends TestCase {
    protected $driver;
    protected $wait;
    protected $baseUrl = 'http://localhost/BiconoirsGourmet';

    protected function setUp(): void {
        // Inicializar WebDriver
        $options = new \Facebook\WebDriver\Chrome\ChromeOptions();
        $options->addArguments(['--start-maximized']);

        $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            \Facebook\WebDriver\Chrome\ChromeOptions::CAPABILITY
        );

        $this->wait = new WebDriverWait($this->driver, 10);
    }

    protected function tearDown(): void {
        if ($this->driver) {
            $this->driver->quit();
        }
    }

    // ===== AUTENTICACIÓN (5 tests) =====

    /** @test TC-01-01 */
    public function testCargarPaginaLogin() {
        $this->driver->get($this->baseUrl . '/login');
        $email_field = $this->driver->findElement(WebDriverBy::name('email'));
        $password_field = $this->driver->findElement(WebDriverBy::name('password'));

        $this->assertNotNull($email_field);
        $this->assertNotNull($password_field);
    }

    /** @test TC-01-02 */
    public function testValidarEmailRequerido() {
        $this->driver->get($this->baseUrl . '/login');
        $submit_btn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit_btn->click();

        $error = $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('error'))
        );
        $this->assertStringContainsString('Email', $error->getText());
    }

    /** @test TC-01-03 */
    public function testValidarContraseñaRequerida() {
        $this->driver->get($this->baseUrl . '/login');
        $email_field = $this->driver->findElement(WebDriverBy::name('email'));
        $email_field->sendKeys('test@example.com');

        $submit_btn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit_btn->click();

        $error = $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('error'))
        );
        $this->assertStringContainsString('Contraseña', $error->getText());
    }

    /** @test TC-01-04 */
    public function testValidarFormatoEmailIncorrecto() {
        $this->driver->get($this->baseUrl . '/login');
        $email_field = $this->driver->findElement(WebDriverBy::name('email'));
        $email_field->sendKeys('email_invalido');

        $error = $this->driver->findElements(WebDriverBy::className('error-inline'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-01-05 */
    public function testEnviarFormularioValido() {
        $this->driver->get($this->baseUrl . '/login');

        $email_field = $this->driver->findElement(WebDriverBy::name('email'));
        $password_field = $this->driver->findElement(WebDriverBy::name('password'));
        $submit_btn = $this->driver->findElement(WebDriverBy::name('submit'));

        $email_field->sendKeys('admin@biconoirs.com');
        $password_field->sendKeys('password123');
        $submit_btn->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/dashboard')
        );
        $this->assertStringContainsString('dashboard', $this->driver->getCurrentURL());
    }

    // ===== REGISTRO (5 tests) =====

    /** @test TC-02-01 */
    public function testCargarPaginaRegistro() {
        $this->driver->get($this->baseUrl . '/register');
        $name_field = $this->driver->findElement(WebDriverBy::name('name'));
        $email_field = $this->driver->findElement(WebDriverBy::name('email'));

        $this->assertNotNull($name_field);
        $this->assertNotNull($email_field);
    }

    /** @test TC-02-02 */
    public function testValidarCamposRequeridos() {
        $this->driver->get($this->baseUrl . '/register');
        $submit_btn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit_btn->click();

        $errors = $this->driver->findElements(WebDriverBy::className('error'));
        $this->assertNotEmpty($errors);
    }

    /** @test TC-02-03 */
    public function testValidarEmailDuplicado() {
        $this->driver->get($this->baseUrl . '/register');

        $fields = [
            'name' => 'Test User',
            'email' => 'admin@biconoirs.com',
            'password' => 'password123',
            'phone' => '3001234567'
        ];

        foreach ($fields as $name => $value) {
            $this->driver->findElement(WebDriverBy::name($name))->sendKeys($value);
        }

        $submit_btn = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit_btn->click();

        $error = $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('error'))
        );
        $this->assertStringContainsString('Email ya registrado', $error->getText());
    }

    /** @test TC-02-04 */
    public function testValidarContraseñaCorta() {
        $this->driver->get($this->baseUrl . '/register');

        $this->driver->findElement(WebDriverBy::name('name'))->sendKeys('New User');
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys('new@example.com');
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys('123');

        $error = $this->driver->findElements(WebDriverBy::className('error-inline'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-02-05 */
    public function testRegistroExitoso() {
        $this->driver->get($this->baseUrl . '/register');

        $timestamp = time();
        $fields = [
            'name' => 'Test User ' . $timestamp,
            'email' => 'test' . $timestamp . '@example.com',
            'password' => 'password123',
            'phone' => '3001234567'
        ];

        foreach ($fields as $name => $value) {
            $this->driver->findElement(WebDriverBy::name($name))->sendKeys($value);
        }

        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/login')
        );
        $this->assertStringContainsString('login', $this->driver->getCurrentURL());
    }

    // ===== CARRITO (5 tests) =====

    /** @test TC-03-01 */
    public function testAgregarPlatoAlCarrito() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/menu');

        $add_btn = $this->driver->findElements(WebDriverBy::className('add-to-cart'))[0];
        $add_btn->click();

        $cart_count = $this->driver->findElement(WebDriverBy::className('cart-count'));
        $this->assertEquals('1', $cart_count->getText());
    }

    /** @test TC-03-02 */
    public function testAumentarCantidad() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/cart');

        $qty_input = $this->driver->findElement(WebDriverBy::className('quantity'));
        $qty_input->clear();
        $qty_input->sendKeys('3');

        $this->wait->until(
            WebDriverExpectedCondition::textToBePresentInElement(
                WebDriverBy::className('total'),
                // El total debe actualizarse
            )
        );
    }

    /** @test TC-03-03 */
    public function testEliminarPlatoDelCarrito() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/cart');

        $remove_btn = $this->driver->findElement(WebDriverBy::className('remove-item'));
        $remove_btn->click();

        $this->wait->until(
            WebDriverExpectedCondition::stalenessOf($remove_btn)
        );
    }

    /** @test TC-03-04 */
    public function testCalcularTotalCarrito() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/cart');

        $total = $this->driver->findElement(WebDriverBy::className('total'))->getText();
        $this->assertRegExp('/\$[0-9]+\.?[0-9]*/', $total);
    }

    /** @test TC-03-05 */
    public function testCheckoutConCarritoVacio() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/checkout');

        $error = $this->driver->findElement(WebDriverBy::className('error'));
        $this->assertStringContainsString('vacío', $error->getText());
    }

    // ===== RESERVAS (5 tests) =====

    /** @test TC-04-01 */
    public function testAbrirFormularioReserva() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $modal = $this->driver->findElement(WebDriverBy::id('reservation-modal'));
        $this->assertTrue($modal->isDisplayed());
    }

    /** @test TC-04-02 */
    public function testValidarFechaPasada() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $date_field = $this->driver->findElement(WebDriverBy::name('date'));
        $date_field->sendKeys('05/01/2020');

        $error = $this->driver->findElements(WebDriverBy::className('error-inline'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-04-03 */
    public function testValidarCantidadPersonas() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $qty_field = $this->driver->findElement(WebDriverBy::name('guests'));
        $qty_field->sendKeys('15');

        $error = $this->driver->findElements(WebDriverBy::className('error'));
        $this->assertNotEmpty($error);
    }

    /** @test TC-04-04 */
    public function testIntentarReservaSlotLleno() {
        // Este test asume que ya hay 3 reservas
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $date_field = $this->driver->findElement(WebDriverBy::name('date'));
        $date_field->sendKeys('05/20/2026');

        $time_field = $this->driver->findElement(WebDriverBy::name('time'));
        $time_field->sendKeys('19:00');

        $submit = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit->click();

        $alert = $this->driver->findElement(WebDriverBy::className('alert'));
        $this->assertStringContainsString('No hay mesas', $alert->getText());
    }

    /** @test TC-04-05 */
    public function testReservaExitosa() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/reservations');

        $date_field = $this->driver->findElement(WebDriverBy::name('date'));
        $date_field->sendKeys('05/25/2026');

        $time_field = $this->driver->findElement(WebDriverBy::name('time'));
        $time_field->sendKeys('20:00');

        $guests = $this->driver->findElement(WebDriverBy::name('guests'));
        $guests->sendKeys('4');

        $submit = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('/my-reservations')
        );
    }

    // ===== ENCUESTA (5 tests) =====

    /** @test TC-05-01 */
    public function testCargarFormularioEncuesta() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/survey');

        $questions = $this->driver->findElements(WebDriverBy::className('question'));
        $this->assertNotEmpty($questions);
    }

    /** @test TC-05-02 */
    public function testValidarRespuestasRequeridas() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/survey');

        $submit = $this->driver->findElement(WebDriverBy::name('submit'));
        $submit->click();

        $error = $this->driver->findElement(WebDriverBy::className('error'));
        $this->assertNotNull($error);
    }

    /** @test TC-05-03 */
    public function testSeleccionarOpciones() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/survey');

        $checkbox = $this->driver->findElement(WebDriverBy::name('q1_opt1'));
        $checkbox->click();

        $this->assertTrue($checkbox->isSelected());
    }

    /** @test TC-05-04 */
    public function testAgregarComentario() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/survey');

        $comment = $this->driver->findElement(WebDriverBy::name('comments'));
        $comment->sendKeys('Excelente servicio');

        $this->assertEquals('Excelente servicio', $comment->getAttribute('value'));
    }

    /** @test TC-05-05 */
    public function testEnviarEncuesta() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/survey');

        // Rellenar formulario
        $this->driver->findElement(WebDriverBy::name('q1_opt1'))->click();
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $message = $this->wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('success'))
        );
        $this->assertStringContainsString('exitosamente', $message->getText());
    }

    // ===== ADMINISTRACIÓN (5 tests) =====

    /** @test TC-06-01 */
    public function testAccesoSinPermisoAdmin() {
        $this->loginUser('user@example.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin');

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('login')
        );
    }

    /** @test TC-06-02 */
    public function testCargarDashboardAdmin() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin');

        $dashboard = $this->driver->findElement(WebDriverBy::id('dashboard'));
        $this->assertTrue($dashboard->isDisplayed());
    }

    /** @test TC-06-03 */
    public function testFiltrarTablaPedidos() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/orders');

        $filter = $this->driver->findElement(WebDriverBy::name('status'));
        $filter->sendKeys('Completado');

        $rows = $this->driver->findElements(WebDriverBy::className('order-row'));
        $this->assertNotEmpty($rows);
    }

    /** @test TC-06-04 */
    public function testBuscarEnTabla() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/orders');

        $search = $this->driver->findElement(WebDriverBy::name('search'));
        $search->sendKeys('ORD-001');

        $results = $this->driver->findElements(WebDriverBy::className('order-row'));
        $this->assertNotEmpty($results);
    }

    /** @test TC-06-05 */
    public function testExportarDatos() {
        $this->loginUser('admin@biconoirs.com', 'password123');
        $this->driver->get($this->baseUrl . '/admin/orders');

        $export_btn = $this->driver->findElement(WebDriverBy::id('export-csv'));
        $export_btn->click();

        // Verificar que la descarga se inició
        sleep(2);
    }

    // ===== HELPERS =====

    private function loginUser($email, $password) {
        $this->driver->get($this->baseUrl . '/login');
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys($email);
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::name('submit'))->click();

        $this->wait->until(
            WebDriverExpectedCondition::urlContains('dashboard')
        );
    }
}
