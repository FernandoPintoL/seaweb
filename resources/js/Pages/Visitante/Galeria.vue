<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import InputLabel from '@/Components/InputLabel.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import SectionBorder from '@/Components/SectionBorder.vue'

import moment from 'moment'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
})

onMounted(() => {
  queryList(props.model.id)
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

const changeLoad = (value) => {
  reactives.isLoad = value
}

const sendModel = async () => {
  if (
    reactives.nameError.length != 0 ||
    reactives.celularError.length != 0 ||
    reactives.nroDocumentoError.length != 0
  ) {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Validaciones sin corregir',
      showConfirmButton: false,
      timer: 1500,
    })
    return
  }
  Swal.fire({
    title: 'Estas seguro de registrar esta información?',
    text: '',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, estoy seguro!',
  }).then((result) => {
    if (result.isConfirmed) {
      if (props.model != null) {
        updateInformation()
      } else {
        createInformacion()
      }
    }
  })
}

const createInformacion = async () => {
  console.log(form)
  const url = route('visitante.store', form)
  await axios
    .post(url)
    .then((response) => {
      Swal.fire({
        position: 'top-end',
        icon: response.data.success ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
      if (response.data.success) {
        form.reset()
      }
    })
    .catch((error) => {
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Verifique el formulario',
        showConfirmButton: false,
        timer: 1500,
      })
      if (error.response.data.messageError) {
        if (error.response.data.message.nroDocumento != null) {
          setErrorNroDocumento(error.response.data.message.nroDocumento)
        } else {
          setErrorNroDocumento('')
        }
      }
    })
}

const updateInformation = async () => {
  console.log(form)
  const url = route('visitante.updateWeb', form.id)
  await axios
    .put(url, form)
    .then((response) => {
      Swal.fire({
        position: 'top-end',
        icon: response.data.success ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
    })
    .catch((error) => {
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Verifique el formulario',
        showConfirmButton: false,
        timer: 1500,
      })
      if (error.response.data.messageError) {
        if (error.response.data.message.nroDocumento != null) {
          setErrorNroDocumento(error.response.data.message.nroDocumento)
        } else {
          setErrorNroDocumento('')
        }
      }
    })
}

const queryList = async (id) => {
  changeLoad(true)
  const url = route('galeriavisitante.getGaleriaVisitante', {
    visitante_id: id,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.success) {
        reactives.list = response.data.data
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0]

  if (!photo) return

  const reader = new FileReader()

  reader.onload = (e) => {
    photoPreview.value = e.target.result
  }

  reader.readAsDataURL(photo)
}

const cargarImagenes = async () => {
  try {
    console.log(image)
    console.log(image.value)
    if (image) {
      let formData = new FormData()
      formData.append('id', props.model.id)
      formData.append('file', image.value)
      // formData.append('imageValue', image.value)
      /*const url = route('galeriavisitante.uploadimage', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })*/
      const response = await axios.post(
        '/galeriavisitante/uploadimage',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        },
      )
      if (response.data.success) {
        queryList(props.model.id)
      }
      console.log(response.data)
    }
  } catch (error) {
    console.error(error)
  }
}

const onFileChange = (event) => {
  image.value = event.target.files[0]
}

const selectNewPhoto = () => {
  photoInput.value.click()

  console.log(photoInput)
  console.log(photoInput.value)
}

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}

const fecha = (fechaData) => {
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>

<template>
  <div v-if="reactives.isLoad" class="w-full p-6">
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
        <span class="sr-only">Cargando Formulario...</span>
        <div class="mb-1 text-base font-medium dark:text-white">
          Cargando Imagenes...
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="col-span-12 sm:col-span-12 my-6">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        GALERIA DE VISITANTE
      </h2>
      <!-- Profile Photo File Input -->
      <!-- <input
        id="photo"
        ref="photoInput"
        type="file"
        class="hidden border border-gray-300"
        @change="updatePhotoPreview"
      />
      <InputLabel for="photo" value="Foto" class="hidden" /> -->
      <!-- <div class="flex justify-between p-2">
        <SecondaryButton
          class="mt-2 me-2"
          type="button"
          @click.prevent="selectNewPhoto"
        >
          Selecciona una foto
        </SecondaryButton>
        <button
          v-show="photoPreview"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
          @click="cargarImagenes"
        >
          <span class="mr-2">Subir Image</span>
          <i class="fa-solid fa-cloud-arrow-up fa-fw" />
        </button>
      </div> -->
      <!-- New Profile Photo Preview -->
      <div v-show="photoPreview" class="my-6">
        <span
          class="block rounded-lg max-w-auto h-96 bg-contain bg-no-repeat bg-center border border-gray-300"
          :style="'background-image: url(\'' + photoPreview + '\');'"
        />
      </div>
    </div>
    <div>
      <input type="file" @change="onFileChange" />
      <button
        @click="cargarImagenes"
        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
      >
        Subir Imagen
      </button>
    </div>
    <SectionBorder />
    <div v-if="reactives.list.length > 0" class="grid grid-cols-2 gap-2">
      <div v-for="item in reactives.list" :key="item.id">
        <img
          class="h-auto max-w-full rounded-lg"
          :src="item.photo_path"
          alt="No Encontrado"
        />
      </div>
    </div>

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
        <p v-if="reactives.query.length != 0">
          Consulta con:
          <span class="font-semibold text-blue-800 leading-tight">
            {{ reactives.query.toUpperCase() }}
          </span>
          no encontrada
        </p>
      </div>
    </div>
  </div>
</template>
