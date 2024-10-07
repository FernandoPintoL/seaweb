<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Loader from '@/Componentes/Loader.vue'
import moment from 'moment-timezone'
import SectionBorder from '@/Components/SectionBorder.vue'

const Swal = inject('$swal')

const props = defineProps({
  model: Object,
  roles: Object,
  model_roles: Object,
})

onMounted(() => {
  // queryDocument('')
  reactives.roles = props.roles
  if (props.model != null) {
    form.roles = props.model_roles
  }
})

const form = useForm({
  id: props.model != null ? props.model.id : '',
  name: props.model != null ? props.model.name : '',
  email: props.model != null ? props.model.email : '',
  usernick: props.model != null ? props.model.usernick : '',
  password: props.model != null ? props.model.password : '',
  repeat_password: '',
  terms: 'accepted',
  isMobile: true,
  roles: [],
})

const reactives = reactive({
  isLoad: false,
  isPassword: true,
  list_typedocument: [],
  list_roles: [],
  nameError: '',
  userNickError: '',
  emailError: '',
  passwordError: '',
  password_confirmation: '',
})

const cargarRol = (id) => {
  console.log(id)
  if (!form.roles.includes(id)) {
    form.roles.push(id)
  }
}

const sendModel = async () => {
  if (
    reactives.nameError.length != 0 ||
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

const onValidateName = (e) => {
  if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,}$/.test(e.target.value)) {
    reactives.nameError =
      'El campo debe tener al menos 2 caracteres y solo pueden ser letras.'
  } else {
    reactives.nameError = ''
    form.name = e.target.value.toUpperCase()
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
  }
}

const onValidatePassword = (e) => {
  if (!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,40}$/.test(e.target.value)) {
    reactives.passwordError =
      'La contraseña debe tener entre 8 y 20 caracteres e incluir al menos una letra mayúscula, una letra minúscula y un número.'
  } else {
    reactives.passwordError = ''
    form.password_confirmation = e.target.value
  }
}

const setErrorName = (value) => {
  console.log(value)
  reactives.nameError = value
}

const setErrorEmail = (value) => {
  console.log(value)
  reactives.emailError = value
}

const setErrorUserNick = (value) => {
  console.log(value)
  reactives.userNickError = value
}

const createInformacion = async () => {
  console.log(form)
  const url = route('users.store', form)
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
        if (error.response.data.message.name != null) {
          setErrorName(error.response.data.message.name[0])
        } else {
          setErrorName('')
        }
        if (error.response.data.message.usernick != null) {
          setErrorUserNick(error.response.data.message.usernick[0])
        } else {
          setErrorUserNick('')
        }
        if (error.response.data.message.email != null) {
          setErrorEmail(error.response.data.message.email[0])
        } else {
          setErrorEmail('')
        }
      }
    })
}

const updateInformation = async () => {
  console.log(form)
  const url = route('users.update', form.id)
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
        if (error.response.data.message.name != null) {
          setErrorName(error.response.data.message.name[0])
        } else {
          setErrorName('')
        }
        if (error.response.data.message.usernick != null) {
          setErrorUserNick(error.response.data.message.usernick[0])
        } else {
          setErrorUserNick('')
        }
        if (error.response.data.message.email != null) {
          setErrorEmail(error.response.data.message.email[0])
        } else {
          setErrorEmail('')
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
  <AppLayout title="USUARIOS">
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
    <FormSection v-else @submitted="sendModel">
      <template #title>
        <p v-if="props.model != null">
          Actualizar Usuario COD #{{ props.model.id }}
        </p>
        <p v-else>Registro del Usuario</p>
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
        <!-- NOMBRE -->
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
              v-model="form.name"
              @input="onValidateName"
              required
              type="text"
              id="name-users"
              class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Bonnie Green"
            />
          </div>
          <InputError class="mt-2" :message="reactives.nameError" />
        </div>
        <!-- USERNICK -->
        <div class="col-span-12 sm:col-span-12">
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
              v-model="form.usernick"
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
        <div class="col-span-12 sm:col-span-12">
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
              v-model="form.email"
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
              v-model="form.password"
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

        <div class="col-span-12 sm:col-span-12">
          <SectionBorder />
          <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">
            Roles
          </h3>
          <ul
            class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          >
            <li
              v-for="item in reactives.roles"
              :key="item.id"
              class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600"
            >
              <div class="flex items-center ps-3">
                <input
                  :id="item.name + '-' + item.id"
                  type="checkbox"
                  :value="item.name"
                  v-model="form.roles"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                />
                <label
                  :for="item.name + '-' + item.id"
                  class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >
                  {{ item.name }}
                </label>
              </div>
            </li>
          </ul>
        </div>
      </template>

      <template #actions>
        <PrimaryButton
            v-if="$page.props.user.roles.includes('super-admin')"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Guardar
        </PrimaryButton>
      </template>
    </FormSection>
  </AppLayout>
</template>
