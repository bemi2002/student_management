<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  specificCourses: Array
})

// Delete with confirmation
function deleteSpecificCourse(id) {
  if (confirm('Are you sure you want to delete this specific course?')) {
    router.delete(`/specific-courses/${id}`)
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Specific Courses" />

    <div class="p-8 bg-gray-100 min-h-screen">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Specific Courses</h1>
        <Link
          href="/specific-courses/create"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700"
        >
          + New Specific Course
        </Link>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full border border-gray-200">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="p-3 text-left">ID</th>
              <th class="p-3 text-left">Course</th>
              <th class="p-3 text-left">Teacher</th>
              <th class="p-3 text-left">Batch</th>
              <th class="p-3 text-left">Code</th>
              <th class="p-3 text-left">Description</th>
              <th class="p-3 text-left">Difficulty</th>
              <th class="p-3 text-left">Resources</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="sc in props.specificCourses"
              :key="sc.id"
              class="border-t hover:bg-gray-50 transition"
            >
              <td class="p-3">{{ sc.id }}</td>
              <td class="p-3">{{ sc.course?.courses_name ?? '-' }}</td>
              <td class="p-3">{{ sc.teacher ? sc.teacher.first_name + ' ' + sc.teacher.last_name : '-' }}</td>
              <td class="p-3">{{ sc.batch?.name ?? '-' }}</td>
              <td class="p-3">{{ sc.specific_courses_code ?? '-' }}</td>
              <td class="p-3">{{ sc.description ?? '-' }}</td>
              <td class="p-3">{{ sc.difficulty_level ?? '-' }}</td>
              <td class="p-3">{{ sc.resources ?? '-' }}</td>
              <td class="p-3 text-right space-x-2">
                <Link
                  :href="`/specific-courses/${sc.id}/edit`"
                  class="text-blue-600 hover:underline"
                >
                  Edit
                </Link>
                <button
                  @click="deleteSpecificCourse(sc.id)"
                  class="text-red-600 hover:underline"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- No specific courses -->
        <div v-if="!props.specificCourses.length" class="text-center py-8 text-gray-500">
          No specific courses found.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
