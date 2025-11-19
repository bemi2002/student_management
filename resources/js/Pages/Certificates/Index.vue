<script setup>
import { Head, Link, router } from '@inertiajs/vue3' // ✅ FIXED import (was wrong before)

const props = defineProps({
  certificates: Object,
  role: String,
})

// Teacher approves course completion
function teacherApprove(id) {
  if (confirm('Confirm student course completion?')) {
    router.post(route('certificates.teacher.approve', id))
  }
}

// Admin approves payment and generates certificate
function adminApprove(id) {
  if (confirm('Approve payment and generate certificate?')) {
    router.post(route('certificates.admin.approve', id))
  }
}

// Download certificate
function downloadCert(id) {
  window.open(route('certificates.download', id), '_blank')
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Certificates</h1>

    <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2">Student</th>
          <th class="p-2">Course</th>
          <th class="p-2">Status</th>
          <th class="p-2">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in certificates.data" :key="c.id" class="border-b">
          <td class="p-2">{{ c.student.name }}</td>
          <td class="p-2">{{ c.course.title }}</td>
          <td class="p-2 capitalize">{{ c.status.replace('_', ' ') }}</td>
          <td class="p-2 space-x-2">
            <!-- Teacher approval -->
            <template v-if="role === 'Teacher' && c.status === 'pending'">
              <button
                @click="teacherApprove(c.id)"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
              >
                Approve Course
              </button>
            </template>

            <!-- Admin approval -->
            <template v-else-if="role === 'Admin' && c.status === 'teacher_approved'">
              <button
                @click="adminApprove(c.id)"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
              >
                Approve Payment
              </button>
            </template>

            <!-- Certificate download -->
            <template v-else-if="c.status === 'completed'">
              <button
                @click="downloadCert(c.id)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded"
              >
                Download Certificate
              </button>
            </template>

            <!-- No action -->
            <span v-else>—</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
