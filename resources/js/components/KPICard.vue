<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ value }}</p>
      </div>
      <div :class="getIconClass()" class="p-3 rounded-lg">
        <component :is="getIcon()" class="w-6 h-6" />
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  color: {
    type: String,
    default: 'blue'
  }
});

const getIconClass = () => {
  const colors = {
    blue: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600',
    green: 'bg-green-100 dark:bg-green-900/30 text-green-600',
    yellow: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600',
    purple: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600'
  };
  return colors[props.color] || colors.blue;
};

const getIcon = () => {
  const icons = {
    briefcase: 'IconBriefcase',
    'check-circle': 'IconCheckCircle',
    clock: 'IconClock',
    star: 'IconStar'
  };
  return icons[props.icon] || 'div';
};
</script>

<script>
const IconBriefcase = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 10v10l8 4m0-4v4"></path></svg>`
};

const IconCheckCircle = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
};

const IconClock = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
};

const IconStar = {
  template: `<svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>`
};

export default {
  components: { IconBriefcase, IconCheckCircle, IconClock, IconStar }
};
</script>
