<script setup></script>
<template>
  <div class="py-3 px-4">
    <div class="relative max-w-xs">
      <label class="sr-only">Search</label>
      <input
        type="text"
        name="hs-table-with-pagination-search"
        id="hs-table-with-pagination-search"
        class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
        placeholder="Search for items"
      />
      <div
        class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3"
      >
        <svg
          class="size-4 text-gray-400 dark:text-neutral-500"
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
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.3-4.3"></path>
        </svg>
      </div>
    </div>
  </div>
  <!-- Table -->
  <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
    <thead class="bg-gray-50 dark:bg-neutral-800">
      <slot name="tbl-header" />
    </thead>

    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
      <slot name="tbl-body" />
    </tbody>
  </table>
  <!-- End Table -->
</template>
