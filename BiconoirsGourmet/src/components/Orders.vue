<!-- src/components/Orders.vue -->

<template>
  <div class="orders-container">
    <div class="container">
      <h1>Mis Órdenes</h1>

      <div v-if="!userStore.isAuthenticated" class="not-authenticated">
        <p>Debes iniciar sesión para ver tus órdenes</p>
        <router-link to="/login" class="login-btn">Iniciar Sesión</router-link>
      </div>

      <div v-else>
        <div v-if="ordersStore.isLoading" class="loading">Cargando órdenes...</div>

        <div v-else-if="ordersStore.orders.length === 0" class="empty">
          <p>No tienes órdenes aún</p>
          <router-link to="/menu">Realiza tu primer pedido</router-link>
        </div>

        <div v-else class="orders-list">
          <div v-for="order in ordersStore.orders" :key="order.order_id" class="order-card">
            <div class="order-header">
              <h3>Orden #{{ order.order_id.slice(0, 8) }}</h3>
              <span :class="['status', getStatusClass(order.status)]">
                {{ getStatusLabel(order.status) }}
              </span>
            </div>
            
            <div class="order-details">
              <p><strong>Fecha:</strong> {{ formatDate(order.created_at) }}</p>
              <p><strong>Total:</strong> {{ formatPrice(order.total_amount) }}</p>
              <p><strong>Artículos:</strong> {{ order.orderDetails?.length || 0 }}</p>
            </div>

            <div class="order-items">
              <div v-for="item in order.orderDetails" :key="item.detail_id" class="order-item">
                <span>{{ item.dish?.name }}</span>
                <span>x{{ item.quantity }}</span>
                <span>{{ formatPrice(item.subtotal) }}</span>
              </div>
            </div>

            <div class="order-actions">
              <button
                v-if="order.status !== 'Cancelled'"
                @click="cancelOrder(order.order_id)"
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
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useOrders } from '@composables/useOrders';
import { useUserStore } from '@stores/userStore';
import { formatPrice, formatDate, getStatusLabel } from '@utils/formatters';

const ordersStore = useOrders();
const userStore = useUserStore();

onMounted(() => {
  if (userStore.isAuthenticated) {
    ordersStore.fetchUserOrders();
  }
});

const getStatusClass = (status: string) => {
  return status.toLowerCase().replace(' ', '-');
};

const cancelOrder = async (id: string) => {
  if (confirm('¿Deseas cancelar esta orden?')) {
    await ordersStore.cancelOrder(id);
  }
};
</script>

<style scoped>
.orders-container {
  padding: 2rem 0;
  min-height: 60vh;
}

.container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 2rem;
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

.login-btn:hover {
  background-color: #2980b9;
}

.loading,
.empty {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}

.empty a {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: #27ae60;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}

.orders-list {
  display: grid;
  gap: 1.5rem;
}

.order-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ecf0f1;
}

.order-header h3 {
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
  background-color: #3498db;
}

.status.completed {
  background-color: #27ae60;
}

.status.cancelled {
  background-color: #e74c3c;
}

.order-details {
  padding: 1rem;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.order-details p {
  margin: 0;
}

.order-items {
  padding: 1rem;
  background-color: #f8f9fa;
}

.order-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  font-size: 0.95rem;
}

.order-actions {
  padding: 1rem;
  display: flex;
  gap: 0.5rem;
}

.cancel-btn {
  padding: 0.5rem 1rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cancel-btn:hover {
  background-color: #c0392b;
}

@media (max-width: 768px) {
  .order-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .order-details {
    grid-template-columns: 1fr;
  }
}
</style>
