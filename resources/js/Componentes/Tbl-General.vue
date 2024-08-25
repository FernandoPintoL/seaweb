<template>
  <!-- Table -->
  <div class="overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
      <thead class="bg-gray-50 dark:bg-neutral-800">
        <slot name="tbl-header" />
      </thead>

      <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
        <slot name="tbl-body" />
      </tbody>
    </table>
  </div>
  <!-- End Table -->
</template>
