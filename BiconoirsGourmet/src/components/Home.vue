<!-- src/components/Home.vue -->

<template>
  <div class="home-container">
    <section class="hero">
      <div class="container">
        <h1>🍽️ Biconoir Gourmet</h1>
        <p>Experiencia culinaria de clase mundial</p>
        <router-link to="/menu" class="cta-btn">Explorar Menú</router-link>
      </div>
    </section>

    <section class="features">
      <div class="container">
        <div class="feature-grid">
          <div class="feature">
            <div class="icon">📱</div>
            <h3>Pedidos en Línea</h3>
            <p>Ordena desde la comodidad de tu hogar</p>
          </div>
          <div class="feature">
            <div class="icon">🎯</div>
            <h3>Entrega Rápida</h3>
            <p>Recibe tu comida en el menor tiempo posible</p>
          </div>
          <div class="feature">
            <div class="icon">👨‍🍳</div>
            <h3>Chefs Expertos</h3>
            <p>Preparado con técnicas gourmet</p>
          </div>
          <div class="feature">
            <div class="icon">⭐</div>
            <h3>Calidad Premium</h3>
            <p>Ingredientes frescos y de la mejor calidad</p>
          </div>
        </div>
      </div>
    </section>

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
.home-container {
  min-height: 100vh;
}

.hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 6rem 0;
  text-align: center;
}

.hero h1 {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.hero p {
  font-size: 1.5rem;
  margin-bottom: 2rem;
  opacity: 0.9;
}

.cta-btn {
  display: inline-block;
  padding: 1rem 2rem;
  background-color: white;
  color: #667eea;
  text-decoration: none;
  border-radius: 4px;
  font-weight: bold;
  transition: transform 0.3s;
}

.cta-btn:hover {
  transform: scale(1.05);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.features {
  padding: 4rem 0;
  background-color: #f8f9fa;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}

.feature {
  text-align: center;
  padding: 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.feature .icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.feature h3 {
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.feature p {
  color: #7f8c8d;
  font-size: 0.95rem;
}

.cta-section {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
  border-radius: 4px;
  font-weight: bold;
  transition: all 0.3s;
}

.btn-primary {
  background-color: white;
  color: #f5576c;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
  background-color: transparent;
  color: white;
  border: 2px solid white;
}

.btn-secondary:hover {
  background-color: white;
  color: #f5576c;
}

@media (max-width: 768px) {
  .hero h1 {
    font-size: 2rem;
  }

  .hero p {
    font-size: 1.1rem;
  }

  .button-group {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }
}
</style>
