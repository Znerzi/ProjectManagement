<template>
  <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <div class="text-2xl font-bold text-indigo-600">PrimeOut</div>
          <span class="text-gray-600 dark:text-gray-400 text-sm">Project Management</span>
        </div>

        <!-- Center Navigation -->
        <div class="hidden md:flex items-center gap-6">
          <router-link
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium text-sm transition-colors"
            :class="{ 'text-indigo-600 dark:text-indigo-400': isActive(item.to) }"
          >
            {{ item.label }}
          </router-link>
        </div>

        <!-- User Menu & Actions -->
        <div class="flex items-center gap-4">
          <!-- User Profile -->
          <div class="relative">
            <button
              @click="toggleUserMenu"
              class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"
            >
              <img
                v-if="authStore.user?.avatar"
                :src="authStore.user.avatar"
                :alt="authStore.user.name"
                class="w-8 h-8 rounded-full object-cover"
              />
              <div v-else class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                {{ authStore.user?.name?.charAt(0) || 'U' }}
              </div>
              <span class="hidden sm:inline text-sm font-medium text-gray-900 dark:text-white">
                {{ authStore.user?.name }}
              </span>
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="showUserMenu"
              class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
            >
              <router-link
                to="/profile"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                @click="showUserMenu = false"
              >
                Profile Settings
              </router-link>
              <button
                @click="logout"
                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700"
              >
                Logout
              </button>
            </div>
          </div>

          <!-- Mobile menu button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="showMobileMenu" class="md:hidden border-t border-gray-200 dark:border-gray-700 py-2">
        <router-link
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"
          @click="showMobileMenu = false"
        >
          {{ item.label }}
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter, useRoute } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const showUserMenu = ref(false);
const showMobileMenu = ref(false);

const navItems = computed(() => {
  const baseItems = [];

  if (authStore.user?.role === 'admin') {
    return [
      { label: 'Dashboard', to: '/admin/dashboard' }
    ];
  } else if (authStore.user?.role === 'client') {
    return [
      { label: 'Dashboard', to: '/client/dashboard' },
      { label: 'Projects', to: '/client/projects' }
    ];
  } else if (authStore.user?.role === 'developer') {
    return [
      { label: 'Dashboard', to: '/developer/dashboard' },
      { label: 'Tasks', to: '/developer/tasks' },
      { label: 'Assignments', to: '/developer/assignments' }
    ];
  }

  return baseItems;
});

const isActive = (path) => {
  return route.path.startsWith(path);
};

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
};

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>
