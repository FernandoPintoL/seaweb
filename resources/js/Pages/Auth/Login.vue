<script setup>
import { ref, reactive, inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
// import { Inertia } from '@inertiajs/inertia';

const Swal = inject('$swal')

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    usernick: '',
    password: '',
    remember: false,
})

const reactives = reactive({
    isPassword: true,
    usernickError: '',
    passwordError: ''
})

const login = () => {
    form
        .transform((data) => ({
            ...data,
            remember: form.remember ? 'on' : '',
        }))
        .post(route('login'))
    /* .then(() => {
        if (!form.hasErrors) {
            form.reset('password') // Solo resetea si no hay errores
        }
    }) */
    // iniciar_session()
}

const setErrorUsernickError = (value) => {
    console.log(value)
    reactives.usernickError = value
}

const setErrorPassword = (value) => {
    console.log(value)
    reactives.passwordError = value
}

const iniciar_session = async () => {
    const url = route('appusers.loginOnApiWeb', form)
    await axios
        .post(url)
        .then((response) => {
            console.log(response)
            Swal.fire({
                position: 'top-end',
                icon: response.data.isSuccess ? 'success' : 'error',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500,
            })
            if (response.data.isSuccess) {
                setErrorPassword('')
                setErrorUsernickError('')
                form.reset()
                window.location = '/dashboard'
            }
        })
        .catch((error) => {
            console.log(error)
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: error.response.data.message,
                showConfirmButton: false,
                timer: 1500,
            })
            if (error.response.data.isMessageError) {
                if (error.response.data.messageError.usernick != null) {
                    setErrorUsernickError(error.response.data.message)
                } else {
                    setErrorUsernickError('')
                }
                if (error.response.data.messageError.password != null) {
                    setErrorPassword(error.response.data.message)
                } else {
                    setErrorPassword('')
                }

            }
        })
}

const changeInputPassword = () => {
    reactives.isPassword = !reactives.isPassword
}
</script>

<template>

    <Head title="Iniciar Sessión" />

    <div class="font-[sans-serif]">
        <div class="min-h-screen flex fle-col items-center justify-center py-6 px-4">
            <div class="grid md:grid-cols-2 items-center gap-4 max-w-6xl w-full">
                <div
                    class="border border-gray-300 rounded-lg p-6 max-w-md shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto">
                    <form class="space-y-4" @submit.prevent="login">
                        <div class="mb-8">
                            <h3 class="text-gray-800 text-3xl font-extrabold">
                                Iniciar Sessión
                            </h3>
                            <p class="text-gray-500 text-sm mt-4 leading-relaxed"></p>
                        </div>
                        <div>
                            <label class="block text-sm mb-2 dark:text-white">UserNick</label>
                            <div class="relative">
                                <input v-model="form.usernick" name="usernick" type="text" required
                                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                    placeholder="Ingresar Email" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.usernick" />
                            <!-- <InputError class="mt-2" :message="reactives.usernickError" /> -->
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Password
                            </label>
                            <div class="flex">
                                <span @click="changeInputPassword"
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i :class="reactives.isPassword ? 'fa-eye' : 'fa-eye-slash'" class="fa-solid"></i>
                                </span>
                                <input v-model="form.password" @input="onValidatePassword" name="password"
                                    :type="reactives.isPassword ? 'password' : 'text'" required
                                    class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Ingrese password" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.passwordError" />
                            <!-- <InputError class="mt-2" :message="reactives.passwordError" /> -->
                        </div>
                        <div class="!mt-8">
                            <button type="submit"
                                class="w-full shadow-xl py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Iniciar Sessión
                            </button>
                        </div>
                    </form>
                </div>
                <div class="lg:h-[400px] md:h-[300px] max-md:mt-8">
                    <img src="/assets/images/sealog.jpeg" class="object-cover h-48 w-96" alt="Dining Experience" />
                </div>
            </div>
        </div>
    </div>
</template>
