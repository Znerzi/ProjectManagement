<template>
  <form @submit.prevent="submit" class="space-y-5">
    <div v-for="field in fields" :key="field.name" class="space-y-2">
      <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ field.label }}
        <span v-if="field.required" class="text-red-500">*</span>
      </label>

      <!-- Text Input -->
      <input
        v-if="field.type === 'text' || field.type === 'email' || field.type === 'password' || field.type === 'number'"
        :id="field.name"
        :type="field.type"
        :name="field.name"
        :placeholder="field.placeholder"
        :required="field.required"
        :value="formData[field.name] || ''"
        @input="updateField(field.name, $event.target.value)"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white outline-none transition"
      />

      <!-- Select -->
      <select
        v-else-if="field.type === 'select'"
        :id="field.name"
        :name="field.name"
        :required="field.required"
        :value="formData[field.name] || ''"
        @change="updateField(field.name, $event.target.value)"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white outline-none transition"
      >
        <option value="">Select {{ field.label }}</option>
        <option v-for="option in field.options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>

      <!-- Textarea -->
      <textarea
        v-else-if="field.type === 'textarea'"
        :id="field.name"
        :name="field.name"
        :placeholder="field.placeholder"
        :required="field.required"
        :rows="field.rows || 4"
        :value="formData[field.name] || ''"
        @input="updateField(field.name, $event.target.value)"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white outline-none transition"
      ></textarea>

      <!-- Checkbox -->
      <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2">
        <input
          type="checkbox"
          :name="field.name"
          :checked="formData[field.name] || false"
          @change="updateField(field.name, $event.target.checked)"
          class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500"
        />
        <span class="text-sm text-gray-600 dark:text-gray-400">{{ field.label }}</span>
      </label>

      <!-- Error Message -->
      <p v-if="errors[field.name]" class="text-sm text-red-500">
        {{ errors[field.name] }}
      </p>
    </div>

    <button
      type="submit"
      :disabled="loading"
      class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
    >
      <span v-if="!loading">{{ submitButtonText }}</span>
      <span v-else class="flex items-center justify-center">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loadingText }}
      </span>
    </button>
  </form>
</template>

<script setup>
import { defineProps, defineEmits, reactive, ref } from 'vue';

defineProps({
  fields: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  submitButtonText: {
    type: String,
    default: 'Submit'
  },
  loadingText: {
    type: String,
    default: 'Processing...'
  },
  initialValues: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['submit']);

const formData = reactive({ ...props.initialValues });
const errors = reactive({});

const updateField = (name, value) => {
  formData[name] = value;
  delete errors[name];
};

const submit = () => {
  emit('submit', formData);
};
</script>
