<!-- src/components/Register.vue -->

<template>
  <div class="register-container">
    <div class="register-card">
      <h1>Crear Cuenta</h1>
      
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="name">Nombre Completo</label>
          <input
            id="name"
            v-model="name"
            type="text"
            required
            placeholder="Juan Pérez"
          />
        </div>

        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            placeholder="tu@email.com"
          />
        </div>

        <div class="form-group">
          <label for="phone">Teléfono (Opcional)</label>
          <input
            id="phone"
            v-model="phone"
            type="tel"
            placeholder="+57 300 1234567"
          />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            placeholder="••••••••"
          />
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirmar Contraseña</label>
          <input
            id="confirmPassword"
            v-model="confirmPassword"
            type="password"
            required
            placeholder="••••••••"
          />
        </div>

        <div v-if="userStore.error" class="error-message">
          {{ userStore.error }}
        </div>

        <div v-if="passwordError" class="error-message">
          {{ passwordError }}
        </div>

        <button
          type="submit"
          :disabled="userStore.isLoading"
          class="submit-btn"
        >
          {{ userStore.isLoading ? 'Cargando...' : 'Crear Cuenta' }}
        </button>
      </form>

      <div class="login-link">
        ¿Ya tienes cuenta? <router-link to="/login">Inicia sesión aquí</router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/userStore';

const name = ref('');
const email = ref('');
const phone = ref('');
const password = ref('');
const confirmPassword = ref('');
const userStore = useUserStore();
const router = useRouter();

const passwordError = computed(() => {
  if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
    return 'Las contraseñas no coinciden';
  }
  return '';
});

const handleRegister = async () => {
  if (passwordError.value) return;

  const success = await userStore.register(
    name.value,
    email.value,
    password.value,
    phone.value
  );
  if (success) {
    router.push('/');
  }
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
  padding: 2rem 0;
}

.register-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 450px;
}

.register-card h1 {
  text-align: center;
  margin-bottom: 2rem;
  color: #2c3e50;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #2c3e50;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #bdc3c7;
  border-radius: 4px;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.error-message {
  background-color: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.submit-btn {
  width: 100%;
  padding: 0.75rem;
  background-color: #27ae60;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s;
}

.submit-btn:hover:not(:disabled) {
  background-color: #229954;
}

.submit-btn:disabled {
  background-color: #95a5a6;
  cursor: not-allowed;
}

.login-link {
  text-align: center;
  margin-top: 1.5rem;
  color: #7f8c8d;
}

.login-link a {
  color: #3498db;
  text-decoration: none;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>
