<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import SectionBorder from '@/Components/SectionBorder.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import InputLabel from '@/Components/InputLabel.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import moment from 'moment'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
})

onMounted(() => {
  // queryDocument('')
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  propietario: props.model != null ? props.model.propietario : '',
  razonSocial: props.model != null ? props.model.razonSocial : '',
  nit: props.model != null ? props.model.nit : '',
  cantidad_viviendas: props.model != null ? props.model.cantidad_viviendas : '',
  perfil_id: props.model != null ? props.model.perfil_id : 0,
  user_id: props.model != null ? props.model.user_id : 0,
  /*DATOS DEL PERFIL*/
  perfil: {
    name: props.model != null ? props.model.perfil.name : '',
    email: props.model != null ? props.model.perfil.email : '',
    direccion: props.model != null ? props.model.perfil.direccion : '',
    celular: props.model != null ? props.model.perfil.celular : '',
    nroDocumento: props.model != null ? props.model.perfil.nroDocumento : '',
    tipo_documento_id: 3,
  },
  user: {
    name: '',
    email: '',
    usernick: '',
    password: '',
  },
  isMobile: true,
})

const reactives = reactive({
  isLoad: false,
  list_typedocument: [],
  propietarioError: '',
  razonSocialError: '',
  nameError: '',
  direccionError: '',
  celularError: '',
  nroDocumentoError: '',
  userNickError: '',
  emailError: '',
  passwordError: '',
})

const sendModel = async () => {
  if (
    reactives.propietarioError.length != 0 ||
    reactives.razonSocialError.length != 0 ||
    reactives.nroDocumentoError.length != 0 ||
    reactives.celularError.length != 0 ||
    reactives.userNickError.length != 0 ||
    reactives.emailError.length != 0 ||
    reactives.passwordError.length != 0
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

const changeLoad = (value) => {
  reactives.isLoad = value
}

const onValidatePropietario = (e) => {
  if (!/^[A-Za-z\s]{5,}$/.test(e.target.value)) {
    reactives.propietarioError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.propietarioError = ''
    form.perfil.name = e.target.value
    form.user.name = e.target.value
  }
}

const onValidateRazonSocial = (e) => {
  if (!/^[A-Za-z\s]{5,}$/.test(e.target.value)) {
    reactives.razonSocialError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.razonSocialError = ''
  }
}

const onValidateUserNick = (e) => {
  if (!/^[a-zA-Z0-9]{3,40}$/.test(e.target.value)) {
    reactives.userNickError =
      'El campo debe tener un minimo de 3 caracteres y un maximo de 10 caracteres solo letras y números'
  } else {
    reactives.userNickError = ''
  }
}

const onValidateEmail = (e) => {
  if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(e.target.value)) {
    reactives.emailError = 'El campo debe ser tipo Email'
  } else {
    reactives.emailError = ''
    form.perfil.email = e.target.value
  }
}

const onValidatePassword = (e) => {
  if (!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,40}$/.test(e.target.value)) {
    reactives.passwordError =
      'La contraseña debe tener entre 8 y 20 caracteres e incluir al menos una letra mayúscula, una letra minúscula y un número.'
  } else {
    reactives.passwordError = ''
  }
}

const onValidateCelular = (e) => {
  if (!/^[\d+]+$/.test(e.target.value)) {
    reactives.celularError =
      'El campo debe tener al menos 7 caracteres y solo pueden ser números y (+).'
  } else {
    reactives.celularError = ''
  }
}

const onValidateNroDocumento = (e) => {
  if (!/^\d+(-[a-zA-Z]{2,3})?$/.test(e.target.value)) {
    reactives.nroDocumentoError =
      'El campo debe tener solo numeros, puede contener 1 solo (-) y umn minimo de 2 letras.'
  } else {
    reactives.nroDocumentoError = ''
    form.perfil.nroDocumento = e.target.value
  }
}

const setErrorNroDocumento = (value) => {
  console.log(value)
  reactives.nroDocumentoError = value
}

const setErrorEmail = (value) => {
  console.log(value)
  reactives.emailError = value
}

const setErrorUserNick = (value) => {
  console.log(value)
  reactives.userNickError = value
}

const setErrorRazonSocial = (value) => {
  console.log(value)
  reactives.razonSocialError = value
}

const createInformacion = async () => {
  console.log(form)
  const url = route('condominio.store', form)
  await axios
    .post(url)
    .then((response) => {
      console.log(response)
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
      console.log(error)
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Verifique el formulario',
        showConfirmButton: false,
        timer: 1500,
      })
      if (error.response.data.messageError) {
        if (error.response.data.message.razonSocial != null) {
          setErrorRazonSocial(error.response.data.message.razonSocial[0])
        } else {
          setErrorRazonSocial('')
        }
        if (error.response.data.message.nroDocumento != null) {
          setErrorNroDocumento(error.response.data.message.nroDocumento[0])
        } else {
          setErrorNroDocumento('')
        }
        if (error.response.data.message.email != null) {
          setErrorEmail(error.response.data.message.email[0])
        } else {
          setErrorEmail('')
        }
        if (error.response.data.message.usernick != null) {
          setErrorUserNick(error.response.data.message.usernick[0])
        } else {
          setErrorUserNick('')
        }
      }
    })
}

const updateInformation = async () => {
  console.log(form)
  const url = route('condominio.update', form.id)
  await axios
    .put(url, form)
    .then((response) => {
      console.log(response)
      Swal.fire({
        position: 'top-end',
        icon: response.data.success ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
    })
    .catch((error) => {
      console.log(error)
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Verifique el formulario',
        showConfirmButton: false,
        timer: 1500,
      })
      if (error.response.data.messageError) {
        if (error.response.data.message.razonSocial != null) {
          setErrorRazonSocial(error.response.data.message.razonSocial[0])
        } else {
          setErrorRazonSocial('')
        }
        if (error.response.data.message.nroDocumento != null) {
          setErrorNroDocumento(error.response.data.message.nroDocumento[0])
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
      if (response.data.success) {
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
  return moment(fechaData).format('YYYY-MM-DD HH:MM:SS')
}
</script>

<template>
  <AppLayout title="CONDOMINIO">
    <template #header>
      <h2
        v-if="props.model != null"
        class="font-semibold text-xl text-gray-800 leading-tight"
      >
        ACTUALIZAR CONDOMINIO
      </h2>
      <h2 v-else class="font-semibold text-xl text-gray-800 leading-tight">
        CREAR CONDOMINIO
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
            Actualizar Condominio COD #{{ props.model.id }}
          </p>
          <p v-else>Registro del Condominio</p>
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
          <!-- PROPIETARIO -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="propietario"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Propietario
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-person-chalkboard"></i>
              </span>
              <input
                v-model="form.propietario"
                @input="onValidatePropietario"
                required
                type="text"
                id="propietario"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Bonnie Green"
              />
            </div>
            <InputError class="mt-2" :message="reactives.propietarioError" />
          </div>
          <!-- RAZON SOCIAL -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="razonSocial"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Razon Social
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-house-laptop"></i>
              </span>
              <input
                v-model="form.razonSocial"
                @input="onValidateRazonSocial"
                required
                type="text"
                id="razonSocial"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Bonnie Green"
              />
            </div>
            <InputError class="mt-2" :message="reactives.razonSocialError" />
          </div>
          <!-- TIPO DE DOCUMENTO  DEBERIA SER NIT-->
          <!-- <div class="col-span-12 sm:col-span-12">
            <label
              for="countries"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Seleccione un tipo de documento
            </label>

            <select
              id="countries"
              required
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
          </div> -->
          <!-- NRO DOCUMENTO NIT -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="nroDocumento"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              (NIT) NÚMERO DE IDENTIFICACION TRIBUTARIA
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-id-card"></i>
              </span>
              <input
                v-model="form.nit"
                @input="onValidateNroDocumento"
                required
                type="text"
                id="nroDocumento"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="8887777-EXT"
              />
            </div>
            <InputError class="mt-2" :message="reactives.nroDocumentoError" />
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
            <InputError class="mt-2" :message="reactives.celularError" />
          </div>
          <!-- DIRECCION -->
          <div class="col-span-12 sm:col-span-12">
            <label
              for="direccion"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Dirección
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-location-dot"></i>
              </span>
              <input
                v-model="form.perfil.direccion"
                required
                type="text"
                id="direccion"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Bonnie Green"
              />
            </div>
            <!-- <InputError
              class="mt-2"
              :message="reactives.nameError.toUpperCase()"
            /> -->
          </div>
          <!-- DATOS DE INICIO  -->
          <div v-if="props.model == null" class="col-span-12 sm:col-span-12">
            <div class="hidden sm:block">
              <div class="py-2">
                <div class="border-t border-gray-200" />
              </div>
            </div>
            <p>DATOS PARA INICIAR SESSION</p>
          </div>
          <!-- USERNICK -->
          <div v-if="props.model == null" class="col-span-12 sm:col-span-12">
            <label
              for="usernick"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              UserNick
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-user-shield"></i>
              </span>
              <input
                v-model="form.user.usernick"
                @input="onValidateUserNick"
                required
                type="text"
                id="usernick"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="fpl3001"
              />
            </div>
            <InputError class="mt-2" :message="reactives.userNickError" />
          </div>
          <!-- EMAIL -->
          <div v-if="props.model == null" class="col-span-12 sm:col-span-12">
            <label
              for="email"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Email
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-at"></i>
              </span>
              <input
                v-model="form.user.email"
                @input="onValidateEmail"
                type="email"
                id="email"
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="pintolinofernando@gmail.com"
              />
            </div>
            <InputError class="mt-2" :message="reactives.emailError" />
          </div>
          <!-- PASSWORD -->
          <div v-if="props.model == null" class="col-span-12 sm:col-span-12">
            <label
              for="password"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Password
            </label>
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
              >
                <i class="fa-solid fa-eye"></i>
              </span>
              <input
                v-model="form.user.password"
                @input="onValidatePassword"
                name="password"
                type="password"
                required
                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Ingrese password"
              />
            </div>
            <InputError class="mt-2" :message="reactives.passwordError" />
          </div>
        </template>

        <template #actions>
          <PrimaryButton
            v-if="!reactives.isLoad"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Guardar
          </PrimaryButton>
        </template>
      </FormSection>
    </div>
  </AppLayout>
</template>
