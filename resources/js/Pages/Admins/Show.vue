<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  admin: Object
})
</script>

<template>
  <Head title="Admin Details" />

  <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Admin Details</h2>

    <div class="grid grid-cols-1 gap-4 text-gray-700">
      <div class="flex justify-between">
        <span class="font-semibold">ID:</span>
        <span>{{ admin.id }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Teacher:</span>
        <span>{{ admin.teacher?.name ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Student:</span>
        <span>{{ admin.student?.name ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Batch:</span>
        <span>{{ admin.batch?.batch ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Course:</span>
        <span>{{ admin.course?.name ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Resource:</span>
        <span>{{ admin.resource?.text ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Specific Course:</span>
        <span>{{ admin.specific_course?.name ?? 'N/A' }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Created At:</span>
        <span>{{ new Date(admin.created_at).toLocaleString() }}</span>
      </div>

      <div class="flex justify-between">
        <span class="font-semibold">Updated At:</span>
        <span>{{ new Date(admin.updated_at).toLocaleString() }}</span>
      </div>
    </div>

    <div class="flex justify-end mt-6">
      <Link
        href="/admins"
        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400"
      >
        Back to List
      </Link>
      <Link
        :href="`/admins/${admin.id}/edit`"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 ml-2"
      >
        Edit
      </Link>
    </div>
  </div>
</template>
