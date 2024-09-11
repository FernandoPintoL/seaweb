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
  galeria: {
    type: Array,
    default: [],
  },
})

const Swal = inject('$swal')

onMounted(() => {
  /* console.log(props.directorio.length)
  console.log(props.listado.length) */
  datas.list = props.listado
})

const datas = reactive({
  url: '',
  list: [],
  isLoad: false,
  dateStart: '',
  dateEnd: '',
  messageList: '',
  metodoList: '',
})

const query = ref('')

const onSearchQuery = (e) => {
  queryList(e.target.value)
}

const descargarTodo = () => {
  props.galeria.forEach((element) =>
    openURL('/galeriavisitante/descargar/' + element.id),
  )
  /* for (let i = 0; i < props.galeria.length; i++) {
    openURL('/galeriavisitante/descargar/' + props.galeria[i].id)
  } */
  //   datas.url = encodeURIComponent('sea-production-2d37.up.railway.app')
  //   datas.url = 'sea-production-2d37.up.railway.app'
}

async function descargarRequest(path) {
  datas.isLoad = true
  const url = route('galeriavisitante.descargaRequest', {
    url: datas.url,
    path: path,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  datas.isLoad = false
}

/*const onSearchDate = () => {
  console.log(datas.dateStart)
  console.log(datas.dateEnd)
  if (datas.dateStart.length > 0 && datas.dateEnd.length > 0) {
    var start = fecha(datas.dateStart)
    var end = fecha(datas.dateEnd)
    console.log(start)
    console.log(end)
    queryDateList(start, end)
  } else {
    queryList('')
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
}*/

const queryList = async (consulta) => {
  datas.isLoad = true
  const url = route('appgaleriavisitante.query', {
    query: consulta.toUpperCase(),
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.isSuccess) {
        datas.list = []
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

const openURL = (url) => {
  window.open(url, '_blank')
}

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}

const destroyPhoto = (id) => {
  console.log(id)
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
      destroyPhotoModel(id)
    }
  })
}

const destroyPhotoModel = async (id) => {
  console.log(id)
  const url = route('appgaleriavisitante.destroyapp', id)
  await axios
    .delete(url, id)
    .then((response) => {
      console.log(response.data)
      Swal.fire({
        title: response.data.isSuccess ? 'Buen Trabajo!' : 'Error!',
        text: response.data.message,
        icon: response.data.isSuccess ? 'success' : 'error',
      })
      if (response.data.isSuccess) {
        queryList(props.model.id)
      }
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
}
</script>

<template>
  <AppLayout title="Galeria Visitantes">
    <div
      class="p-4 md:p-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700"
    >
      <!-- Header -->
      <HeaderIndex :title="'Galeria Visitantes'">
        <template #link></template>
      </HeaderIndex>
      <div
        class="px-3 py-2 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700"
      >
        <button
          class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          @click="descargarTodo"
        >
          DESCARGAR DB URL+DETALLE
        </button>
        <button
          class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          @click="descargarTodo"
        >
          DESCARGAR DB PUBLICPATH+DETALLE
        </button>
        <button
          class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          @click="descargarTodo"
        >
          DESCARGAR DB PUBLICPATH+DETALLE
        </button>
        <button
          class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          @click="descargarTodo"
        >
          DESCARGAR DB PUBLICPATH+DETALLE
        </button>
      </div>
      <!-- End Header -->
      <!-- Search Table -->
      <FormSearch>
        <template #search-table>
          <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="w-full flex flex-col">
              <span
                class="text-sm font-bold text-gray-900 dark:text-neutral-400"
              >
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
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-6">
                <div v-for="item in datas.list" :key="item.id">
                  <img
                    class="h-auto max-w-full rounded-lg"
                    :src="item.photo_path"
                    :alt="item.detalle"
                  />
                  <p>ID: {{ item.id }}</p>
                  <p>ID VISITANTE: {{ item.visitante_id }}</p>
                  <p>Nombre: {{ item.name }}</p>
                  <p>Nro Documento: {{ item.nroDocumento }}</p>
                  <p class="text-xs">{{ item.detalle }}</p>
                  <div class="inline-flex rounded-lg shadow-sm">
                    <button
                      type="button"
                      @click="openURL(item.photo_path)"
                      class="py-1 px-2 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                    >
                      Ver completo
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    <button
                      type="button"
                      @click="destroyPhoto(item.id)"
                      class="py-1 px-2 bg-red-600 hover:bg-red-700 focus:bg-red-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                    >
                      Eliminar
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
              <!-- End Table -->
            </div>
            <Alert v-else :message="datas.messageList"></Alert>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
