<!-- src/components/About.vue -->

<template>
  <div class="about-container">
    <div class="container">
      <h1>Sobre Nosotros</h1>

      <section class="about-section">
        <h2>Biconoir Gourmet</h2>
        <p>
          Somos un restaurante de clase mundial dedicado a ofrecer una experiencia
          culinaria excepcional. Con más de 15 años de trayectoria, nos hemos
          posicionado como uno de los mejores establecimientos gastronómicos de la región.
        </p>
      </section>

      <section class="about-section">
        <h2>Nuestra Misión</h2>
        <p>
          Proporcionar a nuestros clientes una experiencia gastronómica memorable,
          combinando ingredientes premium con técnicas culinarias innovadoras y
          un servicio excepcional.
        </p>
      </section>

      <section class="about-section">
        <h2>Nuestros Valores</h2>
        <ul class="values-list">
          <li>🎯 Excelencia en cada plato</li>
          <li>🌟 Dedicación al cliente</li>
          <li>🌱 Ingredientes frescos y de calidad</li>
          <li>👥 Equipo profesional apasionado</li>
          <li>💡 Innovación constante</li>
        </ul>
      </section>

      <section class="about-section">
        <h2>Contacto</h2>
        <div class="contact-info">
          <p><strong>Teléfono:</strong> +57 (1) 123-4567</p>
          <p><strong>Email:</strong> info@bicoinoirgourmet.com</p>
          <p><strong>Dirección:</strong> Calle 10 #5-50, Bogotá, Colombia</p>
          <p><strong>Horario:</strong> Lunes a Domingo, 12:00 PM - 11:00 PM</p>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
.about-container {
  padding: 2rem 0;
  background-color: #f8f9fa;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 3rem;
  font-size: 2.5rem;
}

h2 {
  color: #2c3e50;
  margin-bottom: 1rem;
  font-size: 1.8rem;
}

.about-section {
  background: white;
  padding: 2rem;
  margin-bottom: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.about-section p {
  line-height: 1.8;
  color: #555;
  font-size: 1.1rem;
}

.values-list {
  list-style: none;
  padding: 0;
}

.values-list li {
  padding: 0.75rem 0;
  font-size: 1.1rem;
  color: #555;
}

.contact-info {
  display: grid;
  gap: 1rem;
}

.contact-info p {
  margin: 0;
  font-size: 1rem;
  color: #555;
}

@media (max-width: 768px) {
  h1 {
    font-size: 1.8rem;
  }

  h2 {
    font-size: 1.3rem;
  }

  .about-section {
    padding: 1.5rem;
  }
}
</style>
