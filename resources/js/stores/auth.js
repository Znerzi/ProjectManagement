import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const token = ref(localStorage.getItem('auth_token') || null);
    const loading = ref(false);
    const error = ref(null);

    // Set default axios header
    if (token.value) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
    }

    const isAuthenticated = computed(() => !!user.value);

    const setUser = (userData) => {
        user.value = userData;
        localStorage.setItem('user', JSON.stringify(userData));
    };

    const setToken = (authToken) => {
        token.value = authToken;
        localStorage.setItem('auth_token', authToken);
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
    };

    const clearAuth = () => {
        user.value = null;
        token.value = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
    };

    const register = async (formData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post('/api/auth/register', formData);
            setUser(response.data.user);
            setToken(response.data.token);
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Registration failed';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const login = async (email, password) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post('/api/auth/login', { email, password });
            setUser(response.data.user);
            setToken(response.data.token);
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const logout = async () => {
        loading.value = true;
        try {
            await axios.post('/api/auth/logout');
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            clearAuth();
            loading.value = false;
        }
    };

    const fetchProfile = async () => {
        try {
            const response = await axios.get('/api/auth/profile');
            setUser(response.data);
            return response.data;
        } catch (err) {
            clearAuth();
            throw err;
        }
    };

    const updateProfile = async (formData) => {
        try {
            const response = await axios.put('/api/auth/profile', formData);
            setUser(response.data.user);
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Profile update failed';
            throw err;
        }
    };

    // Check if user has specific role
    const hasRole = (role) => user.value?.role === role;

    // Check if user is of specific roles
    const hasAnyRole = (...roles) => roles.includes(user.value?.role);

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        setUser,
        setToken,
        clearAuth,
        register,
        login,
        logout,
        fetchProfile,
        updateProfile,
        hasRole,
        hasAnyRole
    };
});
