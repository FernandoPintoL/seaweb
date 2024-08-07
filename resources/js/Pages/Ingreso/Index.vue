<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import List from './List.vue'
import SectionBorder from '@/Components/SectionBorder.vue'
// import { initFlowbite } from 'flowbite'

const props = defineProps({
  tipodocumentos: {
    type: Array,
    default: [],
  },
})
</script>

<template>
  <AppLayout title="Ingresos Visitas">
    <template #header>
      <div
        class="flex justify-between p-2 text-gray-900 rounded-lg dark:text-white group"
      >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          GESTION INGRESOS VISITAS / INGRESOS
        </h2>
        <Link
          :href="route('ingreso.create')"
          class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800"
        >
          Nuevo Ingreso o Visita
          <i class="fas fa-id-card"></i>
        </Link>
      </div>
    </template>
    <div class="w-full">
      <List />
    </div>
  </AppLayout>
</template>
