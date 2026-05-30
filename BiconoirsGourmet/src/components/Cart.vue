<!-- src/components/Cart.vue -->

<template>
  <div class="cart-container">
    <div class="container">
      <h1>Carrito de Compras</h1>

      <div v-if="cartStore.items.length === 0" class="empty-cart">
        <p>Tu carrito está vacío</p>
        <router-link to="/menu" class="continue-btn">Volver al Menú</router-link>
      </div>

      <div v-else class="cart-content">
        <div class="cart-items">
          <div v-for="item in cartStore.items" :key="item.dish_id" class="cart-item">
            <div class="item-details">
              <h3>{{ item.dish.name }}</h3>
              <p class="price">{{ formatPrice(item.dish.price) }} c/u</p>
            </div>
            <div class="item-controls">
              <button @click="cartStore.updateQuantity(item.dish_id, item.quantity - 1)">-</button>
              <span>{{ item.quantity }}</span>
              <button @click="cartStore.updateQuantity(item.dish_id, item.quantity + 1)">+</button>
            </div>
            <div class="item-subtotal">
              {{ formatPrice(Number(item.dish.price) * item.quantity) }}
            </div>
            <button
              @click="cartStore.removeItem(item.dish_id)"
              class="remove-btn"
            >
              ✕
            </button>
          </div>
        </div>

        <div class="cart-summary">
          <div class="summary-row">
            <span>Subtotal:</span>
            <span>{{ formatPrice(cartStore.totalPrice) }}</span>
          </div>
          <div class="summary-row total">
            <span>Total:</span>
            <span>{{ formatPrice(cartStore.totalPrice) }}</span>
          </div>

          <div v-if="cartStore.error" class="error-message">
            {{ cartStore.error }}
          </div>

          <button
            @click="handleCheckout"
            :disabled="cartStore.isLoading || !userStore.isAuthenticated"
            class="checkout-btn"
          >
            {{ cartStore.isLoading ? 'Procesando...' : 'Procesar Pedido' }}
          </button>

          <div v-if="!userStore.isAuthenticated" class="login-required">
            <router-link to="/login">Inicia sesión para continuar</router-link>
          </div>

          <router-link to="/menu" class="continue-btn">Continuar Comprando</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '@stores/cartStore';
import { useUserStore } from '@stores/userStore';
import { useRouter } from 'vue-router';
import { formatPrice } from '@utils/formatters';

const cartStore = useCartStore();
const userStore = useUserStore();
const router = useRouter();

const handleCheckout = async () => {
  const order = await cartStore.checkout();
  if (order) {
    alert('¡Pedido creado exitosamente!');
    router.push('/orders');
  }
};
</script>

<style scoped>
.cart-container {
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

.empty-cart {
  text-align: center;
  padding: 3rem;
  color: #7f8c8d;
}

.empty-cart p {
  font-size: 1.2rem;
  margin-bottom: 1rem;
}

.cart-content {
  display: grid;
  grid-template-columns: 1fr 350px;
  gap: 2rem;
}

.cart-items {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.cart-item {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #ecf0f1;
  gap: 1rem;
}

.cart-item:last-child {
  border-bottom: none;
}

.item-details {
  flex: 1;
}

.item-details h3 {
  margin: 0;
  color: #2c3e50;
}

.price {
  color: #7f8c8d;
  margin: 0.25rem 0 0 0;
  font-size: 0.9rem;
}

.item-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.item-controls button {
  width: 30px;
  height: 30px;
  border: 1px solid #bdc3c7;
  background: white;
  cursor: pointer;
  border-radius: 4px;
}

.item-controls button:hover {
  background-color: #ecf0f1;
}

.item-subtotal {
  min-width: 100px;
  text-align: right;
  font-weight: bold;
  color: #27ae60;
}

.remove-btn {
  background: none;
  border: none;
  color: #e74c3c;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0;
  width: 30px;
  height: 30px;
}

.remove-btn:hover {
  color: #c0392b;
}

.cart-summary {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.summary-row.total {
  border-bottom: none;
  font-weight: bold;
  font-size: 1.2rem;
  color: #27ae60;
  padding-bottom: 0;
  margin-bottom: 1.5rem;
}

.error-message {
  background-color: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.checkout-btn {
  width: 100%;
  padding: 0.75rem;
  background-color: #27ae60;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  margin-bottom: 1rem;
}

.checkout-btn:hover:not(:disabled) {
  background-color: #229954;
}

.checkout-btn:disabled {
  background-color: #95a5a6;
  cursor: not-allowed;
}

.login-required {
  text-align: center;
  color: #e74c3c;
  margin-bottom: 1rem;
}

.login-required a {
  color: #e74c3c;
  text-decoration: none;
}

.login-required a:hover {
  text-decoration: underline;
}

.continue-btn {
  display: block;
  text-align: center;
  padding: 0.75rem;
  background-color: #3498db;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.continue-btn:hover {
  background-color: #2980b9;
}

@media (max-width: 768px) {
  .cart-content {
    grid-template-columns: 1fr;
  }

  .cart-item {
    flex-wrap: wrap;
  }

  .item-subtotal {
    flex-basis: 100%;
    text-align: right;
    padding-top: 0.5rem;
  }
}
</style>
