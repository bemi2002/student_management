<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  admins: Array
})

// Delete admin
const deleteAdmin = (id) => {
  if (confirm('Are you sure you want to delete this record?')) {
    router.delete(`/admins/${id}`)
  }
}

const adminList = computed(() => props.admins)
</script>

<template>
  <Head title="Admins Management" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">
        Admins Management
      </h2>
    </template>

    <div class="py-12 bg-gray-100 min-h-screen">
      <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg text-gray-900">
          
          <!-- Add new admin -->
          <div class="mb-4 flex justify-end">
            <Link
              href="/admins/create"
              class="inline-block px-4 py-2 bg-white text-gray-900 border border-gray-300 rounded hover:bg-gray-50 transition"
            >
              + New Record
            </Link>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse shadow-sm rounded-lg">
              <thead class="bg-gray-100 text-gray-700">
                <tr>
                  <th class="px-4 py-2 border">#</th>
                  <th class="px-4 py-2 border">Teacher</th>
                  <th class="px-4 py-2 border">Student</th>
                  <th class="px-4 py-2 border">Batch</th>
                  <th class="px-4 py-2 border">Course</th>
                  <th class="px-4 py-2 border">Resource</th>
                  <th class="px-4 py-2 border">Specific Course</th>
                  <th class="px-4 py-2 border text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(admin, index) in adminList"
                  :key="admin.id"
                  class="hover:bg-blue-50"
                >
                  <td class="px-4 py-2 border">{{ index + 1 }}</td>
                  <td class="px-4 py-2 border">{{ admin.teacher?.name ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border">{{ admin.student?.name ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border">{{ admin.batch?.batch ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border">{{ admin.course?.name ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border">{{ admin.resource?.text?.slice(0, 20) ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border">{{ admin.specific_course?.name ?? 'N/A' }}</td>
                  <td class="px-4 py-2 border flex justify-center space-x-2">
                    <Link
                      :href="`/admins/${admin.id}/edit`"
                      class="px-3 py-1 bg-gray-200 text-gray-900 rounded hover:bg-gray-300 transition"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteAdmin(admin.id)"
                      class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="adminList.length === 0" class="text-center py-8 text-gray-500">
            No admin records found.
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Optional: make table borders lighter */
table th, table td {
  border-color: #e5e7eb; /* Tailwind gray-200 */
}
</style>
