<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  teacher: Object,
  availableEnrollments: Array, // Pass from controller: enrollments that can be assigned
})

const page = usePage()
const user = computed(() => page.props.auth?.user || {})

// Role-based permissions
const canEdit = computed(() => ['Super-Admin', 'Admin'].includes(user.value.role))
const canDelete = computed(() => ['Super-Admin', 'Admin'].includes(user.value.role))
const canAssign = computed(() => ['Super-Admin', 'Admin'].includes(user.value.role))

// Delete teacher
function deleteTeacher() {
  if (confirm(`Are you sure you want to delete ${props.teacher.first_name} ${props.teacher.last_name}?`)) {
    router.delete(`/teachers/${props.teacher.id}`)
  }
}

// Assign / Unassign Enrollment
const selectedEnrollment = ref('')

function assignEnrollment() {
  if (!selectedEnrollment.value) return alert('Please select an enrollment.')
  router.post(`/teachers/${props.teacher.id}/assign`, {
    enrollment_id: selectedEnrollment.value,
  })
}

function unassignEnrollment(enrollmentId) {
  const reason = prompt('Are you sure you want to unassign this enrollment? Enter reason:')
  if (!reason) return
  router.post(`/teachers/${props.teacher.id}/unassign`, {
    enrollment_id: enrollmentId,
    reason,
  })
}

// Helper
function formatDate(dateStr) {
  if (!dateStr) return 'N/A'
  return new Date(dateStr).toLocaleDateString()
}
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="`${teacher.first_name} ${teacher.last_name}`" />

    <div class="container mx-auto px-6 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ teacher.first_name }} {{ teacher.last_name }}</h1>
        <div class="space-x-2">
          <Link
            href="/teachers"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition"
          >
            Back
          </Link>
          <Link
            v-if="canEdit"
            :href="`/teachers/${teacher.id}/edit`"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Edit
          </Link>
          <button
            v-if="canDelete"
            @click="deleteTeacher"
            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
          >
            Delete
          </button>
        </div>
      </div>

      <!-- Teacher Info -->
      <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Profile Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
          
          <p><span class="font-semibold">Email:</span> {{ teacher.email }}</p>
          <p><span class="font-semibold">Phone:</span> {{ teacher.phone || 'N/A' }}</p>
          <p><span class="font-semibold">Specialization:</span> {{ teacher.specialization || 'N/A' }}</p>

          <p><span class="font-semibold">Availability Days:</span> {{ teacher.availability_days || 'N/A' }}</p>
          <p><span class="font-semibold">Preferred Start:</span> {{ teacher.preferred_start_time || '-' }}</p>
          <p><span class="font-semibold">Preferred End:</span> {{ teacher.preferred_end_time || '-' }}</p>
        </div>
      </div>

      <!-- Assigned Enrollments -->
      <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Assigned Enrollments</h2>
        <div v-if="teacher.enrollments?.length">
          <ul class="divide-y divide-gray-200">
            <li v-for="enrollment in teacher.enrollments" :key="enrollment.id" class="py-2 flex justify-between items-center">
              <span>{{ enrollment.course?.name || 'Unknown Course' }}</span>
              <button
                v-if="canAssign"
                @click="unassignEnrollment(enrollment.id)"
                class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm"
              >
                Unassign
              </button>
            </li>
          </ul>
        </div>
        <p v-else class="text-gray-500">No enrollments assigned.</p>
      </div>

      <!-- Assign New Enrollment -->
      <div v-if="canAssign && availableEnrollments.length" class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Assign Enrollment</h2>
        <div class="flex gap-2">
          <select v-model="selectedEnrollment" class="border rounded-lg px-3 py-2 flex-1">
            <option value="">Select Enrollment</option>
            <option v-for="e in availableEnrollments" :key="e.id" :value="e.id">
              {{ e.course?.name || 'N/A' }}
            </option>
          </select>
          <button
            @click="assignEnrollment"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Assign
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
