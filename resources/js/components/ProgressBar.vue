<template>
  <div class="space-y-2">
    <div v-if="label" class="flex justify-between items-center">
      <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</span>
      <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ percentage }}%</span>
    </div>
    <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
      <div
        :style="{ width: `${percentage}%` }"
        :class="getColorClass()"
        class="h-full rounded-full transition-all duration-300 ease-out"
      ></div>
    </div>
    <p v-if="showStatus" class="text-xs text-gray-500 dark:text-gray-400">
      {{ statusText }}
    </p>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  percentage: {
    type: Number,
    required: true,
    validator: (value) => value >= 0 && value <= 100
  },
  label: {
    type: String,
    default: ''
  },
  showStatus: {
    type: Boolean,
    default: false
  },
  status: {
    type: String,
    default: 'normal' // 'normal', 'success', 'warning', 'danger'
  }
});

const getColorClass = () => {
  if (props.status === 'success' || props.percentage === 100) {
    return 'bg-green-500';
  } else if (props.status === 'warning' || props.percentage >= 75) {
    return 'bg-yellow-500';
  } else if (props.status === 'danger' || props.percentage >= 50) {
    return 'bg-orange-500';
  }
  return 'bg-indigo-600';
};

const statusText = computed(() => {
  if (props.percentage === 0) return 'Not started';
  if (props.percentage < 25) return 'Just started';
  if (props.percentage < 50) return 'In progress';
  if (props.percentage < 75) return 'Half way done';
  if (props.percentage < 100) return 'Almost done';
  return 'Completed';
});
</script>
