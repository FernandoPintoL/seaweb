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
                Users
              </h2>
              <p class="text-sm text-gray-600 dark:text-neutral-400">
                Add users, edit and more.
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <a
                  class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                  href="#"
                >
                  View all
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
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700"
          >
            <thead class="bg-gray-50 dark:bg-neutral-800">
              <tr>
                <th scope="col" class="ps-6 py-3 text-start">
                  <label for="hs-at-with-checkboxes-main" class="flex">
                    <input
                      type="checkbox"
                      class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                      id="hs-at-with-checkboxes-main"
                    />
                    <span class="sr-only">Checkbox</span>
                  </label>
                </th>

                <th
                  scope="col"
                  class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start"
                >
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Name
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Position
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Status
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Portfolio
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span
                      class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                    >
                      Created
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-end"></th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-1" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-1"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1531927557220-a9e23c1e4794?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Christina Bersh
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          christina@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Director
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Human resources
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        1/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 25%;"
                          aria-valuenow="25"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      28 Dec, 12:12
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-2" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-2"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          David Harrison
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          david@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Seller
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Branding products
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500"
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
                          d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                        />
                      </svg>
                      Warning
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        3/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 78%;"
                          aria-valuenow="78"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      20 Dec, 09:27
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-3" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-3"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span
                        class="inline-flex items-center justify-center size-[38px] rounded-full bg-white border border-gray-300 dark:bg-neutral-800 dark:border-neutral-700"
                      >
                        <span
                          class="font-medium text-sm text-gray-800 leading-none dark:text-neutral-200"
                        >
                          A
                        </span>
                      </span>
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Anne Richard
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          anne@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Designer
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      IT department
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        5/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 100%;"
                          aria-valuenow="100"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      18 Dec, 15:20
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-4" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-4"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1541101767792-f9b2b1c4f127?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Samia Kartoon
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          samia@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Executive director
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Marketing
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        0/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 1%;"
                          aria-valuenow="1"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      18 Dec, 15:20
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-5" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-5"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span
                        class="inline-flex items-center justify-center size-[38px] rounded-full bg-white border border-gray-300 dark:bg-neutral-800 dark:border-neutral-700"
                      >
                        <span
                          class="font-medium text-sm text-gray-800 leading-none dark:text-neutral-200"
                        >
                          D
                        </span>
                      </span>
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          David Harrison
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          david@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Developer
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Mobile app
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500"
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
                          d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                        />
                      </svg>
                      Danger
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        3/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 78%;"
                          aria-valuenow="78"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      15 Dec, 14:41
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-6" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-6"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Brian Halligan
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          brian@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Accountant
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Finance
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        2/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 40%;"
                          aria-valuenow="40"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      11 Dec, 18:51
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-7" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-7"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1659482634023-2c4fda99ac0c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2.5&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Andy Clerk
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          andy@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Director
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Human resources
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        1/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 25%;"
                          aria-valuenow="25"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      28 Dec, 12:12
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-8" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-8"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1601935111741-ae98b2b230b0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Bart Simpson
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          Bart@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Seller
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Branding products
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500"
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
                          d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                        />
                      </svg>
                      Warning
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        3/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 78%;"
                          aria-valuenow="78"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      20 Dec, 09:27
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-9" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-9"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span
                        class="inline-flex items-center justify-center size-[38px] rounded-full bg-white border border-gray-300 dark:bg-neutral-800 dark:border-neutral-700"
                      >
                        <span
                          class="font-medium text-sm text-gray-800 leading-none dark:text-neutral-200"
                        >
                          C
                        </span>
                      </span>
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Camila Welters
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          cwelt@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Designer
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      IT department
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        5/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 100%;"
                          aria-valuenow="100"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      18 Dec, 15:20
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-10" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-10"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Oliver Schevich
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          oliver@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Seller
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Branding products
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500"
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
                          d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                        />
                      </svg>
                      Warning
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        3/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 78%;"
                          aria-valuenow="78"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      20 Dec, 09:27
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-11" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-11"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span
                        class="inline-flex items-center justify-center size-[38px] rounded-full bg-white border border-gray-300 dark:bg-neutral-800 dark:border-neutral-700"
                      >
                        <span
                          class="font-medium text-sm text-gray-800 leading-none dark:text-neutral-200"
                        >
                          I
                        </span>
                      </span>
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Inna Ivy
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          invy@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Designer
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      IT department
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        5/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 100%;"
                          aria-valuenow="100"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      18 Dec, 15:20
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 py-3">
                    <label for="hs-at-with-checkboxes-12" class="flex">
                      <input
                        type="checkbox"
                        class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                        id="hs-at-with-checkboxes-12"
                      />
                      <span class="sr-only">Checkbox</span>
                    </label>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img
                        class="inline-block size-[38px] rounded-full"
                        src="https://images.unsplash.com/photo-1670272505340-d906d8d77d03?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                        alt="Avatar"
                      />
                      <div class="grow">
                        <span
                          class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                        >
                          Jessica Williams
                        </span>
                        <span
                          class="block text-sm text-gray-500 dark:text-neutral-500"
                        >
                          myhairisred@site.com
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="h-px w-72 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                    >
                      Executive director
                    </span>
                    <span
                      class="block text-sm text-gray-500 dark:text-neutral-500"
                    >
                      Marketing
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span
                      class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500"
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
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <span class="text-xs text-gray-500 dark:text-neutral-500">
                        0/5
                      </span>
                      <div
                        class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                      >
                        <div
                          class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-neutral-200"
                          role="progressbar"
                          style="width: 1%;"
                          aria-valuenow="1"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500 dark:text-neutral-500">
                      18 Dec, 15:20
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a
                      class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                      href="#"
                    >
                      Edit
                    </a>
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
                  12
                </span>
                results
              </p>
            </div>

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
    <!-- BUTTON BUSQUEDA -->
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
                Residente
              </th>
              <th scope="col" class="px-3 py-2">
                Visitante
              </th>
              <th scope="col" class="px-3 py-2">
                Detalle
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in datas.list"
              :key="item.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              <!-- ID'S Y DATOS DE INGRESOS -->
              <th
                scope="row"
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                <span>#{{ item.id }}</span>
                <br />
                <span
                  :class="item.isAutorizado ? 'text-green-600' : 'text-red-600'"
                >
                  {{ item.isAutorizado ? 'AUTORIZADO' : 'NO AUTORIZADO' }}
                </span>
                <br />
                <span
                  :class="
                    item.vehiculo_id != null
                      ? 'text-teal-600'
                      : 'text-slate-800'
                  "
                >
                  {{
                    item.vehiculo_id != null
                      ? 'INGRESO CON VEHICULO'
                      : 'INGRESO SIN VEHICULO'
                  }}
                </span>
                <br />
                <span>
                  Registrado:
                  {{ item.created_at != null ? item.created_at : '' }}
                </span>
                <br />
                <p>
                  <span class="mb-2">
                    {{
                      item.salida_created_at != null
                        ? 'Salida registrada: ' + item.salida_created_at
                        : 'SIN REGISTRO DE SALIDA'
                    }}
                  </span>
                </p>
                <br />
                <p>
                  <a
                    :href="route('ingreso.edit', item.id)"
                    class="mt-2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                  >
                    Editar
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                </p>
              </th>
              <!-- RESIDENTE -->
              <td
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                <span>{{ item.name_residente.toUpperCase() }}</span>
                <br />
                <span class="text-teal-600">
                  Nro Doc: {{ item.nroDocumento_residente }}
                </span>
              </td>
              <!-- VISITANTE -->
              <td
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                <span>{{ item.name_visitante.toUpperCase() }}</span>
                <br />
                <span class="text-teal-600">
                  Nro Doc: {{ item.nroDocumento_visitante }}
                </span>
              </td>
              <!-- DETALLE -->
              <td
                class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                <p>
                  {{ item.detalle.toUpperCase() }}
                </p>
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
