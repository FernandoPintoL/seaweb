<script setup>
import { ref, inject, reactive, onMounted, onBeforeMount } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import moment from 'moment'
import Select2 from 'vue3-select2-component'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  tipo_ingreso: props.model != null ? props.model.tipo_ingreso : '',
  detalle: props.model != null ? props.model.detalle : '',
  isAutorizado: props.model != null ? props.model.isAutorizado : false,
  visitante_id: props.model != null ? props.model.visitante_id : '',
  residente_habitante_id:
    props.model != null ? props.model.residente_habitante_id : 0,
  autoriza_habitante_id:
    props.model != null ? props.model.autoriza_habitante_id : 0,
  ingresa_habitante_id:
    props.model != null ? props.model.ingresa_habitante_id : 0,
  vehiculo_id: props.model != null ? props.model.vehiculo_id : 0,
  tipo_visita_id: props.model != null ? props.model.tipo_visita_id : 0,
  user_id: props.model != null ? props.model.user_id : 0,
})

onBeforeMount(() => {
  /*queryResidentes('')
  queryVisitantes('')
  queryVehiculos('')
  queryTipoVisitas('')*/
  /*$(document).ready(function () {
    console.log('montado')
    $('#select-residente').select2()
  })*/
})

onMounted(() => {
  console.log(props.model)
  reactives.ingreso_vehiculo = props.model.vehiculo_id != null
  changeLoad(true)
  queryResidentes('')
  queryVisitantes('')
  queryVehiculos('')
  queryTipoVisitas('')
  changeLoad(false)
  /*$(document).ready(function () {
    console.log('montado')
    $('#select-residente').select2()
  })*/
})

const reactives = reactive({
  isLoad: false,
  ingreso_vehiculo: true,
  list_residentes: [],
  list_visitantes: [],
  list_vehiculos: [],
  list_tipo_visitas: [],
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
  form.tipo_ingreso = reactives.ingreso_vehiculo ? 'vehiculo' : 'caminando'
  form.autoriza_habitante_id = form.residente_habitante_id
  // form.user_id = page.props.auth.user.id
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
  const url = route('ingreso.store', form)
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
  const url = route('ingreso.updateIngreso', props.model.id)
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

const queryResidentes = async (consulta) => {
  const url = route('habitante.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      if (response.data.success) {
        reactives.list_residentes = response.data.data
        if (reactives.list_residentes.length > 0) {
          if (props.model != null) {
            form.residente_habitante_id = props.model.residente_habitante_id
          } else {
            form.residente_habitante_id = reactives.list_residentes[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
}

const queryVisitantes = async (consulta) => {
  const url = route('visitante.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      if (response.data.success) {
        reactives.list_visitantes = response.data.data
        if (reactives.list_visitantes.length > 0) {
          if (props.model != null) {
            form.visitante_id = props.model.visitante_id
          } else {
            form.visitante_id = reactives.list_visitantes[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
}

const queryVehiculos = async (consulta) => {
  const url = route('vehiculo.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      if (response.data.success) {
        reactives.list_vehiculos = response.data.data
        console.log('vehiculos')
        console.log(reactives.list_vehiculos)
        if (reactives.list_vehiculos.length > 0) {
          if (props.model != null) {
            form.vehiculo_id = props.model.vehiculo_id
          } else {
            form.vehiculo_id = reactives.list_vehiculos[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
}

const queryTipoVisitas = async (consulta) => {
  const url = route('tipovisita.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      if (response.data.success) {
        reactives.list_tipo_visitas = response.data.data
        if (reactives.list_tipo_visitas.length > 0) {
          if (props.model != null) {
            form.tipo_visita_id = props.model.tipo_visita_id
          } else {
            form.tipo_visita_id = reactives.list_tipo_visitas[0].id
          }
        }
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
}

const fecha = (fechaData) => {
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>

<template>
  <AppLayout title="Ingresos">
    <template #header>
      <h2
        v-if="props.model != null"
        class="font-semibold text-xl text-gray-800 leading-tight"
      >
        ACTUALIZAR INGRESOS {{ props.model.id }}
      </h2>
      <h2 v-else class="font-semibold text-xl text-gray-800 leading-tight">
        CREAR INGRESOS
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
          <p v-if="props.model != null">Ingreso COD #{{ props.model.id }}</p>
          <p v-else>Registrar Ingreso</p>
        </template>

        <template #description>
          <div v-if="props.model != null">
            <span
              :class="
                props.model.isAutorizado ? 'text-green-700' : 'text-red-500'
              "
            >
              {{
                props.model.isAutorizado
                  ? 'INGRESO AUTORIZADO'
                  : 'INGRESO NO AUTORIZADO'
              }}
            </span>
            <p>
              <span class="font-semibold text-gray-800 leading-tight">
                Creado:
              </span>
              {{ fecha(props.model.created_at) }}
            </p>
            <p>
              <span class="font-semibold text-gray-800 leading-tight">
                {{
                  props.model.salida_created_at == null
                    ? 'SALIDA NO REGISTRADA'
                    : 'Salida registrada: '
                }}
              </span>
              {{
                props.model.salida_created_at == null
                  ? ''
                  : props.model.salida_created_at
              }}
            </p>
            <span
              :class="
                props.model.vehiculo_id == null
                  ? 'text-red-700'
                  : 'text-green-500'
              "
            >
              {{
                props.model.vehiculo_id == null
                  ? 'INGRESO SIN VEHICULO'
                  : 'INGRESO CON VEHICULO'
              }}
            </span>
          </div>
          <p v-if="props.model == null">
            Complete correctamente los datos personales
          </p>
        </template>

        <template #form>
          <!-- RESIDENTES -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="select-residente"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un Residente
            </label>

            <select
              id="select-residente"
              required
              class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.residente_habitante_id"
            >
              <option
                v-for="item in reactives.list_residentes"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / nombre: {{ item.name }}
              </option>
            </select>
          </div>
          <!-- VISITANTES -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="select-visitante"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un Visitante
            </label>

            <select
              id="select-visitante"
              required
              class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.visitante_id"
            >
              <option
                v-for="item in reactives.list_visitantes"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / visitante: {{ item.name }}
              </option>
            </select>
          </div>
          <!-- INGRESO CON VEHICULO -->
          <div class="col-span-12 sm:col-span-12">
            <label class="inline-flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="reactives.ingreso_vehiculo"
                :value="reactives.ingreso_vehiculo"
                class="sr-only peer"
              />
              <div
                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
              ></div>
              <span
                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
              >
                Ingreso con vehiculo
              </span>
            </label>
          </div>
          <!-- VEHICULOS -->
          <div
            v-if="reactives.ingreso_vehiculo"
            class="col-span-12 sm:col-span-12"
          >
            <label
              for="select-vehiculo"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un vehiculo
            </label>

            <select
              id="select-vehiculo"
              required
              class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.vehiculo_id"
            >
              <option
                v-for="item in reactives.list_vehiculos"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / placa: {{ item.placa }}
              </option>
            </select>
          </div>
          <!-- TIPO DE VISITAS -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="visitante"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un tipo visita
            </label>

            <select
              id="visitante"
              required
              class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form.tipo_visita_id"
            >
              <option
                v-for="item in reactives.list_tipo_visitas"
                :key="item.id"
                :value="item.id"
              >
                Cod: {{ item.id }} / tipo visita: {{ item.detalle }}
              </option>
            </select>
          </div>
          <!-- Es AUTORIZADO -->
          <div class="col-span-12 sm:col-span-12">
            <label class="inline-flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="form.isAutorizado"
                :value="form.isAutorizado"
                class="sr-only peer"
              />
              <div
                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
              ></div>
              <span
                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
              >
                Ingreso Autorizado
              </span>
            </label>
          </div>
          <!-- Detalle -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="visitante"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Detalle
            </label>
            <textarea
              id="detalle"
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

      <!-- <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        @click="showAlert"
      >
        Alert
      </PrimaryButton> -->
    </div>
  </AppLayout>
</template>
