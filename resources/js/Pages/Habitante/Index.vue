<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import HeaderIndex from '@/Componentes/HeaderIndex.vue'
import Loader from '@/Componentes/Loader.vue'
import TableGrl from '@/Componentes/Tbl-General.vue'
import moment from 'moment-timezone'
import FormSearch from '@/Componentes/FormSearch.vue'
import Alert from '@/Componentes/Alerts.vue'
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

const Swal = inject('$swal')

onMounted(() => {
    datas.list = props.listado
    if (props.condominios.length > 0) {
        datas.condominio_id = props.condominios[0].id;
    }
})

const datas = reactive({
    list: [],
    isLoad: false,
    dateStart: '',
    dateEnd: '',
    messageList: '',
    metodoList: '',
    condominio_id: 0,
})

const query = ref('')

const onSearchQuery = (e) => {
    queryList(e.target.value)
}

const queryList = async (consulta) => {
    if (consulta.length == 0) {
        datas.skip = 0
        datas.take = 10
    } else {
        datas.skip = null
        datas.take = null
    }
    datas.isLoad = true
    const url = route('habitante.query', {
        query: consulta.toUpperCase(),
        condominio_id: datas.condominio_id,
        skip: datas.skip,
        take: datas.take
    })
    await axios
        .post(url)
        .then((response) => {
            console.log(response)
            if (response.data.isSuccess) {
                datas.list = response.data.data
                datas.messageList = response.data.message
                datas.metodoList = consulta.length > 0 ? ' con: ' + consulta : ''
            } else {
                datas.list = []
            }
        })
        .catch((error) => {
            console.log('respuesta error')
            console.log(error)
        })
    datas.isLoad = false
}

const queryListSaltoTake = async (consulta, skip, take) => {
    datas.isLoad = true
    const url = route('habitante.query', {
        query: consulta.toUpperCase(),
        skip: skip,
        take: take,
    })
    await axios
        .post(url)
        .then((response) => {
            console.log(response.data)
            if (response.data.isSuccess) {
                datas.list = response.data.data
                datas.messageList = response.data.message
                datas.metodoList = consulta.length > 0 ? ' con: ' + consulta : ''
                console.log(datas.list.length)
            } else {
                datas.list = []
            }
        })
        .catch((error) => {
            console.log('respuesta error')
            console.log(error)
            Swal.fire({
                title: 'Algun otro error esta sucediendo!',
                text: error,
                icon: 'error',
            })
        })
    datas.isLoad = false
}

const fecha = (fechaData) => {
    return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}

const destroyMessage = (id) => {
    Swal.fire({
        title: 'Estas seguro de eliminar esta información?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, estoy seguro!',
    }).then((result) => {
        if (result.isConfirmed) {
            datas.isLoad = true
            destroyData(id)
            datas.isLoad = false
        }
    })
}

const destroyData = async (id) => {
    const url = route('habitante.destroy', id)
    await axios
        .delete(url)
        .then((response) => {
            console.log(response)
            Swal.fire({
                title: response.data.isSuccess ? 'Buen Trabajo!' : 'Error!',
                text:
                    response.data.statusCode == 23503
                        ? 'ESTE DATO ESTA SIENDO USADO EN OTRAS TABLAS'
                        : response.data.message,
                icon: response.data.isSuccess ? 'success' : 'error',
            })
            if (response.data.isSuccess) {
                queryListSaltoTake('', 0, 20)
            }
        })
        .catch((error) => {
            if (error.response.data.isMessageError) {
                console.log(error.message)
                Swal.fire({
                    title: error.response.data.isMessageError
                        ? 'Error desde el micro servicio!'
                        : 'Algun otro error esta sucediendo!',
                    text: error.response.data.isMessageError
                        ? 'Algunos datos fueron mal registrados'
                        : 'Algun otro tipo de error sucedio',
                    icon: 'error',
                })
            }
        })
}
</script>

<template>
    <AppLayout title="Residentes">
        <div
            class="p-4 md:p-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <!-- Header -->
            <HeaderIndex :title="'Residentes'">
                <template #link>
                    <a v-if="props.crear"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        :href="route('habitante.create')">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Nuevo
                    </a>
                </template>
            </HeaderIndex>
            <!-- End Header -->
            <!-- Search Table -->
            <FormSearch>
                <template #search-table>
                    <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
                        <div class="w-full flex flex-col">
                            <span class="text-sm font-bold text-gray-900 dark:text-neutral-400">
                                Busqueda
                            </span>
                            <div class="relative">
                                <input id="hs-table-with-pagination-search" type="text" v-model="query"
                                    @input="onSearchQuery" name="hs-table-with-pagination-search"
                                    class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Buscar" />
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900 dark:text-neutral-400">Seleccione un
                                condominio</span>
                            <select id=" condominios-ids" v-model="datas.condominio_id"
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                <option v-for="item in props.condominios" :key="item.id" :value="item.id">
                                    COD: {{ item.id }} / NOMBRE: {{ item.propietario }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-2 dark:bg-neutral-800 dark:border-neutral-700">
                        <span>{{ datas.messageList }} {{ datas.metodoList }}</span>
                    </div>
                </template>
            </FormSearch>
            <!-- End Search table -->
        </div>

        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div v-if="datas.isLoad">
                        <Loader />
                    </div>
                    <div v-else>
                        <div v-if="datas.list.length > 0"
                            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                            <!-- Table -->
                            <TableGrl>
                                <template #tbl-header>
                                    <tr>
                                        <th scope="col" class="px-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    ID
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Nombre
                                                </span>
                                            </div>
                                        </th>
                                        <th v-if="props.editar || props.eliminar" scope="col"
                                            class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    _
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                </template>
                                <template #tbl-body>
                                    <tr v-for="item in datas.list" :key="item.id">
                                        <td class="h-px w-72 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                                    #{{ item.id }}
                                                </span>
                                                <span
                                                    class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                                    NroDocumento:
                                                    {{
                                                        item.nroDocumento == null ? '' : item.nroDocumento
                                                    }}
                                                </span>
                                                <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                    Nro Vivienda:
                                                    {{ item.nroVivienda == null ? '' : item.nroVivienda }}
                                                </span>
                                                <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                    Condominio:
                                                    {{ item.razonSocial == null ? '' : item.razonSocial }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-72 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                    {{ item.name == null ? '' : item.name }}
                                                </span>
                                                <span :class="item.isDuenho
                                                    ? 'text-teal-800 bg-teal-100 dark:text-teal-500'
                                                    : 'text-orange-800 bg-red-100 dark:text-orange-500'
                                                    "
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full dark:bg-teal-500/10">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    {{ item.isDuenho ? 'DUEÑO' : 'NO ES DUEÑO' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td v-if="props.editar || props.eliminar" class="h-px w-72 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <Link v-if="props.editar" :href="route('habitante.edit', item.id)"
                                                    class="py-1 px-2 bg-blue-600 hover:bg-blue-700 focus:bg-red-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                Editar
                                                <i class="fa-solid fa-pencil"></i>
                                                </Link>
                                                <button type="button" v-if="props.eliminar"
                                                    @click="destroyMessage(item.id)"
                                                    class="py-1 px-2 bg-red-600 hover:bg-red-700 focus:bg-red-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                    Eliminar
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </TableGrl>
                            <!-- End Table -->
                        </div>
                        <Alert v-else :message="datas.messageList"></Alert>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
