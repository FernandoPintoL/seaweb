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
import moment from 'moment-timezone'
import Loader from '@/Componentes/Loader.vue'

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
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}
</script>

<template>
  <AppLayout title="Crear Viviendas">
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
    <FormSection v-else @submitted="sendModel">
      <template #title>
        <p v-if="props.model != null">Vivienda COD #{{ props.model.id }}</p>
        <p v-else>Registrar Vivienda</p>
      </template>

      <template #description>
        <p v-if="props.model != null">
          <span class="font-semibold text-gray-800 leading-tight">
            Creado:
          </span>
          {{ props.model.created_at ? '' : fecha(props.model.created_at) }}
        </p>
        <p v-if="props.model != null">
          <span class="font-semibold text-gray-800 leading-tight">
            Actualizado:
          </span>
          {{ props.model.updated_at ? '' : fecha(props.model.updated_at) }}
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
            for="tp_vivienda"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Seleccione un tipo de vivienda
          </label>

          <select
            id="tp_vivienda"
            required
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
            for="selects-condominios"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Seleccione un Condominio
          </label>

          <select
            id="selects-condominios"
            required
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
          <div class="flex items-center">
            <input
              type="checkbox"
              id="vvd-ocupada"
              required
              class="relative shrink-0 w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-green-600 checked:border-green-600 focus:checked:border-green-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-green-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-green-200"
              v-model="form.vivienda_ocupada"
            />
            <label
              for="vvd-ocupada"
              class="text-sm text-gray-500 ms-3 dark:text-neutral-400"
            >
              Vivienda Ocupada?
            </label>
          </div>
        </div>
        <!-- Detalle -->
        <div class="col-span-12 sm:col-span-12">
          <label
            for="vivienda-detalle"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Detalle
          </label>
          <textarea
            id="vivienda-detalle"
            autocomplete="detalle"
            v-model="form.detalle"
            rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          ></textarea>
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
  </AppLayout>
</template>
<script>
$(document).ready(function () {
  $('.custom').select2()
})
</script>
