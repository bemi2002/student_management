<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  teachers: Array
})

const form = useForm({})

function deleteTeacher(id) {
  if (confirm('Are you sure you want to delete this teacher?')) {
    form.delete(`/teachers/${id}`, {
      preserveScroll: true,
      onSuccess: () => console.log('Teacher deleted successfully'),
    })
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Teachers" />

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Teachers List</h2>
        <Link
          href="/teachers/create"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
          + Add Teacher
        </Link>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full border divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">ID</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">First Name</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Last Name</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Phone</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Specialization</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Availability Days</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Preferred Start</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Preferred End</th>
              <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Actions</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="t in teachers"
              :key="t.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.id }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.first_name }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.last_name }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.email }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.phone || '-' }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.specialization || '-' }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.availability_days || 'N/A' }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.preferred_start_time || '-' }}</td>
              <td class="px-4 py-2 text-sm text-gray-700">{{ t.preferred_end_time || '-' }}</td>
              <td class="px-4 py-2 text-right text-sm font-medium flex justify-end gap-3">
                <!-- View -->
                <Link
                  :href="`/teachers/${t.id}`"
                  class="text-green-600 hover:text-green-900 font-semibold"
                >
                  View
                </Link>

                <!-- Edit -->
                <Link
                  :href="`/teachers/${t.id}/edit`"
                  class="text-blue-600 hover:text-blue-900 font-semibold"
                >
                  Edit
                </Link>

                <!-- Delete -->
                <button
                  @click="deleteTeacher(t.id)"
                  class="text-red-600 hover:text-red-900 font-semibold"
                >
                  Delete
                </button>
              </td>
            </tr>

            <tr v-if="teachers.length === 0">
              <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                No teachers found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
