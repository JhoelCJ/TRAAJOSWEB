// src/stores/adminStore.ts

import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { Order, Reservation, Survey } from '@types/index';

interface DashboardStats {
  totalOrders: number;
  totalRevenue: number;
  pendingOrders: number;
  totalCustomers: number;
}

export const useAdminStore = defineStore('admin', () => {
  const stats = ref<DashboardStats | null>(null);
  const orders = ref<Order[]>([]);
  const reservations = ref<Reservation[]>([]);
  const surveys = ref<Survey[]>([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const fetchDashboardStats = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{
        success: boolean;
        data: DashboardStats;
      }>('/api/admin/stats');
      stats.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch stats';
    } finally {
      isLoading.value = false;
    }
  };

  const fetchAllOrders = async (status?: string, page: number = 1) => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{ success: boolean; data: Order[] }>(
        '/api/admin/orders',
        { params: { status, page, limit: 50 } }
      );
      orders.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch orders';
    } finally {
      isLoading.value = false;
    }
  };

  const fetchAllReservations = async (status?: string, page: number = 1) => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{
        success: boolean;
        data: Reservation[];
      }>('/api/admin/reservations', { params: { status, page, limit: 50 } });
      reservations.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error ||
        'Failed to fetch reservations';
    } finally {
      isLoading.value = false;
    }
  };

  const fetchSurveys = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{ success: boolean; data: Survey[] }>(
        '/api/admin/surveys'
      );
      surveys.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to fetch surveys';
    } finally {
      isLoading.value = false;
    }
  };

  const updateOrderStatus = async (orderId: string, status: string) => {
    try {
      const response = await axios.put<{
        success: boolean;
        data: Order;
      }>(`/api/orders/${orderId}/status`, { status });
      
      const index = orders.value.findIndex((o) => o.order_id === orderId);
      if (index !== -1) {
        orders.value[index] = response.data.data;
      }
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Failed to update status';
      return false;
    }
  };

  return {
    stats,
    orders,
    reservations,
    surveys,
    isLoading,
    error,
    fetchDashboardStats,
    fetchAllOrders,
    fetchAllReservations,
    fetchSurveys,
    updateOrderStatus
  };
});
