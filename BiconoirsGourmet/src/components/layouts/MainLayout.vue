<!-- src/components/layouts/MainLayout.vue -->

<template>
  <div class="main-layout">
    <nav class="navbar">
      <div class="container nav-container">
        <router-link to="/" class="logo-section">
          <img src="../assets/img/logoRestaurantGreen.png" alt="Biconoir Gourmet" class="logo-image">
          <span class="logo-text">Biconoir Gourmet</span>
        </router-link>
        
        <ul class="nav-links">
          <li><router-link to="/menu">Menú</router-link></li>
          <li><router-link to="/about">Sobre Nosotros</router-link></li>
          <li><router-link to="/survey">Opiniones</router-link></li>
          <li v-if="!userStore.isAuthenticated">
            <router-link to="/login">Ingresar</router-link>
          </li>
          <li v-if="userStore.isAuthenticated">
            <router-link to="/reservations">Reservas</router-link>
          </li>
          <li v-if="userStore.isAuthenticated">
            <router-link to="/orders">Órdenes</router-link>
          </li>
          <li v-if="userStore.isAdmin">
            <router-link to="/admin/dashboard">Admin</router-link>
          </li>
          <li v-if="userStore.isAuthenticated" class="user-menu">
            <span class="user-name">{{ userStore.user?.name }}</span>
            <button @click="logout" class="logout-btn">Salir</button>
          </li>
        </ul>

        <router-link to="/cart" class="cart-icon">
          🛒 <span v-if="cartStore.totalItems > 0" class="badge">{{ cartStore.totalItems }}</span>
        </router-link>
      </div>
    </nav>

    <main class="content">
      <router-view />
    </main>

    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-section">
          <h4>Biconoir Gourmet</h4>
          <p>Experiencia culinaria de clase mundial</p>
        </div>
        <div class="footer-section">
          <h4>Contacto</h4>
          <p>📞 +57 (1) 123-4567</p>
          <p>📧 info@biconoir.com</p>
        </div>
        <div class="footer-section">
          <p>&copy; 2024 Biconoir Gourmet. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@stores/userStore';
import { useCartStore } from '@stores/cartStore';
import { useRouter } from 'vue-router';

const userStore = useUserStore();
const cartStore = useCartStore();
const router = useRouter();

const logout = () => {
  userStore.logout();
  router.push('/');
};
</script>

<style scoped>
:root {
  --primary-green: #16a34a;
  --dark-green: #15803d;
  --light-green: #bbf7d0;
  --accent-green: #22c55e;
}

.main-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f8fafc;
}

.navbar {
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
  color: white;
  padding: 0.75rem 0;
  box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
  position: sticky;
  top: 0;
  z-index: 100;
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
  color: white;
  font-weight: bold;
  transition: all 0.3s;
}

.logo-section:hover {
  opacity: 0.9;
}

.logo-image {
  height: 50px;
  width: auto;
  filter: brightness(1.2);
}

.logo-text {
  font-size: 1.3rem;
  letter-spacing: 0.5px;
}

.nav-links {
  display: flex;
  list-style: none;
  gap: 2.5rem;
  margin: 0;
  padding: 0;
  flex: 1;
  justify-content: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s;
  position: relative;
}

.nav-links a::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 0;
  height: 2px;
  background-color: var(--light-green);
  transition: width 0.3s;
}

.nav-links a:hover {
  color: var(--light-green);
}

.nav-links a:hover::after {
  width: 100%;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.5rem 1rem;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 6px;
}

.user-name {
  color: var(--light-green);
  font-weight: 500;
}

.logout-btn {
  background-color: #ef4444;
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.3s;
}

.logout-btn:hover {
  background-color: #dc2626;
  transform: translateY(-1px);
}

.cart-icon {
  color: white;
  text-decoration: none;
  font-weight: bold;
  position: relative;
  transition: all 0.3s;
}

.cart-icon:hover {
  color: var(--light-green);
  transform: scale(1.05);
}

.badge {
  position: absolute;
  top: -10px;
  right: -10px;
  background-color: #ef4444;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: bold;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.content {
  flex: 1;
  padding: 2rem 0;
}

.footer {
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
  color: white;
  padding: 3rem 0;
  margin-top: auto;
  box-shadow: 0 -4px 12px rgba(22, 163, 74, 0.15);
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  text-align: left;
}

.footer-section {
  padding: 1rem;
}

.footer-section h4 {
  margin-bottom: 1rem;
  color: var(--light-green);
  font-size: 1.1rem;
}

.footer-section p {
  margin: 0.5rem 0;
  opacity: 0.95;
  line-height: 1.6;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

@media (max-width: 768px) {
  .nav-links {
    gap: 1rem;
    font-size: 0.9rem;
  }

  .nav-container {
    flex-wrap: wrap;
    gap: 1rem;
  }

  .logo-text {
    display: none;
  }

  .logo-image {
    height: 40px;
  }

  .footer-content {
    text-align: center;
  }

  .footer-section {
    padding: 0.5rem;
  }
}
</style>
