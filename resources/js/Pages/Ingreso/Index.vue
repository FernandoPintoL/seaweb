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
</script>

<template>
    <AppLayout title="Ingresos Visitas">
        <List :listado="props.listado" :condominios="props.condominios" :crear="props.crear" :editar="props.editar"
            :eliminar="props.eliminar" />
    </AppLayout>
</template>
