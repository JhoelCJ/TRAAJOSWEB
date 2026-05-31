// src/composables/useMenu.ts

import { ref, computed } from 'vue';
import axios from 'axios';
import { Dish } from '@types/index';

export const useMenu = () => {
  const dishes = ref<Dish[]>([]);
  const categories = ref<string[]>([]);
  const selectedCategory = ref<string>('');
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const filteredDishes = computed(() => {
    if (!selectedCategory.value) return dishes.value;
    return dishes.value.filter((d) => d.category === selectedCategory.value);
  });

  const fetchDishes = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{
        success: boolean;
        data: Dish[];
      }>('/api/menu', {
        params: { available: 'true' }
      });
      dishes.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch dishes';
    } finally {
      isLoading.value = false;
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await axios.get<{ success: boolean; data: string[] }>(
        '/api/menu/categories'
      );
      categories.value = response.data.data;
    } catch (err) {
      console.error('Failed to fetch categories:', err);
    }
  };

  const getDishById = async (id: string) => {
    try {
      const response = await axios.get<{ success: boolean; data: Dish }>(
        `/api/menu/${id}`
      );
      return response.data.data;
    } catch (err) {
      error.value = 'Failed to fetch dish details';
      return null;
    }
  };

  return {
    dishes,
    categories,
    selectedCategory,
    filteredDishes,
    isLoading,
    error,
    fetchDishes,
    fetchCategories,
    getDishById
  };
};
