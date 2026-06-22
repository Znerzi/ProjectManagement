<template>
  <BaseModal :title="`Update: ${task.module_name}`" :is-open="isOpen" @close="$emit('close')">
    <form @submit.prevent="submit" class="space-y-5">
      <!-- Progress Slider -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Progress: <span class="text-indigo-600 font-semibold">{{ formData.progress }}%</span>
        </label>
        <input
          v-model.number="formData.progress"
          type="range"
          min="0"
          max="100"
          step="5"
          class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
        />
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
          <span>0%</span>
          <span>50%</span>
          <span>100%</span>
        </div>
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Status
        </label>
        <select
          id="status"
          v-model="formData.status"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white outline-none transition"
        >
          <option value="pending">Pending</option>
          <option value="ongoing">Ongoing</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <!-- Technical Details -->
      <div>
        <label for="details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Technical Details
        </label>
        <textarea
          id="details"
          v-model="formData.technical_details"
          rows="3"
          placeholder="Add any technical notes or implementation details..."
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white outline-none transition"
        ></textarea>
      </div>

      <!-- Progress Info -->
      <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
          </svg>
          <div class="text-sm text-blue-700 dark:text-blue-300">
            <p class="font-semibold">Progress Update</p>
            <p class="mt-1">Set the progress to 100% when the task is complete. Auto-status will update to "completed".</p>
          </div>
        </div>
      </div>
    </form>

    <template #footer>
      <button
        @click="$emit('close')"
        type="button"
        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"
      >
        Cancel
      </button>
      <button
        @click="submit"
        :disabled="loading"
        type="button"
        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white rounded-lg transition"
      >
        <span v-if="!loading">Update Progress</span>
        <span v-else class="flex items-center gap-2">
          <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Updating...
        </span>
      </button>
    </template>
  </BaseModal>
</template>

<script setup>
import { defineProps, defineEmits, ref, reactive, watch } from 'vue';
import BaseModal from './BaseModal.vue';

const props = defineProps({
  task: Object,
  isOpen: Boolean
});

const emit = defineEmits(['close', 'update']);

const loading = ref(false);
const formData = reactive({
  progress: 0,
  status: 'pending',
  technical_details: ''
});

watch(() => props.isOpen, (newVal) => {
  if (newVal && props.task) {
    formData.progress = props.task.progress || 0;
    formData.status = props.task.status || 'pending';
    formData.technical_details = props.task.technical_details || '';
  }
});

const submit = async () => {
  loading.value = true;
  try {
    emit('update', formData);
  } finally {
    loading.value = false;
  }
};
</script>
