<!-- src/components/Reservations.vue -->

<template>
  <div class="reservations-container">
    <div class="container">
      <h1>Mis Reservas</h1>

      <div v-if="!userStore.isAuthenticated" class="not-authenticated">
        <p>Debes iniciar sesión para hacer reservas</p>
        <router-link to="/login" class="login-btn">Iniciar Sesión</router-link>
      </div>

      <div v-else class="reservations-content">
        <!-- Formulario para nueva reserva -->
        <div class="new-reservation">
          <h2>Nueva Reserva</h2>
          <form @submit.prevent="handleCreateReservation">
            <div class="form-group">
              <label for="date">Fecha</label>
              <input
                id="date"
                v-model="formData.date"
                type="datetime-local"
                required
              />
            </div>

            <div class="form-group">
              <label for="partySize">Número de Personas</label>
              <input
                id="partySize"
                v-model.number="formData.partySize"
                type="number"
                min="1"
                max="50"
                required
              />
            </div>

            <div class="form-group">
              <label for="requests">Solicitudes Especiales</label>
              <textarea
                id="requests"
                v-model="formData.requests"
                placeholder="Alergias, preferencias, etc."
                rows="3"
              ></textarea>
            </div>

            <div v-if="reservationsStore.error" class="error-message">
              {{ reservationsStore.error }}
            </div>

            <button
              type="submit"
              :disabled="reservationsStore.isLoading"
              class="submit-btn"
            >
              {{ reservationsStore.isLoading ? 'Creando...' : 'Crear Reserva' }}
            </button>
          </form>
        </div>

        <!-- Lista de reservas -->
        <div class="reservations-list">
          <h2>Mis Reservas</h2>

          <div v-if="reservationsStore.isLoading" class="loading">
            Cargando reservas...
          </div>

          <div v-else-if="reservationsStore.reservations.length === 0" class="empty">
            No tienes reservas aún
          </div>

          <div v-else class="reservations-grid">
            <div v-for="res in reservationsStore.reservations" :key="res.reservation_id" class="reservation-card">
              <div class="res-header">
                <h3>{{ formatDate(res.reservation_date) }}</h3>
                <span :class="['status', getStatusClass(res.status)]">
                  {{ getStatusLabel(res.status) }}
                </span>
              </div>

              <div class="res-details">
                <p><strong>Hora:</strong> {{ formatTime(res.reservation_date) }}</p>
                <p><strong>Personas:</strong> {{ res.party_size }}</p>
                <p v-if="res.special_requests"><strong>Solicitudes:</strong> {{ res.special_requests }}</p>
              </div>

              <div class="res-actions">
                <button
                  v-if="res.status !== 'Cancelled'"
                  @click="cancelReservation(res.reservation_id)"
                  class="cancel-btn"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useReservations } from '../composables/useReservations';
import { useUserStore } from '../stores/userStore';
import { formatDate, formatTime, getStatusLabel } from '../utils/formatters';

const reservationsStore = useReservations();
const userStore = useUserStore();

const formData = ref({
  date: '',
  partySize: 2,
  requests: ''
});

onMounted(() => {
  if (userStore.isAuthenticated) {
    reservationsStore.fetchReservations();
  }
});

const getStatusClass = (status: string) => {
  return status.toLowerCase().replace(' ', '-');
};

const handleCreateReservation = async () => {
  const success = await reservationsStore.createReservation(
    new Date(formData.value.date).toISOString(),
    formData.value.partySize,
    formData.value.requests
  );

  if (success) {
    alert('¡Reserva creada exitosamente!');
    formData.value = { date: '', partySize: 2, requests: '' };
  }
};

const cancelReservation = async (id: string) => {
  if (confirm('¿Deseas cancelar esta reserva?')) {
    await reservationsStore.cancelReservation(id);
  }
};
</script>

<style scoped>
.reservations-container {
  padding: 2rem 0;
  min-height: 60vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 2rem;
}

h2 {
  color: #2c3e50;
  margin-bottom: 1.5rem;
}

.not-authenticated {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}

.login-btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: #3498db;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  margin-top: 1rem;
}

.reservations-content {
  display: grid;
  grid-template-columns: 400px 1fr;
  gap: 2rem;
}

.new-reservation {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #2c3e50;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #bdc3c7;
  border-radius: 4px;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input:focus,
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
}

.reservations-list {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.loading,
.empty {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}

.reservations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.reservation-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 1rem;
  border-left: 4px solid #3498db;
}

.res-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.res-header h3 {
  margin: 0;
  color: #2c3e50;
}

.status {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: bold;
  color: white;
}

.status.pending {
  background-color: #f39c12;
}

.status.confirmed {
  background-color: #27ae60;
}

.status.cancelled {
  background-color: #e74c3c;
}

.res-details p {
  margin: 0.25rem 0;
  font-size: 0.95rem;
}

.res-actions {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #ecf0f1;
}

.cancel-btn {
  padding: 0.5rem 1rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}

.cancel-btn:hover {
  background-color: #c0392b;
}

@media (max-width: 768px) {
  .reservations-content {
    grid-template-columns: 1fr;
  }

  .new-reservation {
    height: auto;
  }
}
</style>
