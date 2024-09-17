<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListIngresos from '@/Pages/Ingreso/List.vue'

const props = defineProps({
  listado: {
    type: Array,
    default: [],
  },
})
</script>

<template>
  <AppLayout title="Dashboard">
    <ListIngresos :listado="props.listado" />
  </AppLayout>
</template>
