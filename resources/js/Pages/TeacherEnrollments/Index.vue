<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
  enrollments: { type: Array, default: () => [] }
})

// Safe delete function
function deleteEnrollment(id) {
  if (confirm('Are you sure you want to delete this enrollment?')) {
    router.delete(`/teacher-enrollments/${id}`)
  }
}
</script>

<template>
  <Head title="Teacher Enrollments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teacher Enrollments</h2>
    </template>

    <div class="max-w-6xl mx-auto p-8 bg-white rounded-xl shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Teacher Enrollments</h2>
        <Link
          href="/teacher-enrollments/create"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          + Create Enrollment
        </Link>
      </div>

      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Teacher</th>
            <th class="border px-4 py-2">Course</th>
            <th class="border px-4 py-2">Specific Course</th>
            <th class="border px-4 py-2">Enrollment ID</th>
            <th class="border px-4 py-2">Created At</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="enrollment in enrollments"
            :key="enrollment.id"
            class="hover:bg-gray-50"
          >
            <td class="border px-4 py-2">{{ enrollment.id }}</td>
            <td class="border px-4 py-2">{{ enrollment.teacher?.name || 'N/A' }}</td>
            <td class="border px-4 py-2">{{ enrollment.course?.name || 'N/A' }}</td>
            <td class="border px-4 py-2">{{ enrollment.specificCourse?.name || 'N/A' }}</td>
            <td class="border px-4 py-2">{{ enrollment.enrollment_id }}</td>
            <td class="border px-4 py-2">
              {{ new Date(enrollment.created_at).toLocaleDateString() }}
            </td>
            <td class="border px-4 py-2 flex gap-2">
              <Link
                :href="`/teacher-enrollments/${enrollment.id}/edit`"
                class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
              >
                Edit
              </Link>
              <button
                @click="deleteEnrollment(enrollment.id)"
                class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="enrollments.length === 0">
            <td colspan="7" class="border px-4 py-2 text-center text-gray-500">
              No enrollments found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>
