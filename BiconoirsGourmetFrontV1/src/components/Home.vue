<!-- src/components/Home.vue -->

<template>
  <div class="home-container">
    <!-- Hero Section con Logo -->
    <section class="hero">
      <div class="container hero-content">
        <div class="logo-section">
          <img src="@/assets/img/logoRestaurantGreen.png" alt="Biconoir Gourmet" class="hero-logo">
        </div>
        <h1>Biconoir Gourmet</h1>
        <p>Experiencia culinaria de clase mundial</p>
        <router-link to="/menu" class="cta-btn">Explorar Menú</router-link>
      </div>
    </section>

    <!-- Restaurant Image Section -->
    <section class="restaurant-section">
      <div class="container">
        <img src="@/assets/img/restaurant.jpg" alt="Nuestro Restaurante" class="restaurant-image">
      </div>
    </section>

    <!-- Featured Dishes -->
    <section class="featured-dishes">
      <div class="container">
        <h2>Platos Destacados</h2>
        <div class="dishes-grid">
          <div class="dish-card">
            <img src="@/assets/img/food1.jpg" alt="Plato 1" class="dish-image">
            <div class="dish-info">
              <h3>Plato Premium</h3>
              <p>Preparación exclusiva del chef</p>
              <span class="price">$28.90</span>
            </div>
          </div>
          <div class="dish-card">
            <img src="@/assets/img/food2.jpg" alt="Plato 2" class="dish-image">
            <div class="dish-info">
              <h3>Especialidad Gourmet</h3>
              <p>Con ingredientes frescos seleccionados</p>
              <span class="price">$32.50</span>
            </div>
          </div>
          <div class="dish-card">
            <img src="@/assets/img/food3.jpg" alt="Plato 3" class="dish-image">
            <div class="dish-info">
              <h3>Creación del Día</h3>
              <p>Chef's special de temporada</p>
              <span class="price">$25.00</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <div class="feature-grid">
          <div class="feature">
            <div class="icon">🎯</div>
            <h3>Calidad Premium</h3>
            <p>Ingredientes frescos y de la mejor calidad</p>
          </div>
          <div class="feature">
            <div class="icon">👨‍🍳</div>
            <h3>Chefs Expertos</h3>
            <p>Preparado con técnicas gourmet</p>
          </div>
          <div class="feature">
            <div class="icon">⚡</div>
            <h3>Servicio Rápido</h3>
            <p>Atención personalizada en cada momento</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
      <div class="container">
        <h2>¿Listo para una experiencia única?</h2>
        <p>Reserva tu mesa o realiza tu pedido ahora</p>
        <div class="button-group">
          <router-link to="/menu" class="btn btn-primary">Pedir Ahora</router-link>
          <router-link to="/reservations" class="btn btn-secondary">Hacer Reserva</router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
:root {
  --primary-green: #16a34a;
  --dark-green: #15803d;
  --light-green: #bbf7d0;
  --accent-green: #22c55e;
}

.home-container {
  min-height: 100vh;
}

/* Hero Section */
.hero {
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
  color: white;
  padding: 4rem 0;
  text-align: center;
}

.hero-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.logo-section {
  margin-bottom: 2rem;
}

.hero-logo {
  max-width: 150px;
  height: auto;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
}

.hero h1 {
  font-size: 3rem;
  margin-bottom: 1rem;
  font-weight: 700;
  letter-spacing: 1px;
}

.hero p {
  font-size: 1.5rem;
  margin-bottom: 2rem;
  opacity: 0.95;
}

.cta-btn {
  display: inline-block;
  padding: 1rem 2rem;
  background-color: white;
  color: var(--primary-green);
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  transition: all 0.3s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

/* Restaurant Section */
.restaurant-section {
  padding: 3rem 0;
  background-color: white;
}

.restaurant-image {
  width: 100%;
  height: auto;
  max-height: 400px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* Featured Dishes Section */
.featured-dishes {
  padding: 4rem 0;
  background: linear-gradient(135deg, #f0fdf4 0%, #f0fdf4 100%);
}

.featured-dishes h2 {
  text-align: center;
  font-size: 2.5rem;
  color: var(--dark-green);
  margin-bottom: 3rem;
}

.dishes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}

.dish-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.3s;
  cursor: pointer;
}

.dish-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 20px rgba(22, 163, 74, 0.15);
}

.dish-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.dish-info {
  padding: 1.5rem;
}

.dish-info h3 {
  color: var(--dark-green);
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
}

.dish-info p {
  color: #666;
  font-size: 0.95rem;
  margin-bottom: 1rem;
}

.price {
  display: block;
  font-size: 1.5rem;
  color: var(--accent-green);
  font-weight: bold;
}

/* Features Section */
.features {
  padding: 4rem 0;
  background-color: white;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}

.feature {
  text-align: center;
  padding: 2rem;
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border-radius: 12px;
  border-left: 4px solid var(--primary-green);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s;
}

.feature:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
}

.feature .icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.feature h3 {
  color: var(--dark-green);
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.feature p {
  color: #555;
  font-size: 0.95rem;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
  color: white;
  padding: 4rem 0;
  text-align: center;
}

.cta-section h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.cta-section p {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  opacity: 0.95;
}

.button-group {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  padding: 1rem 2rem;
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  transition: all 0.3s;
}

.btn-primary {
  background-color: white;
  color: var(--primary-green);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
  background-color: transparent;
  color: white;
  border: 2px solid white;
}

.btn-secondary:hover {
  background-color: white;
  color: var(--primary-green);
}

@media (max-width: 768px) {
  .hero {
    padding: 3rem 0;
  }

  .hero h1 {
    font-size: 2rem;
  }

  .hero p {
    font-size: 1.1rem;
  }

  .hero-logo {
    max-width: 100px;
  }

  .featured-dishes h2 {
    font-size: 1.8rem;
  }

  .button-group {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }
}
</style>
