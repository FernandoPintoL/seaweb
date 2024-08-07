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
  addInputEventListeners()
  queryList('')
})

const addInputEventListeners = () => {
  const inputs = document.querySelectorAll('.dt-container thead input')

  inputs.forEach((input) => {
    const handleKeyDown = (evt) => {
      if ((evt.metaKey || evt.ctrlKey) && evt.key === 'a') input.select()
    }

    input.addEventListener('keydown', handleKeyDown)

    // Asegurarse de eliminar los event listeners cuando se desmonte el componente
    input._cleanup = () => {
      input.removeEventListener('keydown', handleKeyDown)
    }
  })
}

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
  const url = route('ingreso.query', { query: consulta })
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
  const url = route('ingreso.destroy', id)
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
  <div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div
          class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700"
        >
          <!-- Header -->
          <div
            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700"
          >
            <div>
              <h2
                class="text-xl font-semibold text-gray-800 dark:text-neutral-200"
              >
                INGRESOS AL CONDOMINIO
              </h2>
              <p class="text-sm text-gray-600 dark:text-neutral-400">
                Agrega ingresos, edita alguno.
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <a
                  class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                  :href="route('ingreso.index')"
                >
                  Ver pagina principal
                </a>

                <a
                  class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                  href="#"
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
                  Add user
                </a>
              </div>
            </div>
          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-neutral-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-start">
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
                      Residente
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Visitante
                    </span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
              <tr v-for="item in datas.list" :key="item.id">
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <p>
                        <span>#{{ item.id }}</span>
                      </p>
                      <div class="grow">
                        <div>
                          <span
                            :class="
                              item.isAutorizado
                                ? 'text-teal-800 bg-teal-100 dark:text-teal-500'
                                : 'text-red-800 bg-red-100 dark:text-red-500'
                            "
                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full dark:bg-teal-500/10"
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
                              item.isAutorizado ? 'AUTORIZADO' : 'NO AUTORIZADO'
                            }}
                          </span>
                        </div>
                        <p>
                          <span
                            :class="
                              item.isAutorizado
                                ? 'text-teal-800 bg-teal-100 dark:text-teal-500'
                                : 'text-red-800 bg-red-100 dark:text-red-500'
                            "
                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full dark:bg-teal-500/10"
                          >
                            {{
                              item.vehiculo_id != null
                                ? 'INGRESO CON VEHICULO'
                                : 'INGRESO SIN VEHICULO'
                            }}
                          </span>
                        </p>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          Registrado:
                          {{ item.created_at != null ? item.created_at : '' }}
                        </span>
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          {{
                            item.salida_created_at != null
                              ? 'Salida registrada: ' + item.salida_created_at
                              : 'SIN REGISTRO DE SALIDA'
                          }}
                        </span>
                        <div class="px-6 py-1.5">
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
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      {{ item.name_residente.toUpperCase() }}
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Nro Doc: {{ item.nroDocumento_residente }}
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      {{ item.name_visitante.toUpperCase() }}
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Nro Doc: {{ item.nroDocumento_visitante }}
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- End Table -->

          <!-- Footer -->
          <div
            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700"
          >
            <div>
              <p class="text-sm text-gray-600 dark:text-neutral-400">
                <span class="font-semibold text-gray-800 dark:text-neutral-200">
                  {{ datas.list.length }}
                </span>
                results
              </p>
            </div>
            <!-- NEXT -->
            <div>
              <div class="inline-flex gap-x-2">
                <button
                  type="button"
                  class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                    <path d="m15 18-6-6 6-6" />
                  </svg>
                  Prev
                </button>

                <button
                  type="button"
                  class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                >
                  Next
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
                    <path d="m9 18 6-6-6-6" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <!-- End Footer -->
        </div>
      </div>
    </div>
  </div>
</template>
