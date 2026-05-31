// src/stores/userStore.ts

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';
import { User, AuthResponse } from '@types/index';

export const useUserStore = defineStore('user', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('auth_token'));
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const isAuthenticated = computed(() => !!token.value && !!user.value);
  const isAdmin = computed(() => user.value?.role === 'admin');

  const setToken = (newToken: string | null) => {
    token.value = newToken;
    if (newToken) {
      localStorage.setItem('auth_token', newToken);
      axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
    } else {
      localStorage.removeItem('auth_token');
      delete axios.defaults.headers.common['Authorization'];
    }
  };

  const login = async (email: string, password: string) => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.post<AuthResponse>('/api/auth/login', {
        email,
        password
      });
      const { token: newToken, user: newUser } = response.data.data!;
      setToken(newToken);
      user.value = newUser;
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Login failed';
      return false;
    } finally {
      isLoading.value = false;
    }
  };

  const register = async (
    name: string,
    email: string,
    password: string,
    phone?: string
  ) => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await axios.post<AuthResponse>('/api/auth/register', {
        name,
        email,
        password,
        phone
      });
      const { token: newToken, user: newUser } = response.data.data!;
      setToken(newToken);
      user.value = newUser;
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.error || 'Registration failed';
      return false;
    } finally {
      isLoading.value = false;
    }
  };

  const logout = () => {
    user.value = null;
    setToken(null);
  };

  const getCurrentUser = async () => {
    if (!token.value) return;
    isLoading.value = true;
    try {
      const response = await axios.get<{ success: boolean; data: User }>(
        '/api/auth/me'
      );
      user.value = response.data.data;
    } catch (err) {
      logout();
    } finally {
      isLoading.value = false;
    }
  };

  // Initialize with stored token
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
    getCurrentUser();
  }

  return {
    user,
    token,
    isLoading,
    error,
    isAuthenticated,
    isAdmin,
    login,
    register,
    logout,
    getCurrentUser
  };
});
