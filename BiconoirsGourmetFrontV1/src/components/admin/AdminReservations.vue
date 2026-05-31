<!-- src/components/admin/AdminReservations.vue -->

<template>
  <div class="admin-reservations">
    <div class="filters">
      <select v-model="selectedStatus" class="filter-select">
        <option value="">Todos los estados</option>
        <option value="Pending">Pendiente</option>
        <option value="Confirmed">Confirmada</option>
        <option value="Cancelled">Cancelada</option>
      </select>
      <button @click="loadReservations" class="refresh-btn">🔄 Actualizar</button>
    </div>

    <div v-if="adminStore.isLoading" class="loading">Cargando reservas...</div>

    <div v-else-if="adminStore.reservations.length === 0" class="empty">
      No hay reservas
    </div>

    <table v-else class="reservations-table">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Personas</th>
          <th>Estado</th>
          <th>Solicitudes</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="res in adminStore.reservations" :key="res.reservation_id">
          <td>{{ res.customer?.name }}</td>
          <td>{{ formatDate(res.reservation_date) }}</td>
          <td>{{ res.party_size }}</td>
          <td>{{ res.status }}</td>
          <td>{{ res.special_requests || '-' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAdminStore } from '@stores/adminStore';
import { formatDate } from '@utils/formatters';

const adminStore = useAdminStore();
const selectedStatus = ref('');

const loadReservations = async () => {
  await adminStore.fetchAllReservations(selectedStatus.value);
};
</script>

<style scoped>
.admin-reservations {
  width: 100%;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.filter-select {
  padding: 0.5rem;
  border: 1px solid #bdc3c7;
  border-radius: 4px;
}

.refresh-btn {
  padding: 0.5rem 1rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}

.empty {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}

.reservations-table {
  width: 100%;
  border-collapse: collapse;
}

.reservations-table thead {
  background-color: #f8f9fa;
}

.reservations-table th {
  padding: 1rem;
  text-align: left;
  color: #2c3e50;
  font-weight: 600;
  border-bottom: 2px solid #ecf0f1;
}

.reservations-table td {
  padding: 1rem;
  border-bottom: 1px solid #ecf0f1;
}
</style>
