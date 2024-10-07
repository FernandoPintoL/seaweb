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
})

const Swal = inject('$swal')

onMounted(() => {
  console.log(props.listado)
  datas.list = props.listado
})

const datas = reactive({
  list: [],
  isLoad: false,
  dateStart: '',
  dateEnd: '',
  messageList: '',
  metodoList: '',
})

const query = ref('')

const onSearchQuery = (e) => {
  console.log('valor del query')
  console.log(query.value.length)
  queryList(e.target.value)
}

const queryList = async (consulta) => {
  datas.isLoad = true
  const url = route('users.query', {
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
  const url = route('users.destroy', id)
  await axios
    .delete(url)
    .then((response) => {
      console.log(response)
      Swal.fire({
        title: response.data.isSuccess ? 'Buen Trabajo!' : 'Error!',
        text: response.message,
        icon: response.data.isSuccess ? 'success' : 'error',
      })
      if (response.data.isSuccess) {
        queryList('')
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
  <AppLayout title="Usuarios">
    <div
      class="p-4 md:p-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700"
    >
      <!-- Header -->
      <HeaderIndex :title="'Usuarios'">
        <template #link>
          <a
            v-if="$page.props.user.roles.includes('super-admin')"
            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
            :href="route('users.create')"
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
                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                        <span
                          class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                        >
                          NOMBRE
                        </span>
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                        <span
                          class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                        >
                          Email
                        </span>
                      </div>
                    </th>
                    <!-- <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                        <span
                          class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                        >
                          USERNICK
                        </span>
                      </div>
                    </th> -->
                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                        <span
                          class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                        >
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
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          #{{ item.id }}
                        </span>
                      </div>
                    </td>
                    <td class="h-px w-72 whitespace-nowrap">
                      <div class="px-6 py-3">
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          {{ item.name == null ? '' : item.name }}
                        </span>
                      </div>
                    </td>
                    <td class="h-px w-72 whitespace-nowrap">
                      <div class="px-6 py-3">
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          {{ item.email == null ? '' : item.email }}
                        </span>
                      </div>
                    </td>
                    <td class="h-px w-72 whitespace-nowrap">
                      <div class="px-6 py-3">
                        <Link
                            v-if="$page.props.user.roles.includes('super-admin')"
                          :href="route('users.edit', item.id)"
                          class="py-1 px-2 bg-blue-600 hover:bg-blue-700 focus:bg-red-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        >
                          Editar
                          <i class="fa-solid fa-pencil"></i>
                        </Link>
                        <button
                            v-if="$page.props.user.roles.includes('super-admin')"
                          type="button"
                          @click="destroyMessage(item.id)"
                          class="py-1 px-2 bg-red-600 hover:bg-red-700 focus:bg-red-700' inline-flex items-center gap-x-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 text-white shadow-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        >
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
