<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Login from '@/Pages/Auth/Login.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      remember: form.remember ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    })
}
</script>

<template>
  <Head title="Iniciar Sessión" />
  <!-- Features -->
  <div class="max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div
      class="min-h-[35vh] md:min-h-[75vh] backimage bg-center bg-no-repeat relative rounded-xl"
    >
      <div
        class="absolute bottom-0 start-0 end-0 max-w-xs text-center mx-auto p-6 md:start-auto md:text-start md:mx-0"
      >
        <!-- Card -->
        <div
          class="px-5 py-4 inline-block bg-white rounded-lg md:p-7 dark:bg-neutral-800"
        >
          <div class="hidden md:block">
            <h3
              class="text-lg font-bold text-gray-800 sm:text-2xl dark:text-neutral-200"
            >
              SEA
            </h3>
            <p class="mt-2 text-gray-800 dark:text-neutral-200">
              SEGURIDAD ELECTRONICA AUTOMATIZADA TECNOLOGICA
            </p>
          </div>

          <div class="md:mt-16">
            <a
              class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-gray-500 focus:outline-none focus:text-gray-500 dark:text-white dark:hover:text-neutral-400 dark:focus:text-neutral-400"
              :href="route('dashboard')"
            >
              <svg
                class="shrink-0 size-4"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <polygon points="5 3 19 12 5 21 5 3" />
              </svg>
              Ir a la página principal
            </a>
          </div>
        </div>
        <!-- End Card -->
      </div>
    </div>
  </div>
  <!-- End Features -->
</template>

<style scoped>
.backimage {
  background-image: url('/assets/images/bg-01.jpg');
}
</style>
