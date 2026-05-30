<!-- src/components/admin/AdminOrders.vue -->

<template>
  <div class="admin-orders">
    <div class="filters">
      <select v-model="selectedStatus" class="filter-select">
        <option value="">Todos los estados</option>
        <option value="Pending">Pendiente</option>
        <option value="Confirmed">Confirmado</option>
        <option value="Completed">Completado</option>
        <option value="Cancelled">Cancelado</option>
      </select>
      <button @click="loadOrders" class="refresh-btn">🔄 Actualizar</button>
    </div>

    <div v-if="adminStore.isLoading" class="loading">Cargando órdenes...</div>

    <div v-else-if="adminStore.orders.length === 0" class="empty">
      No hay órdenes
    </div>

    <table v-else class="orders-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in adminStore.orders" :key="order.order_id">
          <td>{{ order.order_id.slice(0, 8) }}</td>
          <td>{{ order.customer?.name }}</td>
          <td>{{ formatPrice(order.total_amount) }}</td>
          <td>
            <select
              :value="order.status"
              @change="(e) => updateStatus(order.order_id, (e.target as HTMLSelectElement).value)"
              class="status-select"
            >
              <option value="Pending">Pendiente</option>
              <option value="Confirmed">Confirmado</option>
              <option value="Completed">Completado</option>
              <option value="Cancelled">Cancelado</option>
            </select>
          </td>
          <td>{{ formatDate(order.created_at) }}</td>
          <td>
            <button class="view-btn">Ver</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAdminStore } from '@stores/adminStore';
import { formatPrice, formatDate } from '@utils/formatters';

const adminStore = useAdminStore();
const selectedStatus = ref('');

const loadOrders = async () => {
  await adminStore.fetchAllOrders(selectedStatus.value);
};

const updateStatus = async (orderId: string, status: string) => {
  await adminStore.updateOrderStatus(orderId, status);
};
</script>

<style scoped>
.admin-orders {
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

.orders-table {
  width: 100%;
  border-collapse: collapse;
}

.orders-table thead {
  background-color: #f8f9fa;
}

.orders-table th {
  padding: 1rem;
  text-align: left;
  color: #2c3e50;
  font-weight: 600;
  border-bottom: 2px solid #ecf0f1;
}

.orders-table td {
  padding: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.status-select {
  padding: 0.25rem;
  border: 1px solid #bdc3c7;
  border-radius: 4px;
}

.view-btn {
  padding: 0.25rem 0.75rem;
  background-color: #27ae60;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.85rem;
}
</style>
