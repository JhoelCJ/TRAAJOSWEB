<!-- src/components/layouts/MainLayout.vue -->

<template>
  <div class="main-layout">
    <nav class="navbar">
      <div class="container">
        <router-link to="/" class="logo">🍽️ Biconoir Gourmet</router-link>
        
        <ul class="nav-links">
          <li><router-link to="/menu">Menú</router-link></li>
          <li><router-link to="/about">Sobre Nosotros</router-link></li>
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
            <span>{{ userStore.user?.name }}</span>
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
      <div class="container">
        <p>&copy; 2024 Biconoir Gourmet. Todos los derechos reservados.</p>
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
.main-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.navbar {
  background-color: #2c3e50;
  color: white;
  padding: 1rem 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
}

.nav-links {
  display: flex;
  list-style: none;
  gap: 2rem;
  margin: 0;
  padding: 0;
}

.nav-links a {
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}

.nav-links a:hover {
  color: #3498db;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logout-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.logout-btn:hover {
  background-color: #c0392b;
}

.cart-icon {
  color: white;
  text-decoration: none;
  position: relative;
}

.badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #e74c3c;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
}

.content {
  flex: 1;
  padding: 2rem 0;
}

.footer {
  background-color: #2c3e50;
  color: white;
  text-align: center;
  padding: 2rem;
  margin-top: auto;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

@media (max-width: 768px) {
  .nav-links {
    gap: 1rem;
  }

  .navbar .container {
    flex-wrap: wrap;
  }
}
</style>
