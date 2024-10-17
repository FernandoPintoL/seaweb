<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListIngresos from '@/Pages/Ingreso/List.vue'

const props = defineProps({
    listado: {
        type: Array,
        default: [],
    },
    condominios: {
        type: Array,
        default: [],
    },
    crear: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    editar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    eliminar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
})

onMounted(() => {
    console.log("DESDE DASHBOARD")
    console.log(props.condominios)
    // addInputEventListeners()
    //   queryList('')
})
</script>

<template>
    <AppLayout title="Dashboard">
        <ListIngresos :listado="props.listado" :condominios="props.condominios" :crear="props.crear"
            :editar="props.editar" :eliminar="props.eliminar" />
    </AppLayout>
</template>
