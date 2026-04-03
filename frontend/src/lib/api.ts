import axios from 'axios';
import i18n from './i18n';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  
  const lng = i18n.language || 'fr';
  config.headers['Accept-Language'] = lng;
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      // Redirect to login if already on a protected page
      const isAuthPage = window.location.pathname.includes('/login') || 
                        window.location.pathname.includes('/register') ||
                        window.location.pathname.includes('/verify-email');
      
      if (!isAuthPage) {
        window.location.href = '/login';
      }
    } else if (error.response?.status === 500) {
      // Global 500 error handling
      window.location.href = '/500';
    }
    return Promise.reject(error);
  }
);

export default api;
