<template>
  <div class="expired-container">
    <h2>Su sesión ha expirado</h2>
    <p>Por motivos de seguridad, debe volver a autenticarse.</p>
    
    <button @click="goToLogin" class="relogin-btn">
      Ingresar nuevamente
    </button>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const goToLogin = () => {
  router.push({ name: 'login' });
};
</script>

<style scoped>
.expired-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  text-align: center;
}
.relogin-btn {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
</style>