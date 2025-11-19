<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

defineProps({
  students: Array,
  courses: Array,
  specificCourses: Array
})

const form = useForm({
  students_id: '',
  courses_id: '',
  specific_courses_id: '',
  enrollment_id: ''
})

function submit() {
  form.post('/student-enrollments')
}
</script>

<template>
  <Head title="Create Student Enrollment" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Student Enrollment</h2>
    </template>

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <h2 class="text-2xl font-bold mb-6">Create Student Enrollment</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Student</label>
            <select v-model="form.students_id" class="w-full border rounded-lg p-2">
              <option value="">Select Student</option>
              <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Course</label>
            <select v-model="form.courses_id" class="w-full border rounded-lg p-2">
              <option value="">Select Course</option>
              <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Specific Course</label>
            <select v-model="form.specific_courses_id" class="w-full border rounded-lg p-2">
              <option value="">Select Specific Course</option>
              <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">{{ sc.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Enrollment ID</label>
            <input
              type="number"
              v-model="form.enrollment_id"
              class="w-full border rounded-lg p-2"
              placeholder="Enter Enrollment ID"
            />
          </div>
        </div>

        <div class="flex justify-end mt-6 space-x-2">
          <Link
            href="/student-enrollments"
            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400"
          >
            Cancel
          </Link>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          >
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
