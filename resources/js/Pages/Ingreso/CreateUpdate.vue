<script setup>
import { ref, inject, reactive, onMounted } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Loader from '@/Componentes/Loader.vue'
import moment from 'moment-timezone'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
  isRegister: {
    default: true,
    type: Boolean,
  },
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  tipo_ingreso: props.model != null ? props.model.tipo_ingreso : '',
  detalle: props.model != null ? props.model.detalle : '',
  detalle_salida: props.model != null ? props.model.detalle_salida : '',
  isAutorizado: props.model != null ? props.model.isAutorizado : true,
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

onMounted(() => {
  if (props.model != null) {
    reactives.ingreso_vehiculo = props.model.vehiculo_id != null
  }
  reactives.isLoad = true
  queryResidentes('')
  queryVisitantes('')
  queryVehiculos('')
  queryTipoVisitas('')
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
  reactives.isLoad = true
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
  reactives.isLoad = false
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
        // form.reset()
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
        // console.log(reactives.list_vehiculos)
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
  reactives.isLoad = false
}

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}
</script>

<template>
  <AppLayout title="Ingresos">
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
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
            {{
              props.model.created_at == null
                ? ''
                : fecha(props.model.created_at)
            }}
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
          <div class="grid grid-cols-3 gap-4">
            <div class="col-start-1 col-end-3">
              <label
                for="select-res"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un Residente
              </label>
            </div>
            <div class="col-end-7 col-span-2">
              <a
                :href="route('habitante.create')"
                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-800 hover:text-blue-600 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:text-white/70 dark:focus:text-white/70"
              >
                Nuevo
              </a>
            </div>
          </div>

          <select
            id="select-res"
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            v-model="form.residente_habitante_id"
          >
            <option
              v-for="item in reactives.list_residentes"
              :key="item.id"
              :value="item.id"
            >
              Cod: {{ item.id }} | Residente: {{ item.name }}
            </option>
          </select>
        </div>
        <!-- VISITANTES -->
        <div class="col-span-12 sm:col-span-12">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-start-1 col-end-3">
              <label
                for="select-visit"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un Visitante
              </label>
            </div>
            <div class="col-end-7 col-span-2">
              <a
                :href="route('visitante.create')"
                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-800 hover:text-blue-600 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:text-white/70 dark:focus:text-white/70"
              >
                Nuevo
              </a>
            </div>
          </div>
          <select
            id="select-visit"
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            v-model="form.visitante_id"
          >
            <option
              v-for="item in reactives.list_visitantes"
              :key="item.id"
              :value="item.id"
            >
              Cod: {{ item.id }} | Visitante: {{ item.name }}
            </option>
          </select>
        </div>
        <!-- INGRESO CON VEHICULO -->
        <div class="col-span-12 sm:col-span-12">
          <!-- Switch/Toggle -->
          <div class="flex items-center">
            <input
              type="checkbox"
              id="ingreso-vehiculo"
              class="relative shrink-0 w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-green-600 checked:border-green-600 focus:checked:border-green-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-green-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-green-200"
              v-model="reactives.ingreso_vehiculo"
            />
            <label
              for="ingreso-vehiculo"
              class="text-sm text-gray-500 ms-3 dark:text-neutral-400"
            >
              Ingreso con Vehiculo
            </label>
          </div>
          <!-- End Switch/Toggle -->
        </div>
        <!-- VEHICULOS -->
        <div
          class="col-span-12 sm:col-span-12"
          v-if="reactives.ingreso_vehiculo"
        >
          <div class="grid grid-cols-3 gap-4">
            <div class="col-start-1 col-end-3">
              <label
                for="select-cars"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un vehiculo
              </label>
            </div>
            <div class="col-end-7 col-span-2">
              <a
                :href="route('vehiculo.create')"
                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-800 hover:text-blue-600 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:text-white/70 dark:focus:text-white/70"
              >
                Nuevo
              </a>
            </div>
          </div>

          <select
            id="select-cars"
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            v-model="form.vehiculo_id"
          >
            <option
              v-for="item in reactives.list_vehiculos"
              :key="item.id"
              :value="item.id"
            >
              Cod: {{ item.id }} | Placa: {{ item.placa }}
            </option>
          </select>
        </div>
        <!-- TIPO DE VISITAS -->
        <div class="col-span-12 sm:col-span-12">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-start-1 col-end-3">
              <label
                for="select-tipo-visit"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un tipo visita
              </label>
            </div>
            <div class="col-end-7 col-span-2">
              <a
                :href="route('vehiculo.create')"
                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-800 hover:text-blue-600 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:text-white/70 dark:focus:text-white/70"
              >
                Nuevo
              </a>
            </div>
          </div>

          <select
            id="select-tipo-visit"
            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            v-model="form.tipo_visita_id"
          >
            <option
              v-for="item in reactives.list_tipo_visitas"
              :key="item.id"
              :value="item.id"
            >
              Cod: {{ item.id }} | Tipo visita: {{ item.detalle }}
            </option>
          </select>
        </div>
        <!-- Es AUTORIZADO -->
        <div class="col-span-12 sm:col-span-12">
          <!-- Switch/Toggle -->
          <div class="flex items-center">
            <input
              type="checkbox"
              id="ingreso-autorizado"
              required
              class="relative shrink-0 w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-green-600 checked:border-green-600 focus:checked:border-green-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-green-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-green-200"
              v-model="form.isAutorizado"
            />
            <label
              for="ingreso-autorizado"
              class="text-sm text-gray-500 ms-3 dark:text-neutral-400"
            >
              Ingreso Autorizado
            </label>
          </div>
          <!-- End Switch/Toggle -->
        </div>
        <!-- Detalle -->
        <div class="col-span-12 sm:col-span-12">
          <label
            for="detalle"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Detalle Resgistro
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
        <!-- Detalle Salida -->
        <div class="col-span-12 sm:col-span-12" v-if="!props.isRegister">
          <label
            for="detalle_salida"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Detalle Salida
          </label>
          <textarea
            id="detalle_salida"
            autocomplete="detalle_salida"
            v-model="form.detalle_salida"
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
