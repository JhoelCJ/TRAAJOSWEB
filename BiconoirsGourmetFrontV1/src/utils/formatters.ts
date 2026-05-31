// src/utils/formatters.ts

export const formatPrice = (price: number | string): string => {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(Number(price));
};

export const formatDate = (date: string | Date): string => {
  return new Intl.DateTimeFormat('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date(date));
};

export const formatDateTime = (date: string | Date): string => {
  return new Intl.DateTimeFormat('es-CO', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(date));
};

export const formatTime = (date: string | Date): string => {
  return new Intl.DateTimeFormat('es-CO', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  }).format(new Date(date));
};

export const getStatusColor = (status: string): string => {
  const colors: { [key: string]: string } = {
    'Pending': 'yellow',
    'Pendiente': 'yellow',
    'Confirmed': 'blue',
    'Confirmada': 'blue',
    'Completed': 'green',
    'Completada': 'green',
    'Cancelled': 'red',
    'Cancelada': 'red'
  };
  return colors[status] || 'gray';
};

export const getStatusLabel = (status: string): string => {
  const labels: { [key: string]: string } = {
    'Pending': 'Pendiente',
    'Pendiente': 'Pendiente',
    'Confirmed': 'Confirmada',
    'Confirmada': 'Confirmada',
    'Completed': 'Completada',
    'Completada': 'Completada',
    'Cancelled': 'Cancelada',
    'Cancelada': 'Cancelada'
  };
  return labels[status] || status;
};
