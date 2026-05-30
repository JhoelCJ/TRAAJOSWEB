// src/composables/useOrders.ts

import { ref } from 'vue';
import axios from 'axios';
import { Order } from '@types/index';

export const useOrders = () => {
  const orders = ref<Order[]>([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const fetchUserOrders = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{ success: boolean; data: Order[] }>(
        '/api/orders'
      );
      orders.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch orders';
    } finally {
      isLoading.value = false;
    }
  };

  const getOrderById = async (id: string) => {
    try {
      const response = await axios.get<{ success: boolean; data: Order }>(
        `/api/orders/${id}`
      );
      return response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch order';
      return null;
    }
  };

  const cancelOrder = async (id: string) => {
    try {
      const response = await axios.delete<{ success: boolean; data: Order }>(
        `/api/orders/${id}`
      );
      const index = orders.value.findIndex((o) => o.order_id === id);
      if (index !== -1) {
        orders.value[index] = response.data.data;
      }
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to cancel order';
      return false;
    }
  };

  return {
    orders,
    isLoading,
    error,
    fetchUserOrders,
    getOrderById,
    cancelOrder
  };
};
