<script setup>
import { ref, inject, reactive, onMounted } from 'vue'
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
  condominio_id: props.model != null ? props.model.condominio_id : 0,
  vivienda_ocupada: props.model != null ? props.model.vivienda_ocupada : false,
  nroVivienda: props.model != null ? props.model.nroVivienda : '',
  detalle: props.model != null ? props.model.detalle : '',
  nroEspacios: props.model != null ? props.model.nroEspacios : 0,
  tipo_vivienda_id: props.model != null ? props.model.tipo_vivienda_id : 0,
})

onMounted(() => {
  queryTiposViviendas('')
  queryCondominios('')
})

const reactives = reactive({
  isLoad: false,
  list_condominios: [],
  list_tipos_viviendas: [],
  nroViviendaError: '',
})

const changeLoad = (value) => {
  reactives.isLoad = value
}

const sendModel = async () => {
  if (reactives.nroViviendaError.length != 0) {
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

const onValidateNroVivienda = (e) => {
  if (!/^[0-9-a-zA-Z]?$/.test(e.target.value)) {
    reactives.nroViviendaError = 'El campo debe tener solo números o letras.'
  } else {
    reactives.nroViviendaError = ''
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
  const url = route('vivienda.store', form)
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
  const url = route('vivienda.update', props.model.id)
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

const queryTiposViviendas = async (consulta) => {
  changeLoad(true)
  const url = route('tipovivienda.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      console.log(response)
      if (response.data.success) {
        reactives.list_tipos_viviendas = response.data.data
        if (reactives.list_tipos_viviendas.length > 0) {
          if (props.model != null) {
            form.tipo_vivienda_id = props.model.tipo_vivienda_id
          } else {
            form.tipo_vivienda_id = reactives.list_tipos_viviendas[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const queryCondominios = async (consulta) => {
  changeLoad(true)
  const url = route('condominio.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      console.log(response)
      if (response.data.success) {
        reactives.list_condominios = response.data.data
        if (reactives.list_condominios.length > 0) {
          if (props.model != null) {
            form.condominio_id = props.model.condominio_id
          } else {
            form.condominio_id = reactives.list_condominios[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const fecha = (fechaData) => {
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>

<template>
  <AppLayout title="Vivienda">
    <template #header>
      <h2
        v-if="props.model != null"
        class="font-semibold text-xl text-gray-800 leading-tight"
      >
        ACTUALIZAR VIVIENDA {{ props.model.nroVivienda }}
      </h2>
      <h2 v-else class="font-semibold text-xl text-gray-800 leading-tight">
        CREAR VIVIENDA
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
          <p v-if="props.model != null">Vivienda COD #{{ props.model.id }}</p>
          <p v-else>Registrar Vivienda</p>
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
          <!-- NRO VIVIENDA -->
          <div class="col-span-12 sm:col-span-12">
            <InputLabel for="nroVivienda" value="NRO VIVIENDA" />
            <TextInput
              id="nroVivienda"
              v-model="form.nroVivienda"
              type="text"
              class="mt-1 block w-full"
              required
              autocomplete="nroVivienda"
            />
            <InputError
              class="mt-2"
              :message="reactives.nroViviendaError.toUpperCase()"
            />
          </div>
          <!-- TIPO DE VIVIENDA -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="tipo_vivienda"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un tipo de vivienda
            </label>

            <select
              id="tipo_vivienda"
              required
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.tipo_vivienda_id"
            >
              <option
                v-for="item in reactives.list_tipos_viviendas"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / Detalle: {{ item.detalle }}
              </option>
            </select>
          </div>

          <!-- CONDOMINIO -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="condominio_id"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un Condominio
            </label>

            <select
              id="condominio_id"
              required
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.condominio_id"
            >
              <option
                v-for="item in reactives.list_condominios"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / Razon Social: {{ item.razonSocial }}
              </option>
            </select>
          </div>
          <!-- Es dueño -->
          <div class="col-span-12 sm:col-span-12">
            <label class="inline-flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="form.vivienda_ocupada"
                :value="form.vivienda_ocupada"
                class="sr-only peer"
              />
              <div
                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
              ></div>
              <span
                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
              >
                Vivienda Ocupada
              </span>
            </label>
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
            <!-- <InputError
              class="mt-2"
              :message="reactives.detalleError.toUpperCase()"
            /> -->
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
