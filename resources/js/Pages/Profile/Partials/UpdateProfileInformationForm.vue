<script setup>
import { ref, inject, reactive, onMounted, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const Swal = inject('$swal')

const props = defineProps({
  user: Object,
})

const form = useForm({
  // _method: 'PUT',
  id: props.user.id,
  name: props.user.name,
  email: props.user.email,
  usernick: props.user.usernick,
  photo: null,
})

const verificationLinkSent = ref(null)
const photoPreview = ref(null)
const photoInput = ref(null)

const reactives = reactive({
  isLoad: false,
  nameError: '',
  emailError: '',
  userNickError: '',
})

const sendModel = async () => {
  if (
    reactives.nameError.length != 0 ||
    reactives.emailError.length != 0 ||
    reactives.userNickError.length != 0
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
      updateInformation()
    }
  })
}

const updateInformation = async () => {
  console.log(form)
  const url = route('user.updateinformacion', form.id)
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

const onValidateName = (e) => {
  if (!/^[A-Za-z\s]{5,}$/.test(e.target.value)) {
    reactives.nameError =
      'El campo debe tener al menos 5 caracteres y solo pueden ser letras.'
  } else {
    reactives.nameError = ''
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
  }
}

const setErrorEmail = (value) => {
  console.log(value)
  reactives.emailError = value
}

const setErrorUserNick = (value) => {
  console.log(value)
  reactives.userNickError = value
}

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.post(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => clearPhotoFileInput(),
  })
}

const sendEmailVerification = () => {
  verificationLinkSent.value = true
}

const selectNewPhoto = () => {
  photoInput.value.click()
}

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0]

  if (!photo) return

  const reader = new FileReader()

  reader.onload = (e) => {
    photoPreview.value = e.target.result
  }

  reader.readAsDataURL(photo)
}

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}
</script>

<template>
  <FormSection @submitted="sendModel">
    <template #title>
      Perfil Información
    </template>

    <template #description>
      Actualice la información del perfil y la dirección de correo electrónico
      de su cuenta.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div
        v-if="$page.props.jetstream.managesProfilePhotos"
        class="col-span-6 sm:col-span-4"
      >
        <!-- Profile Photo File Input -->
        <input
          id="photo"
          ref="photoInput"
          type="file"
          class="hidden"
          @change="updatePhotoPreview"
        />

        <InputLabel for="photo" value="Photo" />

        <!-- Current Profile Photo -->
        <div v-show="!photoPreview" class="mt-2">
          <img
            :src="user.profile_photo_url"
            :alt="user.name"
            class="rounded-full h-20 w-20 object-cover"
          />
        </div>

        <!-- New Profile Photo Preview -->
        <div v-show="photoPreview" class="mt-2">
          <span
            class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
            :style="'background-image: url(\'' + photoPreview + '\');'"
          />
        </div>

        <SecondaryButton
          class="mt-2 me-2"
          type="button"
          @click.prevent="selectNewPhoto"
        >
          Select A New Photo
        </SecondaryButton>

        <SecondaryButton
          v-if="user.profile_photo_path"
          type="button"
          class="mt-2"
          @click.prevent="deletePhoto"
        >
          Remove Photo
        </SecondaryButton>

        <InputError :message="form.errors.photo" class="mt-2" />
      </div>

      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="name" value="Nombre" />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          required
          autocomplete="name"
          @input="onValidateName"
        />
        <InputError :message="reactives.nameError" class="mt-2" />
      </div>

      <!-- Email -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autocomplete="username"
          @input="onValidateEmail"
        />
        <InputError :message="reactives.emailError" class="mt-2" />

        <!-- <div
          v-if="
            $page.props.jetstream.hasEmailVerification &&
            user.email_verified_at === null
          "
        >
          <p class="text-sm mt-2">
            Your email address is unverified.

            <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              @click.prevent="sendEmailVerification"
            >
              Click here to re-send the verification email.
            </Link>
          </p>

          <div
            v-show="verificationLinkSent"
            class="mt-2 font-medium text-sm text-green-600"
          >
            A new verification link has been sent to your email address.
          </div>
        </div> -->
      </div>
      <!-- USERNICK -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="usernick" value="User Nick" />
        <TextInput
          id="usernick"
          v-model="form.usernick"
          type="text"
          class="mt-1 block w-full"
          required
          autocomplete="usernick"
          @input="onValidateUserNick"
        />
        <InputError :message="reactives.userNickError" class="mt-2" />
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
        Save
      </PrimaryButton>
    </template>
  </FormSection>
</template>
