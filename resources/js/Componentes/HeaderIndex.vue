<script setup>
import { onMounted, ref } from 'vue'
const props = defineProps({
  title: {
    type: String,
    default: '',
  },
})
</script>
<template>
  <div
    class="px-3 py-2 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700"
  >
    <div>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
        {{ props.title }}
      </h2>
      <p class="text-sm text-gray-600 dark:text-neutral-400">
        Agrega {{ props.title }}, edita y más.
      </p>
    </div>

    <div class="inline-flex gap-x-2">
      <slot name="link" />
    </div>
  </div>
</template>
