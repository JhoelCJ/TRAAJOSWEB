<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuéntranos</title>

    <link rel="stylesheet" href="../public/css/style_index.css">
    <link rel="stylesheet" href="../public/css/style_locations.css">
</head>

<body>

<header>
    <div class="logo">
        <a href="../index.php">
            <img src="../public/img/logoRestaurantGreen.png" class="logo-img">
        </a>
    </div>
    <nav>
        <a href="menu.php">Ver menú</a>
        <a href="reservationForm.php" class="btn-reserva">Reservaciones</a>
        <a href="locations.php">Encuentranos</a>
        <a href="satisfactionForm.php">Encuestas de satisfacción</a>
        <a href="registerForm.php">Registrate</a>
		<a href="loginForm.php">Iniciar sesión</a>
    </nav>
</header>

<section class="location-container">

    <div id="map"></div>

    <div class="location-info">
        <h2>Nuestro Restaurante</h2>

        <p><strong>Dirección:</strong><br>
        Av. Principal 123, Quito - Ecuador</p>

        <p><strong>Teléfono:</strong><br>
        +593 99 999 9999</p>

        <p><strong>Email:</strong><br>
        contacto@biconoir.com</p>

        <p><strong>Horario:</strong><br>
        Lun - Dom: 10:00 AM - 10:00 PM</p>

    </div>

</section>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY"></script>
<script src="../public/js/map.js"></script>

</body>
</html>