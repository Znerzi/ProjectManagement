<template>
  <div id="app" class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <router-view v-if="isReady" />
    <div v-else class="flex items-center justify-center min-h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const isReady = ref(false);

onMounted(async () => {
  try {
    // Restore user from localStorage if token exists
    if (authStore.token) {
      const userData = localStorage.getItem('user');
      if (userData) {
        authStore.setUser(JSON.parse(userData));
      }
    }
  } catch (error) {
    console.error('Error restoring auth:', error);
  } finally {
    isReady.value = true;

    // Redirect based on user role
    if (authStore.isAuthenticated) {
      const roleRoutes = {
        admin: '/admin/dashboard',
        client: '/client/dashboard',
        developer: '/developer/dashboard'
      };
      const route = roleRoutes[authStore.user?.role];
      if (route && router.currentRoute.value.path === '/') {
        router.push(route);
      }
    }
  }
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

* {
  font-family: 'Inter', sans-serif;
}
</style>
