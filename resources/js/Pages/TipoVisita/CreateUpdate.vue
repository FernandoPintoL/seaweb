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
import Loader from '@/Componentes/Loader.vue'
import moment from 'moment-timezone'
import Alert from '@/Componentes/Alerts.vue'

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
    form.sigla = e.target.value.toUpperCase()
  }
}

const onValidateDetalle = (e) => {
  form.detalle = e.target.value.toUpperCase()
}

const setErrorSigla = (value) => {
  console.log(value[0])
  reactives.siglaError = value[0]
}

const createInformacion = async () => {
  const url = route('tipovisita.store', form)
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
      if (response.data.success) {
        form.reset()
      }
      /*messages.eDetalle = []
      messages.eSigla = []*/
    })
    .catch((error) => {
      console.log(error.response)
      if (error.response.data.isMessageError) {
        console.log(error.response.data.message)
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Verifique el formulario',
          showConfirmButton: false,
          timer: 1500,
        })
        if (error.response.data.isMessageError) {
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
  const url = route('tipovisita.update', props.model.id)
  await axios
    .put(url, form)
    .then((response) => {
      console.log(response.data)
      Swal.fire({
        position: 'top-end',
        icon: response.data.isSuccess ? 'success' : 'error',
        title: response.data.message,
        showConfirmButton: false,
        timer: 1500,
      })
      /*messages.eDetalle = []
      messages.eSigla = []*/
    })
    .catch((error) => {
      console.log(error.response)
      if (error.response.data.isMessageError) {
        console.log(error.response.data.message)
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Verifique el formulario',
          showConfirmButton: false,
          timer: 1500,
        })
        if (error.response.data.isMessageError) {
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
  return moment.tz(fechaData, 'America/La_Paz').format('YYYY-MM-DD HH:MM a')
}
</script>

<template>
  <AppLayout title="TIPO DE VISITAS">
    <div v-if="reactives.isLoad">
      <Loader />
    </div>
    <FormSection v-else @submitted="sendModel">
      <template #title>
        <p v-if="props.model != null">Tipo Visita COD #{{ props.model.id }}</p>
        <p v-else>Registrar Tipo Visita</p>
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
            @input="onValidateDetalle"
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
            v-if="$page.props.user.roles.includes('super-admin') ||
            $page.props.user.permissions.includes('TIPO_VISITA.CREAR') ||
            $page.props.user.permissions.includes('TIPO_VISITA.EDITAR') ||
            $page.props.user.permissions_roles.includes('TIPO_VISITA.CREAR') ||
            $page.props.user.permissions_roles.includes('TIPO_VISITA.EDITAR')
        "
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Guardar
        </PrimaryButton>
      </template>
    </FormSection>
  </AppLayout>
</template>
