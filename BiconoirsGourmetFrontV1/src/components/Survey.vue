<!-- src/components/Survey.vue -->

<template>
  <div class="survey-container">
    <div class="container">
      <h1>Cuéntanos Tu Experiencia</h1>
      <p class="subtitle">Tu opinión es muy importante para nosotros</p>

      <div class="survey-card">
        <form @submit.prevent="submitSurvey">
          <div class="form-group">
            <label>Calificación</label>
            <div class="rating">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="formData.rating = star"
                :class="{ active: formData.rating >= star }"
                class="star-btn"
              >
                ⭐
              </button>
            </div>
          </div>

          <div class="form-group">
            <label for="comments">Comentarios</label>
            <textarea
              id="comments"
              v-model="formData.comments"
              placeholder="Comparte tus comentarios sobre tu experiencia..."
              rows="5"
            ></textarea>
          </div>

          <div v-if="error" class="error-message">
            {{ error }}
          </div>

          <div v-if="success" class="success-message">
            ¡Gracias por tu comentario!
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="submit-btn"
          >
            {{ isLoading ? 'Enviando...' : 'Enviar Encuesta' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../stores/userStore';

const userStore = useUserStore();
const formData = ref({
  rating: 0,
  comments: ''
});
const isLoading = ref(false);
const error = ref('');
const success = ref(false);

const submitSurvey = async () => {
  if (!userStore.isAuthenticated) {
    error.value = 'Debes iniciar sesión para enviar una encuesta';
    return;
  }

  if (formData.value.rating === 0) {
    error.value = 'Por favor selecciona una calificación';
    return;
  }

  isLoading.value = true;
  error.value = '';
  success.value = false;

  try {
    await axios.post('/api/surveys', {
      rating: formData.value.rating,
      comments: formData.value.comments
    });
    success.value = true;
    formData.value = { rating: 0, comments: '' };
    setTimeout(() => {
      success.value = false;
    }, 3000);
  } catch (err: any) {
    error.value = err.response?.data?.error || 'Error al enviar la encuesta';
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.survey-container {
  padding: 3rem 0;
  background-color: #f8f9fa;
  min-height: 60vh;
}

.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.subtitle {
  text-align: center;
  color: #7f8c8d;
  font-size: 1.1rem;
  margin-bottom: 2rem;
}

.survey-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 2rem;
}

.form-group label {
  display: block;
  margin-bottom: 1rem;
  font-weight: 500;
  color: #2c3e50;
}

.rating {
  display: flex;
  gap: 1rem;
  justify-content: center;
}

.star-btn {
  background: none;
  border: none;
  font-size: 2.5rem;
  cursor: pointer;
  opacity: 0.3;
  transition: opacity 0.3s;
}

.star-btn:hover,
.star-btn.active {
  opacity: 1;
}

.form-group textarea {
  width: 100%;
  padding: 1rem;
  border: 1px solid #bdc3c7;
  border-radius: 4px;
  font-size: 1rem;
  font-family: inherit;
  resize: vertical;
  box-sizing: border-box;
}

.form-group textarea:focus {
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

.success-message {
  background-color: #efe;
  color: #3c3;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.submit-btn {
  width: 100%;
  padding: 1rem;
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
}
</style>
