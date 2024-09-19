<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
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
})

const Swal = inject('$swal')

const datas = reactive({
  list: [],
  isLoad: false,
  dateStart: '',
  dateEnd: '',
  messageList: '',
  metodoList: '',
})

onMounted(() => {
  datas.list = props.listado
  // addInputEventListeners()
  //   queryList('')
})

const query = ref('')

const recargarPagina = () => {
  query.value = ''
  queryList('')
}

const onSearchQuery = (e) => {
  console.log('valor del query')
  console.log(query.value.length)
  datas.dateStart = ''
  datas.dateEnd = ''
  queryList(e.target.value)
}

const onSearchDate = () => {
  console.log(datas.dateStart)
  console.log(datas.dateEnd)
  query.value = ''
  if (datas.dateStart.length > 0 && datas.dateEnd.length > 0) {
    const start = moment(datas.dateStart)
    const end = moment(datas.dateEnd)
    if (start.isBefore(end)) {
      console.log(datas.dateStart)
      console.log(datas.dateEnd)
      queryDateList(datas.dateStart, datas.dateEnd)
      //   queryDateList(start, end)
    } else {
      Swal.fire({
        title: 'Error!',
        text: 'Fecha no valida',
        icon: 'error',
      })
    }
  } else {
    Swal.fire({
      title: 'Error!',
      text: 'Se necesita registrar fechas',
      icon: 'error',
    })
    queryListSaltoTake('', 0, 20)
  }
}

const queryDateList = async (date_start, date_end) => {
  datas.isLoad = true
  const url = route('appingreso.queryDate', {
    start: date_start,
    end: date_end,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.isSuccess) {
        datas.list = response.data.data
        datas.messageList = response.data.message
        datas.metodoList =
          datas.list.length > 0
            ? ' con: Inicio' + date_start + ' | Fin' + date_end
            : ''
        console.log(datas.list.length)
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
  const url = route('ingreso.query', {
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

const queryList = async (consulta) => {
  datas.isLoad = true
  const url = route('ingreso.query', {
    query: consulta.toUpperCase(),
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
    })
  datas.isLoad = false
}

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM')
}

const destroyData = async (id) => {
  const url = route('ingreso.destroy', id)
  await axios
    .delete(url)
    .then((response) => {
      Swal.fire({
        title: response.data.isSuccess ? 'Buen Trabajo!' : 'Error!',
        text: response.data.isSuccess
          ? 'Dato creado exitosamente'
          : 'Algún error inesperado',
        icon: response.data.isSuccess ? 'success' : 'error',
      })
    })
    .catch((error) => {
      if (error.isMessageError) {
        console.log(error.message)
        Swal.fire({
          title: error.isMessageError
            ? 'Error desde el micro servicio!'
            : 'Algun otro error esta sucediendo!',
          text: error.isMessageError
            ? 'Algunos datos fueron mal registrados'
            : 'Algun otro tipo de error sucedio',
          icon: error.isMessageError ? 'error' : 'success',
        })
      }
    })
  queryList('')
}
</script>
<template>
  <div
    class="p-4 md:p-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700"
  >
    <!-- Header -->
    <HeaderIndex :title="'Ingresos'">
      <template #link>
        <a
          class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          :href="route('ingreso.create')"
        >
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
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
        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
          <div class="flex flex-col">
            <span class="text-sm font-bold text-gray-900 dark:text-neutral-400">
              Busqueda
            </span>
            <div class="relative">
              <input
                id="hs-table-with-pagination-search"
                type="text"
                v-model="query"
                @input="onSearchQuery"
                name="hs-table-with-pagination-search"
                class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                placeholder="Buscar"
              />
              <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3"
              >
                <svg
                  class="shrink-0 size-4"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.3-4.3"></path>
                </svg>
              </div>
            </div>
          </div>
          <div class="flex flex-col">
            <span class="text-sm font-bold text-gray-900 dark:text-neutral-400">
              Rango de fechas
            </span>
            <div class="sm:flex rounded-lg shadow-sm">
              <input
                type="datetime-local"
                v-model="datas.dateStart"
                placeholder="Inicio"
                class="py-2 px-2 pe-11 block w-full border-gray-200 sm:shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
              />
              <input
                type="datetime-local"
                v-model="datas.dateEnd"
                class="py-2 px-2 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
              />
              <button
                type="button"
                @click="onSearchDate"
                class="py-2 px-2 inline-flex items-center min-w-fit w-full border border-gray-200 bg-gray-50 text-sm text-gray-500 -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:w-auto sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg dark:bg-neutral-700 dark:border-neutral-700 dark:text-neutral-400"
              >
                <svg
                  class="shrink-0 size-4"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.3-4.3"></path>
                </svg>
              </button>
            </div>
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
          <div
            v-if="datas.list.length > 0"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700"
          >
            <!-- Table -->
            <TableGrl>
              <template #tbl-header>
                <tr>
                  <th scope="col" class="px-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                      >
                        ID
                      </span>
                    </div>
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-start text-xs font-semibold text-gray-800 uppercase dark:text-neutral-500"
                  >
                    <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                      >
                        RESIDENTE
                      </span>
                    </div>
                  </th>

                  <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                      >
                        VISITANTE
                      </span>
                    </div>
                  </th>
                </tr>
              </template>

              <template #tbl-body>
                <tr v-for="item in datas.list" :key="item.id">
                  <td class="h-px w-72 whitespace-nowrap">
                    <div class="px-3 py-1">
                      <div class="flex items-center gap-x-3">
                        <div
                          class="relative w-2 h-2 bg-blue-200 rounded-full flex justify-center items-center text-center p-5 shadow-xl"
                        >
                          <span
                            class="absolute text-3xl left-0 top-0 text-blue-800"
                          >
                            "
                          </span>
                          <span>{{ item.id }}</span>
                        </div>
                        <div class="grow">
                          <p class="py-1">
                            <span
                              v-if="item.vehiculo_id != null"
                              :class="
                                item.vehiculo_id != null
                                  ? 'text-teal-800 bg-teal-100 dark:text-teal-500'
                                  : 'text-red-800 bg-red-100 dark:text-red-500'
                              "
                              class="px-2 py-1 inline-flex items-center gap-x-1 text-xs font-medium rounded-full dark:bg-teal-500/10"
                            >
                              {{
                                item.vehiculo_id != null
                                  ? 'INGRESO CON VEHICULO'
                                  : 'INGRESO SIN VEHICULO'
                              }}
                              <i
                                :class="
                                  item.vehiculo_id != null
                                    ? 'fa-solid fa-car'
                                    : 'fa-solid fa-person-walking'
                                "
                              ></i>
                            </span>
                          </p>
                          <p class="py-1">
                            <span
                              :class="
                                item.isAutorizado
                                  ? 'text-teal-800 bg-teal-100 dark:text-teal-500'
                                  : 'text-red-800 bg-red-100 dark:text-red-500'
                              "
                              class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-full dark:bg-teal-500/10"
                            >
                              <svg
                                class="size-2.5"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                              >
                                <path
                                  d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                                />
                              </svg>
                              {{
                                item.isAutorizado
                                  ? 'INGRESO AUTORIZADO'
                                  : 'INGRESO NO AUTORIZADO'
                              }}
                            </span>
                          </p>
                          <span
                            class="block text-sm text-gray-900 dark:text-neutral-500"
                          >
                            INGRESO:
                            {{ item.created_at != null ? item.created_at : '' }}
                          </span>
                          <!-- <p
                            class="block text-sm text-gray-500 dark:text-neutral-500"
                          >
                            Detalle Registro:
                            {{ item.detalle != null ? item.detalle : '' }}
                          </p> -->
                          <span
                            class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                          >
                            {{
                              item.salida_created_at != null
                                ? 'SALIDA REGISTRADA: ' +
                                  fecha(item.salida_created_at)
                                : 'SIN REGISTRO DE SALIDA'
                            }}
                          </span>
                          <!-- <p
                            class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                          >
                            Detalle Salida:
                            {{
                              item.detalle_salida != null
                                ? item.detalle_salida
                                : ''
                            }}
                          </p> -->
                          <div class="py-1.5">
                            <a
                              class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                              :href="route('ingreso.edit', item.id)"
                            >
                              Editar
                              <i class="fa-solid fa-pencil"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-3">
                      <span
                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                      >
                        {{
                          item.name_residente == null
                            ? ''
                            : item.name_residente.toUpperCase()
                        }}
                      </span>
                      <span
                        v-if="item.nroDocumento_residente != null"
                        class="block text-sm text-gray-500 dark:text-neutral-500"
                      >
                        Nro Doc:
                        {{
                          item.nroDocumento_residente == null
                            ? ''
                            : item.nroDocumento_residente
                        }}
                      </span>
                      <span
                        class="block text-sm text-gray-500 dark:text-neutral-500"
                      >
                        Nro VIVIENDA:
                        {{ item.nroVivienda == null ? '' : item.nroVivienda }}
                      </span>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-3">
                      <span
                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                      >
                        {{
                          item.name_visitante == null
                            ? ''
                            : item.name_visitante.toUpperCase()
                        }}
                      </span>
                      <span
                        class="block text-sm text-gray-500 dark:text-neutral-500"
                      >
                        Nro Doc:
                        {{
                          item.nroDocumento_visitante == null
                            ? ''
                            : item.nroDocumento_visitante
                        }}
                      </span>
                    </div>
                  </td>
                </tr>
              </template>
            </TableGrl>
            <!-- END TABLE -->
          </div>
          <Alert v-else :message="datas.messageList"></Alert>
        </div>
      </div>
    </div>
  </div>
</template>
