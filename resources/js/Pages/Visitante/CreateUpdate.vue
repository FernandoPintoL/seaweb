<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Galeria from './Galeria.vue'
import Loader from '@/Componentes/Loader.vue'
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
  console.log('print list create')
  console.log(props.listado)
  queryDocument('')
  reactives.listGaleria = props.listado
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  /*DATOS DEL PERFIL*/
  perfil: {
    name: props.model != null ? props.model.perfil.name : '',
    // email: props.model != null ? props.model.perfil.email : '',
    celular: props.model != null ? props.model.perfil.celular : '',
    nroDocumento: props.model != null ? props.model.perfil.nroDocumento : '',
    tipo_documento_id:
      props.model != null ? props.model.perfil.tipo_documento_id : 0,
  },
  /* datos de habitante */
  perfil_id: props.model != null ? props.model.perfil_id : 0,
  isMobile: true,
})

const reactives = reactive({
  isLoad: false,
  list_typedocument: [],
  nameError: '',
  celularError: '',
  nroDocumentoError: '',
  showGaleria: false,
  labelShowGaleria: 'Administrar Galeria',
  listGaleria: [],
})

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

const queryListGaleria = async (id) => {
  changeLoad(true)
  const url = route('appgaleriavisitante.visitanteid', {
    visitante_id: id,
  })
  await axios
    .post(url)
    .then((response) => {
      console.log(response.data)
      if (response.data.isSuccess) {
        reactives.listGaleria = response.data.data
      }
    })
    .catch((error) => {
      console.log('respuesta error')
      console.log(error)
    })
  changeLoad(false)
}

const changeLoad = (value) => {
  reactives.isLoad = value
}

const onValidateName = (e) => {
  if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{5,}$/.test(e.target.value)) {
    reactives.nameError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.nameError = ''
    form.perfil.name = e.target.value.toUpperCase()
  }
}

const onValidateCelular = (e) => {
  if (!/^(?:[678]\d{7})?$/.test(e.target.value)) {
    reactives.celularError =
      'El campo debe tener al menos 7 caracteres y solo pueden ser números.'
  } else {
    reactives.celularError = ''
  }
}

const onValidateNroDocumento = (e) => {
  if (!/^\d{4,15}[a-zA-Z]{0,4}$/.test(e.target.value)) {
    reactives.nroDocumentoError =
      'El campo debe tener solo numeros, puede contener 1 solo (-) y umn minimo de 2 letras.'
  } else {
    reactives.nroDocumentoError = ''
  }
}

const setErrorNroDocumento = (value) => {
  console.log(value[0])
  reactives.nroDocumentoError = value[0]
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
        icon: response.data.isSuccess ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
      if (response.data.isSuccess) {
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
      if (error.response.data.isMessageError) {
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
        icon: response.data.isSuccess ? 'success' : 'error',
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
      if (error.response.data.isMessageError) {
        if (error.response.data.message.nroDocumento != null) {
          setErrorNroDocumento(error.response.data.message.nroDocumento)
        } else {
          setErrorNroDocumento('')
        }
      }
    })
}

const queryDocument = async (consulta) => {
  changeLoad(true)
  const url = route('tipodocumento.query', { query: consulta })
  await axios
    .post(url)
    .then((response) => {
      if (response.data.isSuccess) {
        reactives.list_typedocument = response.data.data
        if (reactives.list_typedocument.length > 0) {
          if (props.model != null) {
            form.perfil.tipo_documento_id = props.model.perfil.tipo_documento_id
          } else {
            form.perfil.tipo_documento_id = reactives.list_typedocument[0].id
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
  <AppLayout title="Craear Visitante">
    <div class="w-full mr-4" v-show="props.model != null">
      <button
      v-if="$page.props.user.roles.includes('super-admin') ||
                $page.props.user.permissions.includes('GALERIA_VISITANTE.LISTAR') ||
                $page.props.user.permissions.includes('GALERIA_VISITANTE.MOSTRAR') ||
                $page.props.user.permissions.includes('GALERIA_VISITANTE.CREAR') ||
                $page.props.user.permissions.includes('GALERIA_VISITANTE.EDITAR') ||
                $page.props.user.permissions_roles.includes('GALERIA_VISITANTE.MOSTRAR') ||
                $page.props.user.permissions_roles.includes('GALERIA_VISITANTE.LISTAR') ||
                $page.props.user.permissions_roles.includes('GALERIA_VISITANTE.CREAR') ||
                $page.props.user.permissions_roles.includes('GALERIA_VISITANTE.EDITAR')
            "
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
              Actualizar Visitante COD #{{ props.model.id }}
            </p>
            <p v-else>Registro del Visitante</p>
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
            <!-- Name -->
            <div class="col-span-12 sm:col-span-12">
              <label
                for="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Nombre
              </label>
              <div class="flex">
                <span
                  class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
                >
                  <svg
                    class="w-4 h-4 text-gray-500 dark:text-gray-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"
                    />
                  </svg>
                </span>
                <input
                  v-model="form.perfil.name"
                  @input="onValidateName"
                  required
                  type="text"
                  id="name"
                  class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Bonnie Green"
                />
              </div>
              <InputError
                class="mt-2"
                :message="reactives.nameError.toUpperCase()"
              />
            </div>

            <!-- TIPO DE DOCUMENTO -->
            <div class="col-span-12 sm:col-span-12">
              <label
                for="select-tp-doc"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Seleccione un tipo de documento
              </label>

              <select
                id="select-tp-doc"
                required
                class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                v-model="form.perfil.tipo_documento_id"
              >
                <option
                  v-for="item in reactives.list_typedocument"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.id }} : {{ item.sigla }} : {{ item.detalle }}
                </option>
              </select>
            </div>
            <!-- NRO DOCUMENTO -->
            <div class="col-span-12 sm:col-span-12">
              <label
                for="nroDocumento"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Numero de Documentacion
              </label>
              <div class="flex">
                <span
                  class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
                >
                  <i class="fa-solid fa-id-card"></i>
                </span>
                <input
                  v-model="form.perfil.nroDocumento"
                  @input="onValidateNroDocumento"
                  required
                  type="text"
                  id="nroDocumento"
                  class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="8887777-EXT"
                />
              </div>
              <InputError
                class="mt-2"
                :message="reactives.nroDocumentoError.toUpperCase()"
              />
            </div>
            <!-- Celular -->
            <div class="col-span-12 sm:col-span-12">
              <label
                for="celular"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Celular
              </label>
              <div class="flex">
                <span
                  class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
                >
                  <i class="fa-solid fa-square-phone"></i>
                </span>
                <input
                  v-model="form.perfil.celular"
                  @input="onValidateCelular"
                  type="tel"
                  id="celular"
                  class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="+59173682145"
                />
              </div>
              <InputError
                class="mt-2"
                :message="reactives.celularError.toUpperCase()"
              />
            </div>
          </template>

          <template #actions>
            <PrimaryButton
                v-if="$page.props.user.roles.includes('super-admin') ||
                $page.props.user.permissions.includes('VISITANTE.CREAR') ||
                $page.props.user.permissions.includes('VISITANTE.EDITAR') ||
                $page.props.user.permissions_roles.includes('VISITANTE.CREAR') ||
                $page.props.user.permissions_roles.includes('VISITANTE.EDITAR')
            "
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
<script>
$(document).ready(function () {
  $('.custom').select2()
})
</script>
