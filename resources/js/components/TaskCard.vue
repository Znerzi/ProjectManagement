<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between mb-3">
      <div class="flex-1">
        <h3 class="font-semibold text-gray-900 dark:text-white">{{ task.module_name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ task.description }}</p>
      </div>
      <span
        :class="getPriorityClass(task.priority)"
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
      >
        {{ task.priority }}
      </span>
    </div>

    <div class="space-y-3">
      <!-- Progress Bar -->
      <ProgressBar :percentage="task.progress" :show-status="true" label="Task Progress" />

      <!-- Status and Deadline -->
      <div class="flex items-center justify-between text-sm">
        <div class="flex items-center gap-2">
          <span
            :class="getStatusClass(task.status)"
            class="inline-block w-3 h-3 rounded-full"
          ></span>
          <span class="text-gray-600 dark:text-gray-400 capitalize">{{ task.status }}</span>
        </div>
        <span
          v-if="task.deadline"
          :class="isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500'"
          class="text-xs"
        >
          {{ formatDate(task.deadline) }}
        </span>
      </div>
    </div>

    <!-- Action Button -->
    <slot name="actions"></slot>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import ProgressBar from './ProgressBar.vue';

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
});

const getPriorityClass = (priority) => {
  const classes = {
    urgent: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
    high: 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200',
    medium: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
    low: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200'
  };
  return classes[priority] || classes.low;
};

const getStatusClass = (status) => {
  const classes = {
    completed: 'bg-green-500',
    ongoing: 'bg-blue-500',
    pending: 'bg-gray-400'
  };
  return classes[status] || 'bg-gray-400';
};

const isOverdue = computed(() => {
  if (!props.task.deadline || props.task.status === 'completed') {
    return false;
  }
  return new Date(props.task.deadline) < new Date();
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};
</script>
