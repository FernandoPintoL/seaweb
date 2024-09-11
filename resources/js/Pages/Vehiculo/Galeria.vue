<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import Loader from '@/Componentes/Loader.vue'
import SectionBorder from '@/Components/SectionBorder.vue'
import imageCompression from 'browser-image-compression'

import moment from 'moment-timezone'
import Alert from '@/Componentes/Alerts.vue'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
  listado: {
    default: [],
    type: Array,
  },
})

onMounted(() => {
  console.log(props.listado)
  reactives.list = props.listado
  // queryList(props.model.id)
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  image: null,
})

const reactives = reactive({
  isLoad: false,
  list: [],
  query: '',
})

const photoPreview = ref(null)
const photoInput = ref(null)
const image = ref(null)
const originalSize = ref(0)
const compressedSize = ref(0)
const originalImageUrl = ref(null)
const compressedImageUrl = ref(null)

const changeLoad = (value) => {
  reactives.isLoad = value
}

const queryList = async (id) => {
  changeLoad(true)
  const url = route('appgaleriavehiculo.vehiculoid', {
    vehiculo_id: id,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.isSuccess) {
        reactives.list = response.data.data
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const cargarImagenes = async () => {
  try {
    if (image) {
      let formData = new FormData()
      formData.append('id', props.model.id)
      formData.append('file', image.value)
      const response = await axios.post(
        '/api/appgaleriavehiculo/uploadimage',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        },
      )
      Swal.fire({
        position: 'top-end',
        icon: response.data.isSuccess ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
      if (response.data.isSuccess) {
        image.value = null
        compressedImageUrl.value = null
        queryList(props.model.id)
      }
      console.log(response.data)
    }
  } catch (error) {
    console.error(error)
  }
}

const showImage = (url) => {
  window.open(url, '_blank')
}

const onFileChange = async (event) => {
  image.value = event.target.files[0]
  const file = event.target.files[0]
  if (file) {
    originalImageUrl.value = URL.createObjectURL(file)
    originalSize.value = (file.size / 1024).toFixed(2)

    try {
      const options = {
        maxSizeMB: 1,
        maxWidthOrHeight: 1024,
      }

      const compressedFile = await imageCompression(file, options)
      image.value = compressedFile

      compressedImageUrl.value = URL.createObjectURL(compressedFile)

      compressedSize.value = (compressedFile.size / 1024).toFixed(2)
    } catch (error) {
      console.error('Error al comprimir la imagen:', error)
    }
  }
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
  const url = route('galeriavehiculo.destroy', id)
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

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}
</script>

<template>
  <div v-if="reactives.isLoad" class="w-full p-6">
    <Loader />
  </div>
  <div v-else>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      GALERIA DE VISITANTE
    </h2>
    <hr />
    <div class="w-full pt-3 pb-3" v-if="compressedImageUrl">
      <img
        :src="compressedImageUrl"
        class="object-cover h-48 w-96 rounded-lg"
        alt="Compressed Image"
      />
      <p>Tamaño: {{ compressedSize }} KB</p>
    </div>
    <!-- <input class="w-full py-2" type="file" @change="onFileChange" /> -->
    <div v-show="image == null" class="flex items-center justify-center w-full">
      <label
        for="dropzone-file-visitante"
        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
      >
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
          <svg
            class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 20 16"
          >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
            />
          </svg>
          <!-- <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-semibold">Click para cargar imagen</span>
            or drag and drop
          </p> -->
          <p class="text-xs text-gray-500 dark:text-gray-400">
            SVG, PNG, JPG or GIF (MAX. 800x400px)
          </p>
        </div>
        <input
          id="dropzone-file-visitante"
          type="file"
          class="hidden"
          @change="onFileChange"
        />
      </label>
    </div>
    <div class="w-full pt-1" v-show="image != null">
      <button
        @click="cargarImagenes"
        class="mr-0 py-1 px-1 font-semibold text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 rounded inline-flex items-center"
      >
        Subir Imagen
        <i class="px-2 fa-solid fa-upload"></i>
      </button>
    </div>

    <SectionBorder />
    <div
      v-if="reactives.list.length > 0"
      class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
    >
      <div v-for="item in reactives.list" :key="item.id">
        <img
          class="object-cover object-center w-full h-40 max-w-full rounded-lg"
          :src="item.photo_path"
          alt="No Encontrado"
        />
        <span class="w-full text-sm px-1 font-bold">ID: {{ item.id }} /</span>
        <span class="w-full text-sm px-1">Creado: {{ item.created_at }}</span>
        <div class="inline-flex rounded-lg shadow-sm">
          <button
            type="button"
            @click="showImage(item.photo_path)"
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
    <!--
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
        <p>GALERIA VACIA...</p>
        <p v-if="reactives.query.length != 0">
          Consulta con:
          <span class="font-semibold text-blue-800 leading-tight">
            {{ reactives.query.toUpperCase() }}
          </span>
          no encontrada
        </p>
      </div>
    </div> -->
    <Alert v-else :message="''">
      <template #body>
        <div>
          <p>GALERIA VACIA...</p>
          <p v-if="reactives.query.length != 0">
            Consulta con:
            <span class="font-semibold text-blue-800 leading-tight">
              {{ reactives.query.toUpperCase() }}
            </span>
            no encontrada
          </p>
        </div>
      </template>
    </Alert>
  </div>
</template>
