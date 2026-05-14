<?php require_once "../backend/dishes.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
	<link rel="stylesheet" href="../public/css/style_menu.css">
    <link rel="stylesheet" href="../public/css/style_index.css">
</head>
<body>

    <div class="main-container">
        
        <header>
            <div class="logo">
                <a href="../index.php">
                    <img src="../public/img/logoRestaurantGreen.png" alt="Logo Biconoir's" class="logo-img">
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

        <h1 class="page-title">Nuestro Menú</h1>

        <section class="menu-section">
            <h2 class="section-title">Entrada</h2>
            <div class="dishes-grid">

                <?php foreach($dishes as $dish): ?>
                    <?php if($dish['category'] == "Entrance"): ?>

                        <article class="dish-card">
                            
                            <img src="<?= $dish['img'] ?>" class="dish-img">

                            <div class="dish-content">
                                <div class="dish-header">
                                    <span><?= $dish['name'] ?></span>
                                    <span class="dish-price">$<?= $dish['price'] ?></span>
                                </div>

                                <p class="dish-description">
                                    <?= $dish['desc'] ?>
                                </p>
                            </div>

                        </article>

                    <?php endif; ?>
                <?php endforeach; ?>

                </div>
        </section>

        <section class="menu-section">
            <h2 class="section-title">Platos principales</h2>
            <div class="dishes-grid">

                <?php foreach($dishes as $dish): ?>
                    <?php if($dish['category'] == "Main dishes"): ?>

                        <article class="dish-card">
                            
                            <img src="<?= $dish['img'] ?>" class="dish-img">

                            <div class="dish-content">
                                <div class="dish-header">
                                    <span><?= $dish['name'] ?></span>
                                    <span class="dish-price">$<?= $dish['price'] ?></span>
                                </div>

                                <p class="dish-description">
                                    <?= $dish['desc'] ?>
                                </p>
                            </div>

                        </article>

                    <?php endif; ?>
                <?php endforeach; ?>

                </div>
        </section>

        <section class="menu-section">
            <h2 class="section-title">Drinks</h2>
            <div class="dishes-grid">

                <?php foreach($dishes as $dish): ?>
                    <?php if($dish['category'] == "Drinks"): ?>

                        <article class="dish-card">
                            
                            <img src="<?= $dish['img'] ?>" class="dish-img">

                            <div class="dish-content">
                                <div class="dish-header">
                                    <span><?= $dish['name'] ?></span>
                                    <span class="dish-price">$<?= $dish['price'] ?></span>
                                </div>

                                <p class="dish-description">
                                    <?= $dish['desc'] ?>
                                </p>
                            </div>

                        </article>

                    <?php endif; ?>
                <?php endforeach; ?>

                </div>
        </section>

    </div>

</body>
</html>