<script setup>
import { ref, onMounted, inject, reactive } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import FormSection from '@/Components/FormSection.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import moment from 'moment'

/*const props = defineProps({
  tipodocumentos: {
    type: Array,
    default: [],
  },
})*/

const Swal = inject('$swal')

const datas = reactive({
  list: [],
  isLoad: false,
})
onMounted(() => {
  queryList('')
})

const query = ref('')

const recargarPagina = () => {
  query.value = ''
  queryList('')
}

const onSearchQuery = (e) => {
  console.log('valor del query')
  console.log(query.value.length)
  queryList(e.target.value)
}

const queryList = async (consulta) => {
  datas.isLoad = true
  const url = route('tipodocumento.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.success) {
        datas.list = response.data.data
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

const destroyData = async (id) => {
  const url = route('tipodocumento.destroy', id)
  await axios
    .delete(url)
    .then((response) => {
      Swal.fire({
        title: response.data.success ? 'Buen Trabajo!' : 'Error!',
        text: response.data.success
          ? 'Dato creado exitosamente'
          : 'Algún error inesperado',
        icon: response.data.success ? 'success' : 'error',
      })
    })
    .catch((error) => {
      if (error.messageError) {
        console.log(error.message)
        Swal.fire({
          title: error.messageError
            ? 'Error desde el micro servicio!'
            : 'Algun otro error esta sucediendo!',
          text: error.messageError
            ? 'Algunos datos fueron mal registrados'
            : 'Algun otro tipo de error sucedio',
          icon: error.messageError ? 'error' : 'success',
        })
      }
    })
  queryList('')
}

const fecha = (fechaData) => {
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>
<template>
  <div
    class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 bg-white dark:bg-gray-900"
  >
    <div>
      <button
        id="recargarTipoDocumentos"
        class="inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button"
        @click="recargarPagina"
      >
        <i class="fas fa-sync-alt mr-3"></i>
        Recargar Pagina
      </button>
      <!-- Dropdown menu -->
    </div>
    <label for="table-search" class="sr-only">Buscar</label>
    <div class="relative">
      <div
        class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none"
      >
        <svg
          class="w-4 h-4 text-gray-500 dark:text-gray-400"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 20 20"
        >
          <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
          />
        </svg>
      </div>
      <input
        v-model="query"
        type="text"
        id="table-search-users"
        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Buscar"
        @input="onSearchQuery"
      />
    </div>
  </div>
  <div v-if="datas.isLoad" class="w-full p-6">
    <div class="text-center">
      <div role="status">
        <svg
          aria-hidden="true"
          class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
          viewBox="0 0 100 101"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
            fill="currentColor"
          />
          <path
            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
            fill="currentFill"
          />
        </svg>
        <span class="sr-only">Cargando...</span>
        <div class="mb-1 text-base font-medium dark:text-white">
          Cargando Lista...
        </div>
      </div>
    </div>
  </div>
  <div v-else class="w-full mt-6">
    <div v-if="datas.list.length > 0" class="w-full">
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table
          class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        >
          <thead
            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
          >
            <tr>
              <th scope="col" class="px-3 py-2">
                ID
              </th>
              <th scope="col" class="px-3 py-2">
                Sigla
              </th>
              <th scope="col" class="px-3 py-2">
                Detalle
              </th>
              <th scope="col" class="px-3 py-2">
                Creado
              </th>
              <th scope="col" class="px-3 py-2">
                Accion
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in datas.list"
              :key="item.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              <th
                scope="row"
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                {{ item.id }}
              </th>
              <td
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                {{ item.sigla }}
              </td>
              <td
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                {{ item.detalle }}
              </td>
              <td class="px-3 py-3">
                {{ item.created_at != null ? fecha(item.created_at) : '' }}
              </td>
              <!-- <td class="px-6 py-4">
                {{
                  tipodocumento.updated_at != null
                    ? fecha(tipodocumento.updated_at)
                    : ''
                }}
              </td> -->
              <td class="px-3 py-3">
                <Link
                  :href="route('tipodocumento.edit', item.id)"
                  class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >
                  Editar
                  <i class="fa-solid fa-pencil"></i>
                </Link>
                <!-- <button
                  type="submit"
                  class="font-medium text-red-600 dark:text-red-500 hover:underline"
                >
                  Delete
                </button> -->
                <!-- <input type="hidden" name="_method" value="DELETE" /> -->
                <!-- <form @submit.prevent="destroyData(tipodocumento.id)">
                  <button
                    type="submit"
                    class="font-medium text-red-600 dark:text-red-500 hover:underline"
                  >
                    Delete
                  </button>
                </form> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- CUANDO LA LISTA ESTA VACIA -->
    <div
      v-else
      class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
      role="alert"
    >
      <svg
        class="flex-shrink-0 inline w-4 h-4 me-3"
        aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg"
        fill="currentColor"
        viewBox="0 0 20 20"
      >
        <path
          d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
        />
      </svg>
      <span class="sr-only">Info</span>
      <div>
        <span class="font-medium">INFORMACION!</span>
        <p>LISTADO VACIA...</p>
        <p v-if="query.length != 0">
          Consulta con:
          <span class="font-semibold text-blue-800 leading-tight">
            {{ query.toUpperCase() }}
          </span>
          no encontrada
        </p>
      </div>
    </div>
  </div>
</template>
