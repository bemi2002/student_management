<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ student.full_name }}
      </h2>
    </template>

    <div class="container mx-auto px-6 py-8">
      <!-- Header Actions -->
      <div class="flex justify-between items-center mb-6">
        <div></div>
        <div class="space-x-2">
          <Link :href="route('students.index')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
            Back
          </Link>

          <Link v-if="canEdit" :href="route('students.edit', student.id)"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Edit
          </Link>

          <button v-if="canDelete" @click="deleteStudent"
            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
            Delete
          </button>
        </div>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash?.success"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ $page.props.flash.error }}
      </div>

      <!-- Student Info -->
      <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Profile Information</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
          <p><span class="font-semibold">Full Name:</span> {{ student.full_name }}</p>
          <p><span class="font-semibold">Email:</span> {{ student.email }}</p>
          <p><span class="font-semibold">Phone:</span> {{ student.contact_phone || 'N/A' }}</p>
          <p><span class="font-semibold">Location:</span> {{ student.location?.name || 'No Location' }}</p>
          <p>
            <span class="font-semibold">Status:</span>
            <span
              :class="{
                'px-2 py-1 rounded text-xs font-semibold': true,
                'bg-green-100 text-green-800': student.status === 'active',
                'bg-yellow-100 text-yellow-800': student.status === 'inactive',
                'bg-red-100 text-red-800': student.status === 'terminated',
                'bg-blue-100 text-blue-800': student.status === 'completed'
              }"
            >
              {{ student.status || 'N/A' }}
            </span>
          </p>
          <p><span class="font-semibold">Registration Date:</span> {{ formatDate(student.registration_date) }}</p>
          <p><span class="font-semibold">Heard About Us:</span> {{ student.heard_about_us || 'N/A' }}</p>
        </div>
      </div>

      <!-- Enrollments -->
      <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Enrollments</h2>

        <div v-if="student.enrollments?.length">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="en in student.enrollments" :key="en.id">
                <td class="px-4 py-2 text-sm text-gray-900">
                  {{ en.course?.course_name || 'N/A' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-gray-500">No enrollments found for this student.</p>
      </div>

      <!-- Assign / Unassign -->
      <div v-if="canAssign" class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Assign or Unassign Enrollment</h2>

        <div v-if="availableEnrollments.length">
          <select v-model="selectedEnrollment" class="border rounded-lg px-3 py-2 w-full mb-4">
            <option value="">Select Enrollment</option>
            <option v-for="e in availableEnrollments" :key="e.id" :value="e.id">
              {{ e.course?.course_name || 'N/A' }}
            </option>
          </select>

          <div class="flex space-x-2">
            <button @click="assignEnrollment"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
              Assign
            </button>

            <button @click="unassignEnrollment"
              class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
              Unassign
            </button>
          </div>
        </div>

        <p v-else class="text-gray-500">No available enrollments to assign.</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  student: Object,
  availableEnrollments: Array,
});

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

// Backend allows only Admin & Super-Admin
const canEdit = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));
const canDelete = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));
const canAssign = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));

const selectedEnrollment = ref("");

function deleteStudent() {
  if (confirm(`Are you sure you want to delete ${props.student.full_name}?`)) {
    router.delete(route('students.destroy', props.student.id));
  }
}

function assignEnrollment() {
  if (!selectedEnrollment.value) return alert("Please select enrollment.");
  router.post(`/students/${props.student.id}/assign`, {
    enrollment_id: selectedEnrollment.value,
  });
}

function unassignEnrollment() {
  if (!selectedEnrollment.value) return alert("Please select enrollment.");
  const reason = prompt("Enter a reason for unassigning:");
  if (!reason) return;
  router.post(`/students/${props.student.id}/unassign`, {
    enrollment_id: selectedEnrollment.value,
    reason,
  });
}

function formatDate(dateStr) {
  if (!dateStr) return "N/A";
  return new Date(dateStr).toLocaleDateString();
}
</script>

<style scoped>
.container {
  max-width: 1100px;
}
</style>
