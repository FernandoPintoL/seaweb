<script setup>
import { onMounted, ref } from 'vue'
import InputError from '@/Components/InputError.vue'

defineProps({
  modelValue: String,
  typeInput: String,
})

defineEmits(['update:modelValue'])

const input = ref(null)
const error = ref('')

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <label><slot name="label" /></label>
  <input
    type="{{ props.typeInput.value }}"
    :value="modelValue"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    @input="$emit('update:modelValue', $event.target.value)"
  />
  <InputError class="mt-2" :message="error" />
</template>
