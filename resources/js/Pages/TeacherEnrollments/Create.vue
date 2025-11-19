<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  teachers: { type: Array, default: () => [] },
  courses: { type: Array, default: () => [] },
  specificCourses: { type: Array, default: () => [] },
})

const form = useForm({
  teacher_id: '',
  courses_id: '',
  specific_courses_id: '',
  enrollment_id: '',
})

function submit() {
  form.post('/teacher-enrollments', {
    onSuccess: () => window.location.href = '/teacher-enrollments'
  })
}
</script>

<template>
  <Head title="Create Teacher Enrollment" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Teacher Enrollment</h2>
    </template>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Create Teacher Enrollment</h2>
        <Link
          href="/teacher-enrollments"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
        >
          Back
        </Link>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label>Teacher *</label>
          <select v-model="form.teacher_id" class="w-full border p-2 rounded">
            <option value="">Select Teacher</option>
            <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
          </select>
        </div>

        <div>
          <label>Course *</label>
          <select v-model="form.courses_id" class="w-full border p-2 rounded">
            <option value="">Select Course</option>
            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>

        <div>
          <label>Specific Course *</label>
          <select v-model="form.specific_courses_id" class="w-full border p-2 rounded">
            <option value="">Select Specific Course</option>
            <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">{{ sc.name }}</option>
          </select>
        </div>

        <div>
          <label>Enrollment ID *</label>
          <input
            type="text"
            v-model="form.enrollment_id"
            class="w-full border p-2 rounded"
          />
        </div>

        <div class="flex justify-end gap-2 mt-4">
          <Link
            href="/teacher-enrollments"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
          >
            Cancel
          </Link>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
