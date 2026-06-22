import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Public Pages
import HomePage from '../pages/Home.vue';
import LoginPage from '../pages/auth/LoginPage.vue';
import RegisterPage from '../pages/auth/RegisterPage.vue';
import RegisterClientPage from '../pages/auth/RegisterClientPage.vue';
import RegisterDeveloperPage from '../pages/auth/RegisterDeveloperPage.vue';


// Admin Pages
import AdminDashboard from '../pages/admin/Dashboard.vue';

// Client Pages
import ClientDashboard from '../pages/client/Dashboard.vue';
import ClientProjects from '../pages/client/Projects.vue';
import ClientProjectDetail from '../pages/client/ProjectDetail.vue';

// Developer Pages
import DeveloperDashboard from '../pages/developer/Dashboard.vue';
import DeveloperTasks from '../pages/developer/Tasks.vue';
import DeveloperAssignments from '../pages/developer/Assignments.vue';

const routes = [
    // Public Routes
    {
        path: '/',
        name: 'home',
        component: HomePage,
        meta: { requiresGuest: true }
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
        meta: { requiresGuest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterPage,
        meta: { requiresGuest: true }
    },
    {
        path: '/register/client',
        name: 'register-client',
        component: RegisterClientPage,
        meta: { requiresGuest: true }
    },
    {
        path: '/register/developer',
        name: 'register-developer',
        component: RegisterDeveloperPage,
        meta: { requiresGuest: true }
    },


    // Admin Routes
    {
        path: '/admin/dashboard',
        name: 'admin-dashboard',
        component: AdminDashboard,
        meta: { requiresAuth: true, role: 'admin' }
    },

    // Client Routes
    {
        path: '/client/dashboard',
        name: 'client-dashboard',
        component: ClientDashboard,
        meta: { requiresAuth: true, role: 'client' }
    },
    {
        path: '/client/projects',
        name: 'client-projects',
        component: ClientProjects,
        meta: { requiresAuth: true, role: 'client' }
    },
    {
        path: '/client/projects/:id',
        name: 'client-project-detail',
        component: ClientProjectDetail,
        meta: { requiresAuth: true, role: 'client' }
    },

    // Developer Routes
    {
        path: '/developer/dashboard',
        name: 'developer-dashboard',
        component: DeveloperDashboard,
        meta: { requiresAuth: true, role: 'developer' }
    },
    {
        path: '/developer/tasks',
        name: 'developer-tasks',
        component: DeveloperTasks,
        meta: { requiresAuth: true, role: 'developer' }
    },
    {
        path: '/developer/assignments',
        name: 'developer-assignments',
        component: DeveloperAssignments,
        meta: { requiresAuth: true, role: 'developer' }
    },

    // Catch-all for 404
    {
        path: '/:pathMatch(.*)*',
        redirect: '/login'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation Guards
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Check if route requires authentication
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login');
        return;
    }

    // Check if route requires guest (not authenticated)
    if (to.meta.requiresGuest && authStore.isAuthenticated) {
        next('/dashboard');
        return;
    }

    // Check if user has required role
    if (to.meta.role && authStore.user?.role !== to.meta.role) {
        next('/dashboard');
        return;
    }

    next();
});

export default router;
