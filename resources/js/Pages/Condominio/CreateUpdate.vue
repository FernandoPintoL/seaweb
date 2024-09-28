<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Loader from '@/Componentes/Loader.vue'
import moment from 'moment-timezone'

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
  isPassword: true,
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
    reactives.direccionError.length != 0 ||
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

  /*if (form.razonSocial.length == 0) form.razonSocial = null
  if (form.nit == null || form.nit.length == 0) {
    form.nit = null
    form.perfil.nroDocumento = null
  }*/

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
      console.log(form)
      reactives.isLoad = true
      if (props.model != null) {
        updateInformation()
      } else {
        createInformacion()
      }
      reactives.isLoad = false
    }
  })
}

const onValidatePropietario = (e) => {
  if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{5,}$/.test(e.target.value)) {
    reactives.propietarioError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.propietarioError = ''
    form.propietario = e.target.value.toUpperCase()
    form.perfil.name = e.target.value.toUpperCase()
    form.user.name = e.target.value.toUpperCase()
  }
}

const onValidateRazonSocial = (e) => {
  if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{0,}$/.test(e.target.value)) {
    reactives.razonSocialError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.razonSocialError = ''
    form.razonSocial = e.target.value.toUpperCase()
  }
}

const onValidateDireccion = (e) => {
  if (e.target.value.length < 3) {
    reactives.direccionError = 'El campo debe tener al menos 3 caracteres.'
  } else {
    reactives.direccionError = ''
    form.direccion = e.target.value.toUpperCase()
  }
}

const onValidateUserNick = (e) => {
  if (!/^[a-zA-Z0-9]{3,40}$/.test(e.target.value)) {
    reactives.userNickError =
      'El campo debe tener un minimo de 3 caracteres y un maximo de 10 caracteres solo letras y números, sin tildes'
  } else {
    reactives.userNickError = ''
  }
}

const onValidateEmail = (e) => {
  if (!/^[a-zA-Z0-9.]+@[a-zA-Z]+\.[a-zA-Z]+$/.test(e.target.value)) {
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
  if (!/^(?:[678]\d{7})?$/.test(e.target.value)) {
    reactives.celularError =
      'El campo debe tener al menos 7 caracteres y solo pueden ser números.'
  } else {
    reactives.celularError = ''
  }
}

const onValidateNroDocumento = (e) => {
  if (!/^\d{0,15}[a-zA-Z]{0,4}$/.test(e.target.value)) {
    reactives.nroDocumentoError =
      'El campo debe tener solo números, y/o complemento'
  } else {
    reactives.nroDocumentoError = ''
    form.perfil.nroDocumento = e.target.value
    form.nit = e.target.value
  }
}

const setErrorPropietario = (value) => {
  console.log(value)
  reactives.propietarioError = value
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
      console.log(response.data)
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
      console.log(error)
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Verifique el formulario',
        showConfirmButton: false,
        timer: 1500,
      })
      if (error.response.data.isMessageError) {
        if (error.response.data.message.propietario != null) {
          setErrorPropietario(error.response.data.message.propietario[0])
        } else {
          setErrorPropietario('')
        }
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
        if (error.response.data.message.nit != null) {
          setErrorNroDocumento(error.response.data.message.nit[0])
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
        icon: response.data.isSuccess ? 'success' : 'error',
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
      if (error.response.data.isMessageError) {
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

const fecha = (fechaData) => {
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}

const changeInputPassword = () => {
  reactives.isPassword = !reactives.isPassword
}
</script>

<template>
  <AppLayout title="CONDOMINIOS">
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
    <FormSection v-else @submitted="sendModel">
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
          {{
            props.model.created_at == null ? '' : fecha(props.model.created_at)
          }}
        </p>
        <p v-if="props.model != null">
          <span class="font-semibold text-gray-800 leading-tight">
            Actualizado:
          </span>
          {{
            props.model.updated_at == null ? '' : fecha(props.model.updated_at)
          }}
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
            Nombre(*)
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
        <!-- DIRECCION -->
        <div class="col-span-12 sm:col-span-12">
          <label
            for="direccion"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
          >
            Dirección(*)
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
              @input="onValidateDireccion"
              type="text"
              id="direccion"
              class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Bonnie Green"
            />
          </div>
          <InputError class="mt-2" :message="reactives.direccionError" />
          <!-- <InputError
              class="mt-2"
              :message="reactives.nameError.toUpperCase()"
            /> -->
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
              type="text"
              id="razonSocial"
              class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Bonnie Green"
            />
          </div>
          <InputError class="mt-2" :message="reactives.razonSocialError" />
        </div>
        <!-- NRO DE DOCUMENTO -->
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
            Telefono
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
              placeholder="73682145"
            />
          </div>
          <InputError class="mt-2" :message="reactives.celularError" />
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
            UserNick(*)
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
            Email(*)
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
            Password(*)
          </label>
          <div class="flex">
            <span
              @click="changeInputPassword"
              class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
            >
              <i
                :class="reactives.isPassword ? 'fa-eye' : 'fa-eye-slash'"
                class="fa-solid"
              ></i>
            </span>
            <input
              v-model="form.user.password"
              @input="onValidatePassword"
              name="password"
              :type="reactives.isPassword ? 'password' : 'text'"
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
  </AppLayout>
</template>
