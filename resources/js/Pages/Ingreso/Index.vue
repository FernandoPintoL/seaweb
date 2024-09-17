<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import List from './List.vue'

const props = defineProps({
  listado: {
    type: Array,
    default: [],
  },
})
</script>

<template>
  <AppLayout title="Ingresos Visitas">
    <List :listado="props.listado" />
  </AppLayout>
</template>
