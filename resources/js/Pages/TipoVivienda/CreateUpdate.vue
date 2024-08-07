<script setup>
import { ref, inject, reactive } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import moment from 'moment'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  sigla: props.model != null ? props.model.sigla : '',
  detalle: props.model != null ? props.model.detalle : '',
})

const messages = reactive({
  eSigla: [],
  eDetalle: [],
})

const reactives = reactive({
  isLoad: false,
  siglaError: '',
  detalleError: '',
})

const changeLoad = (value) => {
  reactives.isLoad = value
}

const sendModel = async () => {
  if (reactives.siglaError.length != 0 || reactives.detalleError.length != 0) {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Validaciones sin corregir',
      text:
        'Sigla: ' +
        reactives.siglaError +
        '\nDetalle: ' +
        reactives.detalleError,
      showConfirmButton: false,
      timer: 1500,
    })
    return
  }
  changeLoad(true)
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
  changeLoad(false)
}

const onValidateSigla = (e) => {
  if (!/^[A-Za-z\s]{2,}$/.test(e.target.value)) {
    reactives.siglaError =
      'El campo debe tener al menos 2 caracteres y solo pueden ser letras.'
  } else {
    reactives.siglaError = ''
  }
}

const onValidateDetalle = (e) => {
  if (!/^[A-Za-z\s]{0,}$/.test(e.target.value)) {
    reactives.detalleError = 'El campo solo pueden ser letras.'
  } else {
    reactives.detalleError = ''
  }
}

const setErrorSigla = (value) => {
  console.log(value[0])
  reactives.siglaError = value[0]
}

const createInformacion = async () => {
  const url = route('tipovivienda.store', form)
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
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
      /*messages.eDetalle = []
      messages.eSigla = []*/
    })
    .catch((error) => {
      console.log(error.response)
      if (error.response.data.messageError) {
        console.log(error.response.data.message)
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Verifique el formulario',
          showConfirmButton: false,
          timer: 1500,
        })
        if (error.response.data.messageError) {
          if (error.response.data.message.sigla != null) {
            setErrorSigla(error.response.data.message.sigla)
          } else {
            setErrorSigla('')
          }
        }
      }
      /*messages.eSigla =
        error.response.data.message.sigla != null
          ? error.response.data.message.sigla
          : []
      messages.eDetalle =
        error.response.data.message.detalle != null
          ? error.response.data.message.detalle
          : []*/
    })
}

const updateInformation = async () => {
  const url = route('tipovivienda.update', props.model.id)
  await axios
    .put(url, form)
    .then((response) => {
      console.log(response.data)
      Swal.fire({
        position: 'top-end',
        icon: response.data.success ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
      /*messages.eDetalle = []
      messages.eSigla = []*/
    })
    .catch((error) => {
      console.log(error.response)
      if (error.response.data.messageError) {
        console.log(error.response.data.message)
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Verifique el formulario',
          showConfirmButton: false,
          timer: 1500,
        })
        if (error.response.data.messageError) {
          if (error.response.data.message.sigla != null) {
            setErrorSigla(error.response.data.message.sigla)
          } else {
            setErrorSigla('')
          }
        }
      }
    })
}

const fecha = (fechaData) => {
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>

<template>
  <AppLayout title="Tipo Vivienda">
    <template #header>
      <h2
        v-if="props.model != null"
        class="font-semibold text-xl text-gray-800 leading-tight"
      >
        ACTUALIZAR TIPO VIVIENDA {{ props.model.sigla }}
      </h2>
      <h2 v-else class="font-semibold text-xl text-gray-800 leading-tight">
        CREAR TIPO VIVIENDA
      </h2>
    </template>
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
            Cargando Formulario...
          </div>
        </div>
      </div>
    </div>
    <div v-else class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-8">
      <FormSection @submitted="sendModel">
        <template #title>
          <p v-if="props.model != null">
            Tipo Vivienda COD #{{ props.model.id }}
          </p>
          <p v-else>Registrar Tipo Vivienda</p>
        </template>

        <template #description>
          <p v-if="props.model != null">
            <span class="font-semibold text-gray-800 leading-tight">
              Creado:
            </span>
            {{ fecha(props.model.created_at) }}
          </p>
          <p v-if="props.model != null">
            <span class="font-semibold text-gray-800 leading-tight">
              Actualizado:
            </span>
            {{ fecha(props.model.updated_at) }}
          </p>
          <p>
            Complete correctamente los datos personales
          </p>
        </template>

        <template #form>
          <!-- Sigla -->
          <div class="col-span-12 sm:col-span-12">
            <InputLabel for="sigla" value="Sigla" />
            <TextInput
              id="sigla"
              v-model="form.sigla"
              type="text"
              class="mt-1 block w-full"
              required
              autocomplete="sigla"
              @input="onValidateSigla"
            />
            <InputError
              class="mt-2"
              :message="reactives.siglaError.toUpperCase()"
            />
            <div v-if="messages.eSigla.length > 0">
              <div v-for="(msg, index) in messages.eSigla" :key="index">
                <InputError :message="msg.toUpperCase()" class="mt-2" />
              </div>
            </div>
          </div>
          <!-- Detalle -->
          <div class="col-span-12 sm:col-span-12">
            <InputLabel for="detalle" value="Detalle" />
            <TextInput
              id="detalle"
              v-model="form.detalle"
              type="text"
              class="mt-1 block w-full"
              required
              autocomplete="detalle"
            />
            <InputError
              class="mt-2"
              :message="reactives.detalleError.toUpperCase()"
            />
            <div v-if="messages.eDetalle.length > 0">
              <div v-for="(msg, index) in messages.eDetalle" :key="index">
                <InputError :message="msg.toUpperCase()" class="mt-2" />
              </div>
            </div>
          </div>
        </template>

        <template #actions>
          <ActionMessage :on="form.recentlySuccessful" class="me-3">
            Saved.
          </ActionMessage>

          <PrimaryButton
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Guardar
          </PrimaryButton>
        </template>
      </FormSection>

      <!-- <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        @click="showAlert"
      >
        Alert
      </PrimaryButton> -->
    </div>
  </AppLayout>
</template>
