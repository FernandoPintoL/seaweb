<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Banner from '@/Components/Banner.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import ApplicationMark from '@/Components/ApplicationMark.vue'
import Dropdown from '@/Components/Dropdown.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'

defineProps({
  title: String,
})

const showingNavigationDropdown = ref(false)

const switchToTeam = (team) => {
  router.put(
    route('current-team.update'),
    {
      team_id: team.id,
    },
    {
      preserveState: false,
    },
  )
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <div>
    <Head :title="title" />
    <Banner />
    <!-- ========== HEADER ========== -->
    <header
      class="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 lg:ps-[260px] dark:bg-neutral-800 dark:border-neutral-700"
    >
      <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">
        <div class="me-5 lg:me-0 lg:hidden">
          <!-- Logo -->
          <a
            :href="route('dashboard')"
            class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none focus:opacity-80"
          >
            <img
              class="h-8 me-3"
              alt="FlowBite Logo"
              id="logo-menu"
              src="/assets/images/sealog.jpeg"
            />
            <span
              class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"
            >
              SEA
            </span>
          </a>
          <!-- End Logo -->
        </div>

        <div
          class="w-full flex items-center justify-end ms-auto md:justify-between gap-x-1 md:gap-x-3"
        >
          <div class="hidden md:block">
            <!-- Search Input -->
            <div class="relative">
              <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5"
              >
                <svg
                  class="shrink-0 size-4 text-gray-400 dark:text-white/60"
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
                  <circle cx="11" cy="11" r="8" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
              </div>
              <input
                type="text"
                class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder:text-neutral-400 dark:focus:ring-neutral-600"
                placeholder="Search"
              />
              <div
                class="hidden absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-1"
              >
                <button
                  type="button"
                  class="inline-flex shrink-0 justify-center items-center size-6 rounded-full text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                  aria-label="Close"
                >
                  <span class="sr-only">Close</span>
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
                    <circle cx="12" cy="12" r="10" />
                    <path d="m15 9-6 6" />
                    <path d="m9 9 6 6" />
                  </svg>
                </button>
              </div>
              <div
                class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-3 text-gray-400"
              >
                <svg
                  class="shrink-0 size-3 text-gray-400 dark:text-white/60"
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
                  <path
                    d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"
                  />
                </svg>
                <span class="mx-1">
                  <svg
                    class="shrink-0 size-3 text-gray-400 dark:text-white/60"
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
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                  </svg>
                </span>
                <span class="text-xs">/</span>
              </div>
            </div>
            <!-- End Search Input -->
          </div>

          <div class="flex flex-row items-center justify-end gap-1">
            <button
              type="button"
              class="md:hidden size-[38px] relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
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
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
              </svg>
              <span class="sr-only">Busqueda</span>
            </button>

            <!-- Dropdown -->
            <div
              class="hs-dropdown [--placement:bottom-right] relative inline-flex"
            >
              <button
                id="hs-dropdown-account"
                type="button"
                class="size-[38px] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:text-white"
                aria-haspopup="menu"
                aria-expanded="false"
                aria-label="Dropdown"
              >
                <img
                  class="shrink-0 size-[38px] rounded-full"
                  :src="$page.props.auth.user.profile_photo_url"
                  alt="Avatar"
                />
              </button>

              <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="hs-dropdown-account"
              >
                <div
                  class="py-3 px-5 bg-gray-100 rounded-t-lg dark:bg-neutral-700"
                >
                  <p class="text-sm text-gray-500 dark:text-neutral-500">
                    Iniciado
                  </p>
                  <p
                    class="text-sm font-medium text-gray-800 dark:text-neutral-200"
                  >
                    {{ $page.props.auth.user.email }}
                  </p>
                </div>
                <div class="p-1.5 space-y-0.5">
                  <a
                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                    href="#"
                  >
                    <svg
                      class="shrink-0 size-5"
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
                      <path
                        d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                      ></path>
                      <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Inicio
                  </a>
                  <a
                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                    href="#"
                  >
                    <svg
                      class="shrink-0 mt-0.5 size-4"
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
                      <circle cx="18" cy="15" r="3" />
                      <circle cx="9" cy="7" r="4" />
                      <path d="M10 15H6a4 4 0 0 0-4 4v2" />
                      <path d="m21.7 16.4-.9-.3" />
                      <path d="m15.2 13.9-.9-.3" />
                      <path d="m16.6 18.7.3-.9" />
                      <path d="m19.1 12.2.3-.9" />
                      <path d="m19.6 18.7-.4-1" />
                      <path d="m16.8 12.3-.4-1" />
                      <path d="m14.3 16.6 1-.4" />
                      <path d="m20.7 13.8 1-.4" />
                    </svg>
                    Perfil
                  </a>
                  <form @submit.prevent="logout">
                    <DropdownLink as="button">
                      <svg
                        class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"
                        />
                        <path
                          d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"
                        />
                        <path
                          d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"
                        />
                      </svg>
                      Cerrar Sessión
                    </DropdownLink>
                  </form>
                </div>
              </div>
            </div>
            <!-- End Dropdown -->
          </div>
        </div>
      </nav>
    </header>
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <!-- Breadcrumb SOLO SALE EN VISTA DE CELULAR -->
    <div
      class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden dark:bg-neutral-800 dark:border-neutral-700"
    >
      <div class="flex items-center py-2">
        <!-- Navigation Toggle -->
        <button
          type="button"
          class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-500 dark:focus:text-neutral-500"
          aria-haspopup="dialog"
          aria-expanded="false"
          aria-controls="hs-application-sidebar"
          aria-label="Toggle navigation"
          data-hs-overlay="#hs-application-sidebar"
        >
          <span class="sr-only">Menú Navegación</span>
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
            <rect width="18" height="18" x="3" y="3" rx="2" />
            <path d="M15 3v18" />
            <path d="m8 9 3 3-3 3" />
          </svg>
        </button>
        <!-- End Navigation Toggle -->

        <!-- Breadcrumb -->
        <ol class="ms-3 flex items-center whitespace-nowrap">
          <li
            class="flex items-center text-sm text-gray-800 dark:text-neutral-400"
          >
            Application Layout
            <svg
              class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500"
              width="16"
              height="16"
              viewBox="0 0 16 16"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
              />
            </svg>
          </li>
          <li
            class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400"
            aria-current="page"
          >
            Dashboard
          </li>
        </ol>
        <!-- End Breadcrumb -->
      </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Sidebar -->
    <div
      id="hs-application-sidebar"
      class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-[260px] h-full hidden fixed inset-y-0 start-0 z-[60] bg-white border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 dark:bg-neutral-800 dark:border-neutral-700"
      role="dialog"
      tabindex="-1"
      aria-label="Sidebar"
    >
      <div class="relative flex flex-col h-full max-h-full">
        <div class="px-6 pt-4">
          <!-- Logo -->
          <a
            :href="route('dashboard')"
            class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none focus:opacity-80"
          >
            <img
              class="h-8 me-3"
              alt="FlowBite Logo"
              id="logo-menu"
              src="/assets/images/sealog.jpeg"
            />
          </a>
          <!-- End Logo -->
        </div>

        <!-- Content -->
        <div
          class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"
        >
          <nav
            class="hs-accordion-group p-3 w-full flex flex-col flex-wrap"
            data-hs-accordion-always-open
          >
            <ul class="flex flex-col space-y-1">
              <li>
                <a
                  class="flex items-center gap-x-3.5 py-2 px-2.5 bg-gray-100 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-700 dark:text-white"
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
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                  </svg>
                  Dashboard
                </a>
              </li>

              <!-- <li class="hs-accordion" id="users-accordion">
                <button
                  type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-200"
                  aria-expanded="true"
                  aria-controls="users-accordion-child"
                >
                  <i class="fas fa-building"></i>
                  CONDOMINIOS

                  <svg
                    class="hs-accordion-active:block ms-auto hidden size-4"
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
                    <path d="m18 15-6-6-6 6" />
                  </svg>

                  <svg
                    class="hs-accordion-active:hidden ms-auto block size-4"
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
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>

                <div
                  id="users-accordion-child"
                  class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                  role="region"
                  aria-labelledby="users-accordion"
                >
                  <ul
                    class="hs-accordion-group ps-8 pt-1 space-y-1"
                    data-hs-accordion-always-open
                  >
                    <li class="hs-accordion" id="users-accordion-sub-1">
                      <button
                        type="button"
                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-200"
                        aria-expanded="true"
                        aria-controls="users-accordion-sub-1-child"
                      >
                        Sub Menu 1

                        <svg
                          class="hs-accordion-active:block ms-auto hidden size-4"
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
                          <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg
                          class="hs-accordion-active:hidden ms-auto block size-4"
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
                          <path d="m6 9 6 6 6-6" />
                        </svg>
                      </button>

                      <div
                        id="users-accordion-sub-1-child"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                        role="region"
                        aria-labelledby="users-accordion-sub-1"
                      >
                        <ul class="pt-1 space-y-1">
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 1
                            </a>
                          </li>
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 2
                            </a>
                          </li>
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 3
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li class="hs-accordion" id="users-accordion-sub-2">
                      <button
                        type="button"
                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-200"
                        aria-expanded="true"
                        aria-controls="users-accordion-sub-2-child"
                      >
                        Sub Menu 2

                        <svg
                          class="hs-accordion-active:block ms-auto hidden size-4"
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
                          <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg
                          class="hs-accordion-active:hidden ms-auto block size-4"
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
                          <path d="m6 9 6 6 6-6" />
                        </svg>
                      </button>

                      <div
                        id="users-accordion-sub-2-child"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                        role="region"
                        aria-labelledby="users-accordion-sub-2"
                      >
                        <ul class="pt-1 space-y-1">
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 1
                            </a>
                          </li>
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 2
                            </a>
                          </li>
                          <li>
                            <a
                              class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                              href="#"
                            >
                              Link 3
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                  </ul>
                </div>
              </li> -->

              <!-- <li class="hs-accordion" id="account-accordion">
                <button
                  type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-200"
                  aria-expanded="true"
                  aria-controls="account-accordion-child"
                >
                  <svg
                    class="shrink-0 mt-0.5 size-4"
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
                    <circle cx="18" cy="15" r="3" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M10 15H6a4 4 0 0 0-4 4v2" />
                    <path d="m21.7 16.4-.9-.3" />
                    <path d="m15.2 13.9-.9-.3" />
                    <path d="m16.6 18.7.3-.9" />
                    <path d="m19.1 12.2.3-.9" />
                    <path d="m19.6 18.7-.4-1" />
                    <path d="m16.8 12.3-.4-1" />
                    <path d="m14.3 16.6 1-.4" />
                    <path d="m20.7 13.8 1-.4" />
                  </svg>
                  Condominio

                  <svg
                    class="hs-accordion-active:block ms-auto hidden size-4"
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
                    <path d="m18 15-6-6-6 6" />
                  </svg>

                  <svg
                    class="hs-accordion-active:hidden ms-auto block size-4"
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
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>

                <div
                  id="account-accordion-child"
                  class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                  role="region"
                  aria-labelledby="account-accordion"
                >
                  <ul class="ps-8 pt-1 space-y-1">
                    <li>
                      <a
                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                        :href="route('condominio.index')"
                      >
                        Listado
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="hs-accordion" id="projects-accordion">
                <button
                  type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-200"
                  aria-expanded="true"
                  aria-controls="projects-accordion-child"
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
                    <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                  </svg>
                  Projects

                  <svg
                    class="hs-accordion-active:block ms-auto hidden size-4"
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
                    <path d="m18 15-6-6-6 6" />
                  </svg>

                  <svg
                    class="hs-accordion-active:hidden ms-auto block size-4"
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
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>

                <div
                  id="projects-accordion-child"
                  class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                  role="region"
                  aria-labelledby="projects-accordion"
                >
                  <ul class="ps-8 pt-1 space-y-1">
                    <li>
                      <a
                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                        href="#"
                      >
                        Link 1
                      </a>
                    </li>
                    <li>
                      <a
                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                        href="#"
                      >
                        Link 2
                      </a>
                    </li>
                    <li>
                      <a
                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:bg-neutral-800 dark:text-neutral-200"
                        href="#"
                      >
                        Link 3
                      </a>
                    </li>
                  </ul>
                </div>
              </li> -->

              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('condominio.index')"
                >
                  <i class="fas fa-building"></i>
                  Condominio
                </a>
              </li>

              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('ingreso.index')"
                >
                  <i class="fa-solid fa-calendar-days"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Visitas / Ingresos
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('habitante.index')"
                >
                  <svg
                    class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 18"
                  >
                    <path
                      d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"
                    />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Residente / Huespedes
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('visitante.index')"
                >
                  <i class="fas fa-walking"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Visitantes
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('tipodocumento.index')"
                >
                  <i class="fa-solid fa-id-card-clip"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Tipo Documento
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('tipovisita.index')"
                >
                  <i class="fa-solid fa-person-military-to-person"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Tipo Visita
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('vehiculo.index')"
                >
                  <i class="fa-solid fa-car"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Vehiculo
                  </span>
                </a>
              </li>
              <li>
                <a
                  class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-200 dark:hover:text-neutral-300"
                  :href="route('vivienda.index')"
                >
                  <i class="fa-solid fa-car"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">
                    Viviendas
                  </span>
                </a>
              </li>
              <li>
                <form @submit.prevent="logout">
                  <div
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                  >
                    <svg
                      class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"
                      />
                      <path
                        d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"
                      />
                      <path
                        d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"
                      />
                    </svg>
                    <button type="submit">
                      <span class="flex-1 ms-3 whitespace-nowrap">
                        Cerrar Sessión
                      </span>
                    </button>
                  </div>
                </form>
              </li>
            </ul>
          </nav>
        </div>
        <!-- End Content -->
      </div>
    </div>
    <!-- End Sidebar -->

    <!-- Content -->
    <div class="w-full lg:ps-64">
      <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
        <!-- Card COMPELETE TABLE -->
        <slot />
        <!-- End Card -->
      </div>
    </div>
    <!-- End Content -->
  </div>
</template>
<!-- <style>
.backimage {
  background-image: url('~@/assets/images/bg-01.jpg');
}
</style> -->
