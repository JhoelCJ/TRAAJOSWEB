// src/composables/useReservations.ts

import { ref } from 'vue';
import axios from 'axios';
import { Reservation } from '@types/index';

export const useReservations = () => {
  const reservations = ref<Reservation[]>([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const fetchReservations = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.get<{
        success: boolean;
        data: Reservation[];
      }>('/api/reservations');
      reservations.value = response.data.data;
    } catch (err: any) {
      error.value = err.response?.data?.error ||
        'Failed to fetch reservations';
    } finally {
      isLoading.value = false;
    }
  };

  const createReservation = async (
    reservation_date: string,
    party_size: number,
    special_requests?: string
  ) => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.post<{
        success: boolean;
        data: Reservation;
      }>('/api/reservations', {
        reservation_date,
        party_size,
        special_requests
      });
      reservations.value.push(response.data.data);
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error ||
        'Failed to create reservation';
      return false;
    } finally {
      isLoading.value = false;
    }
  };

  const cancelReservation = async (id: string) => {
    try {
      const response = await axios.delete<{
        success: boolean;
        data: Reservation;
      }>(`/api/reservations/${id}`);
      const index = reservations.value.findIndex(
        (r) => r.reservation_id === id
      );
      if (index !== -1) {
        reservations.value[index] = response.data.data;
      }
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error ||
        'Failed to cancel reservation';
      return false;
    }
  };

  return {
    reservations,
    isLoading,
    error,
    fetchReservations,
    createReservation,
    cancelReservation
  };
};
