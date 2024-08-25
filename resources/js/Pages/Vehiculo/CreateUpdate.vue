<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Loader from '@/Componentes/Loader.vue'
import Galeria from './Galeria.vue'
import moment from 'moment-timezone'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
  listado: {
    default: [],
    type: Array,
  },
})

onMounted(() => {
  reactives.listGaleria = props.listado
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  placa: props.model != null ? props.model.placa : '',
  detalle: props.model != null ? props.model.detalle : '',
  tipo_vehiculo: props.model != null ? props.model.tipo_vehiculo : 'automóvil',
})

const messages = reactive({
  eSigla: [],
  eDetalle: [],
})

const reactives = reactive({
  isLoad: false,
  placaError: '',
  detalleError: '',
  labelShowGaleria: 'Administrar Galeria',
  showGaleria: false,
  listGaleria: [],
})

const queryListGaleria = async (id) => {
  changeLoad(true)
  const url = route('appgaleriavehiculo.vehiculoid', {
    vehiculo_id: id,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.success) {
        reactives.listGaleria = response.data.data
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const changeShowGaleria = () => {
  if (props.model != null) {
    reactives.showGaleria = !reactives.showGaleria
    if (reactives.showGaleria) {
      reactives.labelShowGaleria = 'Administrar Datos'
    } else {
      reactives.labelShowGaleria = 'Administrar Galeria'
      queryListGaleria(props.model.id)
    }
  }
}

const changeLoad = (value) => {
  reactives.isLoad = value
}

const sendModel = async () => {
  if (reactives.placaError.length != 0) {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Validaciones sin corregir',
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

const onValidatePlaca = (e) => {
  if (!/^(?:\d{0,4}[a-zA-Z]{0,3}|[a-zA-Z]{0,3}\d{0,4})$/.test(e.target.value)) {
    reactives.placaError = 'Campo incorrecto'
  } else {
    reactives.placaError = ''
    form.placa = e.target.value.toUpperCase()
  }
}

const onValidateDetalle = (e) => {
  if (!/^[A-Za-z\s]{0,}$/.test(e.target.value)) {
    reactives.detalleError = 'El campo solo pueden ser letras.'
  } else {
    reactives.detalleError = ''
  }
}

const setErrorPlaca = (value) => {
  console.log(value)
  reactives.placaError = value
}

const createInformacion = async () => {
  const url = route('vehiculo.store', form)
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
          if (error.response.data.message.placa != null) {
            setErrorSigla(error.response.data.message.placa[0])
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
  const url = route('vehiculo.updateVehiculo', props.model.id)
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
          if (error.response.data.message.placa != null) {
            setErrorSigla(error.response.data.message.placa[0])
          } else {
            setErrorSigla('')
          }
        }
      }
    })
}

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}
</script>

<template>
  <AppLayout title="Crear Vehiculo">
    <div class="w-full mr-4" v-show="props.model != null">
      <button
        type="button"
        @click="changeShowGaleria"
        :class="
          reactives.showGaleria
            ? 'bg-blue-600 hover:bg-blue-700 focus:bg-blue-700'
            : 'bg-green-600 hover:bg-green-700 focus:bg-green-700'
        "
        class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none"
      >
        {{ reactives.labelShowGaleria }}
        <i
          :class="
            reactives.showGaleria
              ? 'fa-solid fa-table'
              : 'fa-solid fa-camera-retro'
          "
        ></i>
      </button>
    </div>
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
    <div v-else>
      <div v-if="reactives.showGaleria">
        <div v-if="props.model != null" class="w-full">
          <Galeria :model="props.model" :listado="reactives.listGaleria" />
        </div>
      </div>
      <div v-else>
        <FormSection @submitted="sendModel">
          <template #title>
            <p v-if="props.model != null">
              Tipo Vehiculo COD #{{ props.model.id }}
            </p>
            <p v-else>Registrar Vehiculo</p>
          </template>

          <template #description>
            <p v-if="props.model != null">
              <span class="font-semibold text-gray-800 leading-tight">
                Creado:
              </span>
              {{
                props.model.created_at == null
                  ? ''
                  : fecha(props.model.created_at)
              }}
            </p>
            <p v-if="props.model != null">
              <span class="font-semibold text-gray-800 leading-tight">
                Actualizado:
              </span>
              {{
                props.model.updated_at == null
                  ? ''
                  : fecha(props.model.updated_at)
              }}
            </p>
            <p>
              Complete correctamente los datos personales
            </p>
          </template>

          <template #form>
            <!-- Sigla -->
            <div class="col-span-12 sm:col-span-12">
              <InputLabel for="sigla" value="Placa" />
              <TextInput
                id="sigla"
                v-model="form.placa"
                type="text"
                class="mt-1 block w-full"
                required
                autocomplete="sigla"
                @input="onValidatePlaca"
              />
              <InputError
                class="mt-2"
                :message="reactives.placaError.toUpperCase()"
              />
              <div v-if="messages.eSigla.length > 0">
                <div v-for="(msg, index) in messages.eSigla" :key="index">
                  <InputError :message="msg.toUpperCase()" class="mt-2" />
                </div>
              </div>
            </div>
            <!-- TIPO DE VEHICULO -->
            <div class="col-span-12 sm:col-span-12">
              <label
                for="tipo_vehiculos"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un tipo de vehiculo
              </label>

              <select
                id="tipo_vehiculos"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                v-model="form.tipo_vehiculo"
              >
                <option value="automóvil">
                  AUTOMOVIL
                </option>
                <option value="motocicleta">
                  MOTOCICLETA
                </option>
                <option value="camión">
                  CAMION
                </option>
                <option value="bicicleta">
                  BICICLETA
                </option>
                <option value="otro">
                  OTRO
                </option>
              </select>
            </div>
            <!-- Detalle -->
            <div class="col-span-12 sm:col-span-12">
              <InputLabel for="detalle" value="Detalle" />
              <TextInput
                id="detalle"
                v-model="form.detalle"
                type="text"
                class="mt-1 block w-full"
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
      </div>
    </div>
  </AppLayout>
</template>
