<template>
  <div class="auth-container">
    <h1>Bienvenido a Biconoir's Restaurant</h1>
    <p>Por favor, inicie sesión para continuar.</p>
    
    <a href="/api/auth/google" class="google-btn">
      Ingresar con Google
    </a>
  </div>
</template>

<script setup lang="ts">
// Logic here if needed, but OAuth flow is driven by standard href links
</script>

<style scoped>
.auth-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
.google-btn {
  padding: 10px 20px;
  background-color: #4285F4;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 20px;
}
</style>