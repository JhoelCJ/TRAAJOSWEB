<!-- src/components/Menu.vue -->

<template>
  <div class="menu-container">
    <div class="container">
      <h1>Nuestro Menú</h1>
      
      <div class="filters">
        <button
          v-for="category in menuStore.categories"
          :key="category"
          @click="menuStore.selectedCategory = category"
          :class="{ active: menuStore.selectedCategory === category }"
          class="filter-btn"
        >
          {{ category }}
        </button>
        <button
          @click="menuStore.selectedCategory = ''"
          :class="{ active: menuStore.selectedCategory === '' }"
          class="filter-btn"
        >
          Todos
        </button>
      </div>

      <div v-if="menuStore.isLoading" class="loading">Cargando platos...</div>

      <div v-else-if="menuStore.filteredDishes.length === 0" class="empty">
        No hay platos disponibles
      </div>

      <div v-else class="dishes-grid">
        <div v-for="dish in menuStore.filteredDishes" :key="dish.dish_id" class="dish-card">
          <div class="dish-image">
            <img :src="dish.image_url || '/img/placeholder.jpg'" :alt="dish.name" />
          </div>
          <div class="dish-info">
            <h3>{{ dish.name }}</h3>
            <p class="description">{{ dish.description }}</p>
            <div class="dish-footer">
              <span class="price">{{ formatPrice(dish.price) }}</span>
              <button
                @click="addToCart(dish)"
                class="add-btn"
              >
                Agregar
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
import { useMenu } from '../composables/useMenu';
import { useCartStore } from '../stores/cartStore';
import { formatPrice } from '../utils/formatters';

const menuStore = useMenu();
const cartStore = useCartStore();

onMounted(() => {
  menuStore.fetchDishes();
  menuStore.fetchCategories();
});

const addToCart = (dish: any) => {
  cartStore.addItem(dish, 1);
  alert(`${dish.name} agregado al carrito`);
};
</script>

<style scoped>
.menu-container {
  padding: 2rem 0;
  background-color: #f8f9fa;
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

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  justify-content: center;
}

.filter-btn {
  padding: 0.5rem 1rem;
  border: 2px solid #bdc3c7;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}

.filter-btn:hover {
  border-color: #3498db;
}

.filter-btn.active {
  background-color: #3498db;
  color: white;
  border-color: #3498db;
}

.loading,
.empty {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
  font-size: 1.1rem;
}

.dishes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}

.dish-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.dish-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.dish-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
  background-color: #ecf0f1;
}

.dish-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.dish-info {
  padding: 1rem;
}

.dish-info h3 {
  margin: 0 0 0.5rem 0;
  color: #2c3e50;
}

.description {
  color: #7f8c8d;
  font-size: 0.9rem;
  margin: 0.5rem 0 1rem 0;
  line-height: 1.4;
}

.dish-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.price {
  font-size: 1.3rem;
  font-weight: bold;
  color: #27ae60;
}

.add-btn {
  padding: 0.5rem 1rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.add-btn:hover {
  background-color: #2980b9;
}
</style>
