<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Enrollments
      </h2>
    </template>

    <div class="p-4">
      <!-- Header: Create Button + Search -->
      <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
        <Link
          :href="route('enrollmentss.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Create Enrollment
        </Link>

        <input
          v-model="search"
          type="text"
          placeholder="Search by title..."
          class="border p-2 rounded w-full md:w-64"
        />
      </div>

      <!-- Enrollment Table -->
      <table class="w-full border-collapse border text-sm">
        <thead>
          <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Title</th>
            <th class="p-2 border">Description</th>
            <th class="p-2 border">Course</th>
            <th class="p-2 border">Course Type</th>
            <th class="p-2 border">Company Address</th>
            <th class="p-2 border">Student Capacity</th>
            <th class="p-2 border">Amount</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border">Telegram</th>
            <th class="p-2 border">Enrollment Date</th>
            <th class="p-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="e in filteredEnrollments"
            :key="e.id"
            class="border hover:bg-gray-50"
          >
            <td class="p-2 border">{{ e.id }}</td>
            <td class="p-2 border">{{ e.title || '-' }}</td>
            <td class="p-2 border">{{ e.description || '-' }}</td>
            <td class="p-2 border">{{ e.course?.course_name || 'N/A' }}</td>
            <td class="p-2 border">{{ e.course_type?.course_type || 'N/A' }}</td>
            <td class="p-2 border">{{ e.company_address?.company_name || 'N/A' }}</td>
            <td class="p-2 border">{{ e.student_capacity ?? 0 }}</td>
            <td class="p-2 border">${{ e.amount_to_be_paid ?? 0 }}</td>
            <td class="p-2 border">{{ e.completion_status || 'Not Started' }}</td>
            <td class="p-2 border">{{ e.telegram_link || '-' }}</td>
            <td class="p-2 border">{{ e.enrollment_date || '-' }}</td>
            <td class="p-2 border flex gap-2">
              <Link :href="route('enrollmentss.show', e.id)" class="text-blue-600 hover:underline">View</Link>
              <Link :href="route('enrollmentss.edit', e.id)" class="text-green-600 hover:underline">Edit</Link>
              <button @click="confirmDelete(e.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
          <tr v-if="filteredEnrollments.length === 0">
            <td class="p-2 border text-center" colspan="12">
              No enrollments found.
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Delete Confirmation Modal -->
      <div
        v-if="showDelete"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <div class="bg-white p-6 rounded shadow-lg w-96">
          <p class="mb-4 text-lg font-semibold">
            Are you sure you want to delete this enrollment?
          </p>
          <div class="flex justify-end gap-4">
            <button
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
              @click="deleteEnrollment"
            >
              Yes
            </button>
            <button
              class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
              @click="showDelete = false"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  enrollments: { type: Array, default: () => [] }
})

const search = ref('')
const showDelete = ref(false)
const deleteId = ref(null)

// Local reactive copy of enrollments
const localEnrollments = ref([...props.enrollments])

// Filtered enrollments by title
const filteredEnrollments = computed(() => {
  return localEnrollments.value.filter(e =>
    e.title?.toLowerCase().includes(search.value.toLowerCase())
  )
})

function confirmDelete(id) {
  deleteId.value = id
  showDelete.value = true
}

function deleteEnrollment() {
  if (deleteId.value) {
    router.delete(route('enrollmentss.destroy', deleteId.value), {
      onSuccess: () => {
        localEnrollments.value = localEnrollments.value.filter(e => e.id !== deleteId.value)
        showDelete.value = false
        deleteId.value = null
      }
    })
  }
}
</script>
