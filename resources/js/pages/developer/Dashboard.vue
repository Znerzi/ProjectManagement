<template>
  <div class="space-y-6">
    <Navbar />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Developer Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome, {{ authStore.user?.name }}</p>
      </div>

      <!-- KPI Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <KPICard
          title="Active Assignments"
          :value="stats.active_assignments"
          icon="briefcase"
          color="blue"
        />
        <KPICard
          title="Completed Tasks"
          :value="stats.completed_tasks"
          icon="check-circle"
          color="green"
        />
        <KPICard
          title="Pending Tasks"
          :value="stats.pending_tasks"
          icon="clock"
          color="yellow"
        />
        <KPICard
          title="Average Rating"
          :value="stats.average_rating.toFixed(1)"
          icon="star"
          color="purple"
        />
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Urgent Tasks -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white">Active Tasks</h2>
              <span class="text-sm text-gray-500 dark:text-gray-400">{{ tasks.length }} tasks</span>
            </div>

            <div v-if="tasks.length > 0" class="space-y-4">
              <TaskCard
                v-for="task in tasks.slice(0, 5)"
                :key="task.id"
                :task="task"
              >
                <template #actions>
                  <div class="mt-4 flex gap-2">
                    <button
                      @click="openTaskDetail(task)"
                      class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm py-2 px-3 rounded transition"
                    >
                      Update Progress
                    </button>
                  </div>
                </template>
              </TaskCard>
            </div>
            <div v-else class="text-center py-8">
              <p class="text-gray-500 dark:text-gray-400">No active tasks</p>
            </div>
          </div>
        </div>

        <!-- Calendar & Urgency -->
        <div class="space-y-6">
          <!-- Task by Status -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Task Distribution</h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">Ongoing</span>
                <div class="flex items-center gap-2">
                  <div class="h-2 flex-1 min-w-[100px] bg-blue-200 dark:bg-blue-900 rounded-full">
                    <div class="h-full bg-blue-600 rounded-full" :style="{ width: `${(stats.ongoing_tasks / stats.total_tasks) * 100}%` }"></div>
                  </div>
                  <span class="text-sm font-semibold text-gray-900 dark:text-white w-8">{{ stats.ongoing_tasks }}</span>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">Pending</span>
                <div class="flex items-center gap-2">
                  <div class="h-2 flex-1 min-w-[100px] bg-gray-200 dark:bg-gray-700 rounded-full">
                    <div class="h-full bg-gray-600 rounded-full" :style="{ width: `${(stats.pending_tasks / stats.total_tasks) * 100}%` }"></div>
                  </div>
                  <span class="text-sm font-semibold text-gray-900 dark:text-white w-8">{{ stats.pending_tasks }}</span>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">Completed</span>
                <div class="flex items-center gap-2">
                  <div class="h-2 flex-1 min-w-[100px] bg-green-200 dark:bg-green-900 rounded-full">
                    <div class="h-full bg-green-600 rounded-full" :style="{ width: `${(stats.completed_tasks / stats.total_tasks) * 100}%` }"></div>
                  </div>
                  <span class="text-sm font-semibold text-gray-900 dark:text-white w-8">{{ stats.completed_tasks }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Upcoming Deadlines -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upcoming Deadlines</h3>
            <div v-if="upcomingDeadlines.length > 0" class="space-y-2">
              <div
                v-for="task in upcomingDeadlines.slice(0, 5)"
                :key="task.id"
                class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-sm"
              >
                <span class="text-gray-700 dark:text-gray-300">{{ task.module_name }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(task.deadline) }}</span>
              </div>
            </div>
            <div v-else class="text-center py-4">
              <p class="text-sm text-gray-500 dark:text-gray-400">No deadlines</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Task Progress Modal -->
      <TaskProgressModal
        v-if="selectedTask"
        :task="selectedTask"
        :is-open="showTaskModal"
        @close="showTaskModal = false"
        @update="updateTaskProgress"
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';
import Navbar from '../../components/Navbar.vue';
import TaskCard from '../../components/TaskCard.vue';
import KPICard from '../../components/KPICard.vue';
import TaskProgressModal from '../../components/TaskProgressModal.vue';

const authStore = useAuthStore();
const stats = ref({
  total_assignments: 0,
  active_assignments: 0,
  completed_assignments: 0,
  total_tasks: 0,
  completed_tasks: 0,
  ongoing_tasks: 0,
  pending_tasks: 0,
  average_rating: 0
});

const tasks = ref([]);
const selectedTask = ref(null);
const showTaskModal = ref(false);

const upcomingDeadlines = computed(() => {
  return tasks.value
    .filter(t => t.deadline && t.status !== 'completed')
    .sort((a, b) => new Date(a.deadline) - new Date(b.deadline));
});

onMounted(async () => {
  await loadDashboard();
});

const loadDashboard = async () => {
  try {
    const response = await axios.get('/api/developer/dashboard');
    stats.value = response.data.stats;
    tasks.value = response.data.tasks;
  } catch (error) {
    console.error('Error loading dashboard:', error);
  }
};

const openTaskDetail = (task) => {
  selectedTask.value = task;
  showTaskModal.value = true;
};

const updateTaskProgress = async (taskData) => {
  try {
    await axios.put(`/api/developer/tasks/${selectedTask.value.id}`, taskData);
    await loadDashboard();
    showTaskModal.value = false;
  } catch (error) {
    console.error('Error updating task:', error);
  }
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>
