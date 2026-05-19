<?php
// tests/PHPUnitBackendTests.php
// 30 Backend Unit + Integration Tests

namespace Tests;

use PHPUnit\Framework\TestCase;

class BackendAuthTests extends TestCase {
    private $authController;
    private $db;

    protected function setUp(): void {
        // Mock database connection
        $this->db = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
        // Initialize controller
        $this->authController = new \App\Controllers\AuthController();
    }

    /** @test TC-07-01 */
    public function testAutenticarCredencialesCorrectas() {
        $result = $this->authController->authenticate('admin@biconoirs.com', 'password123');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user_id', $result);
    }

    /** @test TC-07-02 */
    public function testAutenticarCredencialesIncorrectas() {
        $result = $this->authController->authenticate('admin@biconoirs.com', 'wrongpassword');

        $this->assertFalse($result);
    }

    /** @test TC-07-03 */
    public function testValidarTokenExpirado() {
        $expiredToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1NTEwMjM5NDZ9.invalid';
        $result = $this->authController->validateToken($expiredToken);

        $this->assertFalse($result);
    }

    /** @test TC-07-04 */
    public function testRefreshToken() {
        $token = $this->authController->authenticate('admin@biconoirs.com', 'password123');
        $newToken = $this->authController->refreshToken($token['token']);

        $this->assertNotEquals($token['token'], $newToken);
        $this->assertIsString($newToken);
    }

    /** @test TC-07-05 */
    public function testLogout() {
        $token = $this->authController->authenticate('admin@biconoirs.com', 'password123');
        $result = $this->authController->logout($token['token']);

        $this->assertTrue($result);
        $validation = $this->authController->validateToken($token['token']);
        $this->assertFalse($validation);
    }
}

class BackendUserTests extends TestCase {
    private $userModel;

    protected function setUp(): void {
        $this->userModel = new \App\Models\User();
    }

    /** @test TC-08-01 */
    public function testCrearUsuarioConDatosValidos() {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser' . time() . '@example.com',
            'password' => 'securepass123',
            'phone' => '3001234567'
        ];

        $userId = $this->userModel->create($data);

        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);
    }

    /** @test TC-08-02 */
    public function testCrearUsuarioConEmailDuplicado() {
        $data = [
            'name' => 'Duplicate User',
            'email' => 'admin@biconoirs.com',
            'password' => 'password123',
            'phone' => '3001234567'
        ];

        $result = $this->userModel->create($data);

        $this->assertFalse($result);
    }

    /** @test TC-08-03 */
    public function testActualizarPerfilUsuario() {
        $userId = 1;
        $data = ['name' => 'Updated Name', 'phone' => '3009999999'];

        $result = $this->userModel->update($userId, $data);

        $this->assertTrue($result);

        $user = $this->userModel->getById($userId);
        $this->assertEquals('Updated Name', $user['name']);
    }

    /** @test TC-08-04 */
    public function testEliminarUsuario() {
        $userId = 2;
        $result = $this->userModel->softDelete($userId);

        $this->assertTrue($result);

        $user = $this->userModel->getById($userId);
        $this->assertEquals(0, $user['is_active']);
    }

    /** @test TC-08-05 */
    public function testListarUsuariosConPaginacion() {
        $users = $this->userModel->paginate(page: 1, perPage: 10);

        $this->assertIsArray($users);
        $this->assertLessThanOrEqual(10, count($users));
    }
}

class BackendDishTests extends TestCase {
    private $dishModel;

    protected function setUp(): void {
        $this->dishModel = new \App\Models\Dish();
    }

    /** @test TC-09-01 */
    public function testCrearPlatoConIngredientes() {
        $dishData = [
            'name' => 'Plato Test ' . time(),
            'description' => 'Test Description',
            'price' => 15.50,
            'ingredients' => [1, 2, 3]
        ];

        $dishId = $this->dishModel->createWithIngredients($dishData);

        $this->assertIsInt($dishId);

        $dish = $this->dishModel->getById($dishId);
        $this->assertEquals($dishData['name'], $dish['name']);
    }

    /** @test TC-09-02 */
    public function testCrearPlatoDuplicadoActivo() {
        $dishData = [
            'name' => 'Arepa Reina Pepiada',
            'description' => 'Classic Venezuelan',
            'price' => 12.00
        ];

        $result = $this->dishModel->create($dishData);

        $this->assertFalse($result);
    }

    /** @test TC-09-03 */
    public function testReutilizarPlatoInactivo() {
        // First create and deactivate a dish
        $dish = $this->dishModel->findByName('Plato Inactivo Test');

        if ($dish) {
            $result = $this->dishModel->reactivate($dish['id']);
            $this->assertTrue($result);
        }
    }

    /** @test TC-09-04 */
    public function testSoftDeletePlato() {
        $dishId = 5;
        $result = $this->dishModel->softDelete($dishId);

        $this->assertTrue($result);

        $dish = $this->dishModel->getById($dishId);
        $this->assertEquals(0, $dish['is_active']);
    }

    /** @test TC-09-05 */
    public function testBloquearEliminacionPorPedidoActivo() {
        $dishId = 1; // Dish with active orders

        $result = $this->dishModel->softDelete($dishId);

        $this->assertFalse($result);
    }
}

class BackendOrderTests extends TestCase {
    private $orderModel;

    protected function setUp(): void {
        $this->orderModel = new \App\Models\Order();
    }

    /** @test TC-10-01 */
    public function testCrearOrdenConItems() {
        $orderData = [
            'user_id' => 1,
            'items' => [
                ['dish_id' => 1, 'quantity' => 2],
                ['dish_id' => 2, 'quantity' => 1]
            ],
            'total' => 35.50
        ];

        $orderId = $this->orderModel->create($orderData);

        $this->assertIsInt($orderId);

        $order = $this->orderModel->getById($orderId);
        $this->assertEquals(35.50, $order['total']);
    }

    /** @test TC-10-02 */
    public function testActualizarEstadoOrden() {
        $orderId = 1;
        $result = $this->orderModel->updateStatus($orderId, 'Completado');

        $this->assertTrue($result);

        $order = $this->orderModel->getById($orderId);
        $this->assertEquals('Completado', $order['status']);
    }

    /** @test TC-10-03 */
    public function testCalcularTotalConDescuento() {
        $items = [
            ['price' => 10, 'quantity' => 2],
            ['price' => 15, 'quantity' => 1]
        ];

        $total = $this->orderModel->calculateTotal($items, discount: 5);

        $expected = (10 * 2) + (15 * 1) - 5;
        $this->assertEquals($expected, $total);
    }

    /** @test TC-10-04 */
    public function testValidarStockInsuficiente() {
        $orderData = [
            'user_id' => 1,
            'items' => [
                ['dish_id' => 1, 'quantity' => 1000] // Stock muy alto
            ]
        ];

        $result = $this->orderModel->create($orderData);

        $this->assertFalse($result);
    }

    /** @test TC-10-05 */
    public function testCancelarOrdenActiva() {
        $orderId = 1;
        $result = $this->orderModel->cancel($orderId);

        $this->assertTrue($result);

        $order = $this->orderModel->getById($orderId);
        $this->assertEquals('Cancelada', $order['status']);
    }
}

class BackendReservationTests extends TestCase {
    private $reservationModel;

    protected function setUp(): void {
        $this->reservationModel = new \App\Models\Reservation();
    }

    /** @test TC-11-01 */
    public function testCrearReservaConCapacidadDisponible() {
        $reservationData = [
            'user_id' => 1,
            'date' => date('Y-m-d', strtotime('+5 days')),
            'time' => '19:00',
            'guests' => 4
        ];

        $reservationId = $this->reservationModel->create($reservationData);

        $this->assertIsInt($reservationId);
        $this->assertGreaterThan(0, $reservationId);
    }

    /** @test TC-11-02 */
    public function testValidarMesasLlenasEnHorario() {
        $reservationData = [
            'user_id' => 2,
            'date' => '2026-05-20',
            'time' => '19:00',
            'guests' => 6
        ];

        $result = $this->reservationModel->create($reservationData);

        $this->assertFalse($result);
    }

    /** @test TC-11-03 */
    public function testLimiteDosReservasActivasPorUsuario() {
        $userId = 1;
        $activeReservations = $this->reservationModel->getActiveByUser($userId);

        $this->assertLessThanOrEqual(2, count($activeReservations));
    }

    /** @test TC-11-04 */
    public function testCancelarReserva() {
        $reservationId = 1;
        $result = $this->reservationModel->cancel($reservationId);

        $this->assertTrue($result);

        $reservation = $this->reservationModel->getById($reservationId);
        $this->assertEquals('Cancelada', $reservation['status']);
    }

    /** @test TC-11-05 */
    public function testListarReservasPorRangoFechas() {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+30 days'));

        $reservations = $this->reservationModel->getByDateRange($startDate, $endDate);

        $this->assertIsArray($reservations);
    }
}

class BackendSurveyTests extends TestCase {
    private $surveyModel;

    protected function setUp(): void {
        $this->surveyModel = new \App\Models\Survey();
    }

    /** @test TC-12-01 */
    public function testGuardarEncuestaConRespuestas() {
        $surveyData = [
            'user_id' => 1,
            'order_id' => 1,
            'responses' => [
                ['question_id' => 1, 'answer' => 'Excelente'],
                ['question_id' => 2, 'answer' => 5],
                ['question_id' => 3, 'answer' => 'Buen servicio']
            ]
        ];

        $surveyId = $this->surveyModel->create($surveyData);

        $this->assertIsInt($surveyId);
    }

    /** @test TC-12-02 */
    public function testValidarRespuestasRequeridas() {
        $surveyData = [
            'user_id' => 1,
            'order_id' => 1,
            'responses' => [] // Empty responses
        ];

        $result = $this->surveyModel->create($surveyData);

        $this->assertFalse($result);
    }

    /** @test TC-12-03 */
    public function testCalcularPromedioCalificacion() {
        $ratings = [5, 4, 5, 3, 4];
        $average = $this->surveyModel->calculateAverage($ratings);

        $expected = array_sum($ratings) / count($ratings);
        $this->assertEquals($expected, $average);
    }

    /** @test TC-12-04 */
    public function testFiltrarEncuestasPorRangoFechas() {
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');

        $surveys = $this->surveyModel->getByDateRange($startDate, $endDate);

        $this->assertIsArray($surveys);
    }

    /** @test TC-12-05 */
    public function testGenerarReporteEncuestas() {
        $report = $this->surveyModel->generateReport();

        $this->assertIsArray($report);
        $this->assertArrayHasKey('total_surveys', $report);
        $this->assertArrayHasKey('average_rating', $report);
        $this->assertArrayHasKey('satisfaction_percentage', $report);
    }
}
