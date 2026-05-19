<?php
// Generador automático de 90 Test Cases
// Estructura: 30 Frontend/Selenium + 30 Backend/PHPUnit + 30 E2E/Selenium

class TestCaseGenerator {
    private $output = '';

    public function generate() {
        $this->output = $this->getHeader();
        $this->output .= $this->generateFrontendTests();
        $this->output .= $this->generateBackendTests();
        $this->output .= $this->generateE2ETests();
        $this->output .= $this->getFooter();
        return $this->output;
    }

    private function getHeader() {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>90 Test Cases - BiconoirsGourmet</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .section { margin-bottom: 40px; page-break-after: always; }
        .section-title { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .feature { background: #ecf0f1; padding: 15px; margin-bottom: 15px; border-left: 4px solid #3498db; }
        .test-case { background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 3px; }
        .test-code { font-weight: bold; color: #2980b9; font-size: 14px; }
        .field { margin: 8px 0; }
        .label { font-weight: bold; color: #2c3e50; width: 80px; display: inline-block; }
        .value { color: #333; }
        .result-pending { background: #fff3cd; padding: 5px; border-radius: 3px; color: #856404; }
        .status { color: #666; }
        .progress { margin-top: 20px; padding: 10px; background: #d4edda; border-radius: 3px; }
        @media print {
            body { margin: 0; }
            .section { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 90 Test Cases - BiconoirsGourmet</h1>
        <p><strong>Fecha de generación:</strong> 2026-05-13</p>
        <p><strong>Total de casos:</strong> 90 test cases (30 Frontend + 30 Backend + 30 E2E)</p>
        <hr>
HTML;
    }

    private function generateFrontendTests() {
        $html = <<<HTML
        <div class="section">
            <div class="section-title">🌐 PARTE 1: FRONTEND / SELENIUM (UI Tests) - 30 Casos</div>
            <p>Prueba lo que el usuario ve e interactúa en el navegador.</p>
HTML;

        $features = [
            ['nombre' => 'Autenticación', 'numero' => 1],
            ['nombre' => 'Registro', 'numero' => 2],
            ['nombre' => 'Carrito', 'numero' => 3],
            ['nombre' => 'Reservas', 'numero' => 4],
            ['nombre' => 'Encuesta', 'numero' => 5],
            ['nombre' => 'Administración', 'numero' => 6],
        ];

        $testCounter = 1;

        foreach ($features as $feature) {
            $html .= '<div class="feature">';
            $html .= '<strong>Feature ' . $feature['numero'] . ' — ' . $feature['nombre'] . '</strong>';

            $tests = $this->getFrontendTestsForFeature($feature['nombre']);
            foreach ($tests as $test) {
                $html .= $this->renderTestCase($testCounter, $feature['numero'], $test);
                $testCounter++;
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function generateBackendTests() {
        $html = <<<HTML
        <div class="section">
            <div class="section-title">⚙️ PARTE 2: BACKEND / PHPUNIT (Unit + Integration Tests) - 30 Casos</div>
            <p>Prueba la lógica de negocio en los Models y Controllers, sin navegador.</p>
HTML;

        $features = [
            ['nombre' => 'Autenticación', 'numero' => 7],
            ['nombre' => 'Gestión de Usuarios', 'numero' => 8],
            ['nombre' => 'Gestión de Platos', 'numero' => 9],
            ['nombre' => 'Gestión de Pedidos', 'numero' => 10],
            ['nombre' => 'Gestión de Reservas', 'numero' => 11],
            ['nombre' => 'Encuestas', 'numero' => 12],
        ];

        $testCounter = 31;

        foreach ($features as $feature) {
            $html .= '<div class="feature">';
            $html .= '<strong>Feature ' . $feature['numero'] . ' — ' . $feature['nombre'] . '</strong>';

            $tests = $this->getBackendTestsForFeature($feature['nombre']);
            foreach ($tests as $test) {
                $html .= $this->renderTestCase($testCounter, $feature['numero'], $test);
                $testCounter++;
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function generateE2ETests() {
        $html = <<<HTML
        <div class="section">
            <div class="section-title">🔗 PARTE 3: END-TO-END / SELENIUM (E2E Tests) - 30 Casos</div>
            <p>Prueba flujos completos de negocio de inicio a fin, combinando varias pantallas.</p>
HTML;

        $features = [
            ['nombre' => 'Flujo Cliente Completo', 'numero' => 13],
            ['nombre' => 'Flujo Administrador', 'numero' => 14],
            ['nombre' => 'Flujo Inventario', 'numero' => 15],
            ['nombre' => 'Flujo Reservas', 'numero' => 16],
            ['nombre' => 'Casos Negativos', 'numero' => 17],
            ['nombre' => 'Casos de Integración', 'numero' => 18],
        ];

        $testCounter = 61;

        foreach ($features as $feature) {
            $html .= '<div class="feature">';
            $html .= '<strong>Feature ' . $feature['numero'] . ' — ' . $feature['nombre'] . '</strong>';

            $tests = $this->getE2ETestsForFeature($feature['nombre']);
            foreach ($tests as $test) {
                $html .= $this->renderTestCase($testCounter, $feature['numero'], $test);
                $testCounter++;
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function getFrontendTestsForFeature($feature) {
        $tests = [
            'Autenticación' => [
                ['prueba' => 'Cargar página de login', 'esperado' => 'Formulario visible con campos usuario y contraseña'],
                ['prueba' => 'Validar email requerido', 'esperado' => 'Mensaje de error "Email es requerido"'],
                ['prueba' => 'Validar contraseña requerida', 'esperado' => 'Mensaje de error "Contraseña es requerida"'],
                ['prueba' => 'Validar formato email incorrecto', 'esperado' => 'Error "Email inválido"'],
                ['prueba' => 'Enviar formulario válido', 'esperado' => 'Redirecciona a dashboard si login es exitoso'],
            ],
            'Registro' => [
                ['prueba' => 'Cargar página de registro', 'esperado' => 'Formulario de registro con todos los campos'],
                ['prueba' => 'Validar campos requeridos', 'esperado' => 'Errores en cada campo vacío'],
                ['prueba' => 'Validar email duplicado', 'esperado' => 'Error "Email ya registrado"'],
                ['prueba' => 'Validar contraseña corta', 'esperado' => 'Error "Contraseña debe tener al menos 8 caracteres"'],
                ['prueba' => 'Registro exitoso', 'esperado' => 'Usuario creado, redirecciona a login'],
            ],
            'Carrito' => [
                ['prueba' => 'Agregar plato al carrito', 'esperado' => 'Plato aparece en carrito con cantidad 1'],
                ['prueba' => 'Aumentar cantidad', 'esperado' => 'Total actualizado correctamente'],
                ['prueba' => 'Eliminar plato del carrito', 'esperado' => 'Plato removido, carrito actualizado'],
                ['prueba' => 'Calcular total carrito', 'esperado' => 'Total correcto = suma de precios × cantidad'],
                ['prueba' => 'Checkout con carrito vacío', 'esperado' => 'Mensaje "Carrito vacío, agregue platos"'],
            ],
            'Reservas' => [
                ['prueba' => 'Abrir formulario de reserva', 'esperado' => 'Modal con campos: fecha, hora, cantidad personas'],
                ['prueba' => 'Validar fecha pasada', 'esperado' => 'Error "Fecha debe ser futura"'],
                ['prueba' => 'Validar cantidad de personas', 'esperado' => 'Error si > 10 o < 1'],
                ['prueba' => 'Intentar reservar con slot lleno', 'esperado' => 'Alerta "No hay mesas disponibles"'],
                ['prueba' => 'Reserva exitosa', 'esperado' => 'Confirmación y redirección a "Mis Reservas"'],
            ],
            'Encuesta' => [
                ['prueba' => 'Cargar formulario de encuesta', 'esperado' => 'Preguntas y opciones de respuesta visibles'],
                ['prueba' => 'Validar respuestas requeridas', 'esperado' => 'Error "Debe responder todas las preguntas"'],
                ['prueba' => 'Seleccionar opciones', 'esperado' => 'Opciones seleccionadas marcan checkbox'],
                ['prueba' => 'Agregar comentario', 'esperado' => 'Texto se guarda correctamente'],
                ['prueba' => 'Enviar encuesta', 'esperado' => 'Mensaje "Encuesta enviada exitosamente"'],
            ],
            'Administración' => [
                ['prueba' => 'Acceso sin permiso de admin', 'esperado' => 'Redirecciona a login o página de error'],
                ['prueba' => 'Cargar dashboard admin', 'esperado' => 'Estadísticas y gráficos visibles'],
                ['prueba' => 'Filtrar tabla de pedidos', 'esperado' => 'Tabla filtra correctamente por estado'],
                ['prueba' => 'Buscar en tabla', 'esperado' => 'Resultados de búsqueda coinciden con término'],
                ['prueba' => 'Exportar datos', 'esperado' => 'Archivo descargado en formato correcto'],
            ],
        ];

        return $tests[$feature] ?? [];
    }

    private function getBackendTestsForFeature($feature) {
        $tests = [
            'Autenticación' => [
                ['prueba' => 'Autenticar con credenciales correctas', 'esperado' => 'Retorna token/sesión válida'],
                ['prueba' => 'Autenticar con credenciales incorrectas', 'esperado' => 'Retorna error "Credenciales inválidas"'],
                ['prueba' => 'Validar token expirado', 'esperado' => 'Error "Token expirado"'],
                ['prueba' => 'Refresh token', 'esperado' => 'Nuevo token generado correctamente'],
                ['prueba' => 'Logout', 'esperado' => 'Sesión/token invalidada'],
            ],
            'Gestión de Usuarios' => [
                ['prueba' => 'Crear usuario con datos válidos', 'esperado' => 'Usuario guardado en BD, retorna ID'],
                ['prueba' => 'Crear usuario con email duplicado', 'esperado' => 'Error de validación única'],
                ['prueba' => 'Actualizar perfil de usuario', 'esperado' => 'Cambios guardados correctamente'],
                ['prueba' => 'Eliminar usuario', 'esperado' => 'Soft delete, usuario inactivo'],
                ['prueba' => 'Listar usuarios con paginación', 'esperado' => 'Retorna 10 usuarios por página'],
            ],
            'Gestión de Platos' => [
                ['prueba' => 'Crear plato con ingredientes', 'esperado' => 'Plato y sus ingredientes guardados'],
                ['prueba' => 'Crear plato duplicado activo', 'esperado' => 'Error "Plato ya existe"'],
                ['prueba' => 'Reutilizar plato inactivo', 'esperado' => 'Plato reactivado correctamente'],
                ['prueba' => 'Soft delete plato', 'esperado' => 'Plato oculto, marca is_active = 0'],
                ['prueba' => 'Bloquear eliminación por pedido activo', 'esperado' => 'Error "Plato tiene pedidos activos"'],
            ],
            'Gestión de Pedidos' => [
                ['prueba' => 'Crear orden con items', 'esperado' => 'Orden guardada, cálculo de total correcto'],
                ['prueba' => 'Actualizar estado: Pendiente → Completado', 'esperado' => 'Estado cambia, stock descontado'],
                ['prueba' => 'Calcular total con descuento', 'esperado' => 'Total = (suma items) - descuento'],
                ['prueba' => 'Validar stock insuficiente', 'esperado' => 'Error "Stock insuficiente para item X"'],
                ['prueba' => 'Cancelar orden activa', 'esperado' => 'Stock devuelto, status = Cancelada'],
            ],
            'Gestión de Reservas' => [
                ['prueba' => 'Crear reserva con capacidad disponible', 'esperado' => 'Reserva guardada correctamente'],
                ['prueba' => 'Validar mesas llenas en horario', 'esperado' => 'Error "Sin mesas disponibles"'],
                ['prueba' => 'Límite 2 reservas activas por usuario', 'esperado' => 'Error "Máximo 2 reservas activas"'],
                ['prueba' => 'Cancelar reserva', 'esperado' => 'Status = Cancelada, slot liberado'],
                ['prueba' => 'Listar reservas por rango de fechas', 'esperado' => 'Retorna solo reservas en rango'],
            ],
            'Encuestas' => [
                ['prueba' => 'Guardar encuesta con respuestas', 'esperado' => 'Encuesta y respuestas en BD'],
                ['prueba' => 'Validar respuestas requeridas', 'esperado' => 'Error si falta alguna pregunta'],
                ['prueba' => 'Calcular promedio calificación', 'esperado' => 'Promedio = suma calificaciones / cantidad'],
                ['prueba' => 'Filtrar encuestas por rango fechas', 'esperado' => 'Retorna solo encuestas en período'],
                ['prueba' => 'Generar reporte encuestas', 'esperado' => 'Reporte con estadísticas agregadas'],
            ],
        ];

        return $tests[$feature] ?? [];
    }

    private function getE2ETestsForFeature($feature) {
        $tests = [
            'Flujo Cliente Completo' => [
                ['prueba' => 'Registro → Login → Dashboard', 'esperado' => 'Cliente ve dashboard personalizado'],
                ['prueba' => 'Ver menú → Agregar platos al carrito', 'esperado' => 'Carrito muestra platos seleccionados'],
                ['prueba' => 'Checkout → Generar pedido → Ver en Mis Pedidos', 'esperado' => 'Pedido visible en historial'],
                ['prueba' => 'Hacer reserva → Ver en Mis Reservas', 'esperado' => 'Reserva confirmada y visible'],
                ['prueba' => 'Completar encuesta → Verificar guardar', 'esperado' => 'Encuesta guardada en BD'],
            ],
            'Flujo Administrador' => [
                ['prueba' => 'Login admin → Ver dashboard', 'esperado' => 'Estadísticas cargadas correctamente'],
                ['prueba' => 'Crear nuevo plato → Ver en menú público', 'esperado' => 'Plato aparece para clientes'],
                ['prueba' => 'Administrar reservas → Aprobar/Cancelar', 'esperado' => 'Cambio de estado reflejado'],
                ['prueba' => 'Ver reportes financieros', 'esperado' => 'Datos cargados correctamente'],
                ['prueba' => 'Gestionar usuarios → Cambiar rol', 'esperado' => 'Rol actualizado para usuario'],
            ],
            'Flujo Inventario' => [
                ['prueba' => 'Cargar suministro → Stock actualizado', 'esperado' => 'Cantidad de stock aumenta'],
                ['prueba' => 'Crear orden → Descontar stock automático', 'esperado' => 'Stock disminuye después de orden'],
                ['prueba' => 'Completar orden → Stock procesado', 'esperado' => 'Stock final correcto'],
                ['prueba' => 'Historial de movimientos de stock', 'esperado' => 'Todos los movimientos registrados'],
                ['prueba' => 'Alerta de stock bajo', 'esperado' => 'Notificación cuando stock < umbral'],
            ],
            'Flujo Reservas' => [
                ['prueba' => 'Hacer reserva → Verificar en admin', 'esperado' => 'Aparece en tabla de reservas'],
                ['prueba' => 'Cancelar reserva → Slot se libera', 'esperado' => 'Otro usuario puede reservar ese slot'],
                ['prueba' => 'Validar capacidad máxima', 'esperado' => 'No permite más reservas que capacidad'],
                ['prueba' => 'Recordatorio de reserva (24h antes)', 'esperado' => 'Email/SMS enviado'],
                ['prueba' => 'Check-in de reserva', 'esperado' => 'Status cambia a "Completada"'],
            ],
            'Casos Negativos' => [
                ['prueba' => 'Intentar 3+ reservas simultáneas', 'esperado' => 'Error "Máximo 2 reservas"'],
                ['prueba' => 'Pedido con stock insuficiente', 'esperado' => 'Orden no se crea'],
                ['prueba' => 'Acceso sin autenticación a admin', 'esperado' => 'Redirecciona a login'],
                ['prueba' => 'Modificar precio en carrito (XSS)', 'esperado' => 'Precio valida desde servidor'],
                ['prueba' => 'Intentar borrar usuario activo', 'esperado' => 'Error "Usuario tiene pedidos activos"'],
            ],
            'Casos de Integración' => [
                ['prueba' => 'Email de confirmación de pedido', 'esperado' => 'Email enviado con detalles'],
                ['prueba' => 'Notificación de reserva confirmada', 'esperado' => 'Email + SMS enviados'],
                ['prueba' => 'Reporte diario de ventas', 'esperado' => 'Reporte generado y enviado'],
                ['prueba' => 'Sincronización de stock entre módulos', 'esperado' => 'Stock consistente en BD'],
                ['prueba' => 'Auditoría de cambios críticos', 'esperado' => 'Todos los cambios registrados en log'],
            ],
        ];

        return $tests[$feature] ?? [];
    }

    private function renderTestCase($counter, $featureNum, $test) {
        $tc_id = str_pad($featureNum, 2, '0', STR_PAD_LEFT) . '-' . str_pad(($counter % 30 == 0 ? 30 : $counter % 30), 2, '0', STR_PAD_LEFT);

        return <<<HTML
            <div class="test-case">
                <div class="test-code">TC-{$tc_id}</div>
                <div class="field">
                    <span class="label">Prueba:</span>
                    <span class="value">{$test['prueba']}</span>
                </div>
                <div class="field">
                    <span class="label">Esperado:</span>
                    <span class="value">{$test['esperado']}</span>
                </div>
                <div class="field">
                    <span class="label">Resultado:</span>
                    <span class="result-pending">[Se llena al ejecutar]</span>
                </div>
                <div class="field">
                    <span class="label">Estado:</span>
                    <span class="status">[ ] Prueba Pasada / [ ] Prueba Fallida</span>
                </div>
            </div>
HTML;
    }

    private function getFooter() {
        return <<<HTML
        <div class="progress">
            <strong>📊 Resumen de Ejecución:</strong><br>
            Total Ejecutados: <input type="text" style="width: 50px;"> / 90<br>
            Pasados: <input type="text" style="width: 50px;"> ✓<br>
            Fallidos: <input type="text" style="width: 50px;"> ✗<br>
            Tasa de Éxito: <input type="text" style="width: 50px;"> %
        </div>
    </div>
</body>
</html>
HTML;
    }
}

// Generar y guardar
$generator = new TestCaseGenerator();
$html = $generator->generate();

// Guardar archivo
$filename = __DIR__ . '/90_test_cases.html';
file_put_contents($filename, $html);

echo "✅ 90 Test Cases generados exitosamente!\n";
echo "📄 Archivo: " . $filename . "\n";
echo "📊 Total: 30 Frontend + 30 Backend + 30 E2E\n";

// También generar versión en formato texto/CSV
$text_version = generateTextVersion();
file_put_contents(__DIR__ . '/90_test_cases.txt', $text_version);
echo "📝 Versión texto: " . __DIR__ . '/90_test_cases.txt' . "\n";

function generateTextVersion() {
    $text = "90 TEST CASES - BiconoirsGourmet\n";
    $text .= "================================\n\n";
    $text .= "PARTE 1: FRONTEND / SELENIUM (30 casos)\n";
    $text .= "PARTE 2: BACKEND / PHPUNIT (30 casos)\n";
    $text .= "PARTE 3: E2E / SELENIUM (30 casos)\n\n";
    $text .= "Generado: 2026-05-13\n";
    return $text;
}
?>
