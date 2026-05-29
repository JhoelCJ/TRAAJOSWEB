<template>
  <div class="home-container">
    <nav class="navbar">
      <h2>Biconoir's Restaurant</h2>
      <button @click="handleLogout" class="logout-btn">Cerrar Sesión</button>
    </nav>
    
    <main>
      <h1>Panel Principal</h1>
      <p>Su sesión está activa por 1 minuto.</p>
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const handleLogout = async () => {
  try {
    const response = await fetch('/api/auth/logout', { method: 'POST' });
    if (response.ok) {
      router.push({ name: 'login' });
    }
  } catch (error) {
    console.error('Logout failed:', error);
  }
};
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  padding: 1rem;
  background-color: #f8f9fa;
}
.logout-btn {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}
</style>