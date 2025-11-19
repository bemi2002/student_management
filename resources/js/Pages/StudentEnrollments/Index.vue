<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({ enrollments: Array })
</script>

<template>
  <Head title="Student Enrollments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Enrollments</h2>
    </template>

    <div class="p-8 bg-gray-100 min-h-screen">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Student Enrollments</h1>
        <Link
          href="/student-enrollments/create"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
          + New Enrollment
        </Link>
      </div>

      <table class="w-full bg-white shadow rounded-lg">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Student</th>
            <th class="p-3 text-left">Course</th>
            <th class="p-3 text-left">Specific Course</th>
            <th class="p-3 text-left">Enrollment ID</th>
            <th class="p-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="e in enrollments" :key="e.id" class="border-t hover:bg-gray-50">
            <td class="p-3">{{ e.id }}</td>
            <td class="p-3">{{ e.student?.name ?? 'N/A' }}</td>
            <td class="p-3">{{ e.course?.name ?? 'N/A' }}</td>
            <td class="p-3">{{ e.specific_course?.name ?? 'N/A' }}</td>
            <td class="p-3">{{ e.enrollment_id }}</td>
            <td class="p-3 text-right space-x-2">
              <Link
                :href="`/student-enrollments/${e.id}/edit`"
                class="text-blue-600 hover:underline"
              >
                Edit
              </Link>
              <Link
                :href="`/student-enrollments/${e.id}`"
                method="delete"
                as="button"
                class="text-red-600 hover:underline"
              >
                Delete
              </Link>
            </td>
          </tr>
        </tbody>
      </table>

      <div
        v-if="enrollments.length === 0"
        class="text-center py-8 text-gray-500"
      >
        No enrollments found.
      </div>
    </div>
  </AuthenticatedLayout>
</template>
