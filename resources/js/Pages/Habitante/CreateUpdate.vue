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
    viviendas: {
        type: Array,
        default: [],
    },
    tipo_documento: {
        type: Array,
        default: [],
    },
    residentes: {
        type: Array,
        default: [],
    },
    crear: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    editar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    eliminar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
})

onMounted(() => {
    reactives.list_vivienda = props.viviendas
    reactives.list_typedocument = props.tipo_documento
    reactives.list_habitante = props.residentes
    // queryDocument('')
    // queryHabitante('')
    console.log(reactives.list_vivienda);
    //   queryVivienda('')
})

const form = useForm({
    id: props.model != null ? props.model.id : '',
    /*DATOS DEL PERFIL*/
    perfil: {
        name: props.model != null ? props.model.perfil.name : '',
        email: props.model != null ? props.model.perfil.email : '',
        celular: props.model != null ? props.model.perfil.celular : '',
        nroDocumento: props.model != null ? props.model.perfil.nroDocumento : '',
        tipo_documento_id:
            props.model != null ? props.model.perfil.tipo_documento_id : 0,
    },
    /* datos de habitante */
    isDuenho: props.model != null ? props.model.isDuenho : true,
    isDependiente: props.model != null ? props.model.isDependiente : false,
    responsable_id: props.model != null ? props.model.responsable_id : 0,
    perfil_id: props.model != null ? props.model.perfil_id : 0,
    vivienda_id: props.model != null ? props.model.vivienda_id : 0,
    isMobile: true,
})

const reactives = reactive({
    isLoad: false,
    list_typedocument: [],
    list_habitante: [],
    list_vivienda: [],
    nameError: '',
    celularError: '',
    nroDocumentoError: '',
    emailError: '',
})

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
        reactives.celularError = 'Número de celular incorrecto'
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

const setErrorEmail = (value) => {
    console.log(value[0])
    reactives.emailError = value[0]
}

const setErrorNroDocumento = (value) => {
    console.log(value[0])
    reactives.nroDocumentoError = value[0]
}

const sendModel = async () => {
    if (
        reactives.nameError.length != 0 ||
        reactives.emailError.length != 0 ||
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
    if (form.isDuenho) {
        form.responsable_id = 0
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
        reactives.isLoad = true
        if (result.isConfirmed) {
            if (props.model != null) {
                updateInformation()
            } else {
                createInformacion()
            }
        }
        reactives.isLoad = false
    })
}

const createInformacion = async () => {
    console.log(form)
    form.isDependiente = !form.isDuenho
    const url = route('habitante.store', form)
    await axios
        .post(url)
        .then((response) => {
            // console.log(response)
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
                if (error.response.data.message.email != null) {
                    setErrorEmail(error.response.data.message.email)
                } else {
                    setErrorEmail('')
                }
            }
        })
}

const updateInformation = async () => {
    form.isDependiente = !form.isDuenho
    const url = route('habitante.update', form.id)
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
                if (error.response.data.message.email != null) {
                    setErrorEmail(error.response.data.message.email)
                } else {
                    setErrorEmail('')
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
                        // form.perfil.tipo_documento_id = reactives.list_typedocument[0].id
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
/// LISTAR LOS HABITANTES QUE SON DUEÑOS
const queryHabitante = async (consulta) => {
    changeLoad(true)
    const url = route('habitante.query', { query: consulta })
    await axios
        .post(url)
        .then((response) => {
            if (response.data.isSuccess) {
                reactives.list_habitante = response.data.data
                if (reactives.list_habitante.length > 0) {
                    if (props.model != null) {
                        form.responsable_id = props.model.responsable_id
                    } else {
                        // form.responsable_id = reactives.list_habitante[0].id
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

const queryVivienda = async (consulta) => {
    changeLoad(true)
    const url = route('vivienda.query', { query: consulta })
    await axios
        .post(url)
        .then((response) => {
            console.log(response)
            if (response.data.isSuccess) {
                reactives.list_vivienda = response.data.data
                if (reactives.list_vivienda.length > 0) {
                    if (props.model != null) {
                        form.vivienda_id = props.model.vivienda_id
                    } else {
                        // form.vivienda_id = reactives.list_vivienda[0].id
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
    <AppLayout title="RESIDENTE">
        <div v-if="reactives.isLoad">
            <Loader />
        </div>
        <FormSection v-else @submitted="sendModel">
            <template #title>
                <p v-if="props.model != null">
                    Actualizar Residente COD #{{ props.model.id }}
                </p>
                <p v-else>Registro del Residente</p>
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
                <!-- Name -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombre
                    </label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                            </svg>
                        </span>
                        <input v-model="form.perfil.name" @input="onValidateName" required type="text" id="name"
                            class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Bonnie Green" />
                    </div>
                    <InputError class="mt-2" :message="reactives.nameError" />
                </div>
                <!-- Email -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email
                    </label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            <i class="fa-solid fa-at"></i>
                        </span>
                        <input v-model="form.perfil.email" type="email" id="email"
                            class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="pintolinofernando@gmail.com" />
                    </div>
                    <InputError class="mt-2" :message="reactives.emailError" />
                </div>
                <!-- Celular -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="celular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Celular
                    </label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            <i class="fa-solid fa-square-phone"></i>
                        </span>
                        <input v-model="form.perfil.celular" @input="onValidateCelular" type="tel" id="celular"
                            class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="+59173682145" />
                    </div>
                    <InputError class="mt-2" :message="reactives.celularError" />
                </div>
                <!-- TIPO DE DOCUMENTO -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="tipo-doc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Seleccione un tipo de documento
                    </label>

                    <select id="tipo-doc"
                        class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        v-model="form.perfil.tipo_documento_id">
                        <option v-for="item in reactives.list_typedocument" :key="item.id" :value="item.id">
                            {{ item.id }} : {{ item.sigla }} : {{ item.detalle }}
                        </option>
                    </select>
                </div>
                <!-- NRO DOCUMENTO -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="nroDocumento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Numero de Documentacion
                    </label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input v-model="form.perfil.nroDocumento" @input="onValidateNroDocumento" type="text"
                            id="nroDocumento"
                            class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="8887777-EXT" />
                    </div>
                    <InputError class="mt-2" :message="reactives.nroDocumentoError" />
                </div>
                <!-- Es dueño -->
                <div class="col-span-12 sm:col-span-12">
                    <div class="flex items-center">
                        <input type="checkbox" id="ingreso-is-duenho"
                            class="relative shrink-0 w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-green-600 checked:border-green-600 focus:checked:border-green-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-green-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-green-200"
                            v-model="form.isDuenho" />
                        <label for="ingreso-is-duenho" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                            Es Dueño?
                        </label>
                    </div>
                </div>
                <!-- <p>{{ react.list_habitante }}</p> -->
                <!-- Responsable a cargo si no es dueño -->
                <div v-if="!form.isDuenho" class="col-span-12 sm:col-span-12">
                    <label for="select-resd" class="mb-2 text-sm font-medium">
                        <i class="fa-solid fa-people-roof"></i>
                        Seleccione un residente
                    </label>
                    <div class="flex justify-start">
                        <select id="select-resd"
                            class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            v-model="form.responsable_id">
                            <option class="w-full" v-for="item in reactives.list_habitante" :key="item.id"
                                :value="item.id">
                                CI: {{ item.nroDocumento }} / Nombre:
                                {{ item.name }} / Condominio: {{ item.propietario }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- NUMERO DE VIVIENDA -->
                <div class="col-span-12 sm:col-span-12">
                    <label for="select-vnds" class="mb-2 text-sm font-medium">
                        <i class="fa-solid fa-house"></i>
                        Seleccione una vivienda
                    </label>
                    <select id="select-vnds"
                        class="custom bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        v-model="form.vivienda_id">
                        <option v-for="item in reactives.list_vivienda" :key="item.id" :value="item.id">
                            ID: {{ item.id }} / Vivienda:
                            {{ item.nroVivienda }} / Condominio: {{ item.condominio.propietario }}
                        </option>
                    </select>
                </div>
            </template>

            <template #actions>
                <PrimaryButton v-if="
                    $page.props.user.roles.includes('super-admin') ||
                    $page.props.user.permissions.includes('HABITANTE.CREAR') ||
                    $page.props.user.permissions.includes('HABITANTE.EDITAR') ||
                    $page.props.user.permissions_roles.includes('HABITANTE.CREAR') ||
                    $page.props.user.permissions_roles.includes('HABITANTE.EDITAR')
                " :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Guardar
                </PrimaryButton>
            </template>
        </FormSection>
    </AppLayout>
</template>
<!-- <script>
$(document).ready(function () {
    $('.custom').select2()
})
</script> -->
