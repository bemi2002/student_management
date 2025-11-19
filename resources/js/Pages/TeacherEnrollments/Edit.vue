<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  enrollment: Object,
  teachers: Array,
  courses: Array,
  specificCourses: Array
})

// Initialize form
const form = useForm({
  teacher_id: props.enrollment.teacher_id || '',
  courses_id: props.enrollment.courses_id || '',
  specific_courses_id: props.enrollment.specific_courses_id || '',
  enrollment_id: props.enrollment.enrollment_id || ''
})

// Submit handler
const submit = () => {
  form.put(`/teacher-enrollments/${props.enrollment.id}`, {
    onSuccess: () => {
      window.location.href = '/teacher-enrollments'
    }
  })
}
</script>

<template>
  <Head title="Edit Teacher Enrollment" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Teacher Enrollment</h2>
    </template>

    <div class="max-w-2xl mx-auto p-8 bg-white shadow rounded-xl mt-10">
      <h1 class="text-2xl font-semibold mb-6">Edit Teacher Enrollment</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <!-- Teacher -->
        <div>
          <label class="block mb-1 text-gray-700 font-medium">Teacher</label>
          <select v-model="form.teacher_id" class="w-full border rounded p-2">
            <option value="">Select Teacher</option>
            <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
          </select>
        </div>

        <!-- Course -->
        <div>
          <label class="block mb-1 text-gray-700 font-medium">Course</label>
          <select v-model="form.courses_id" class="w-full border rounded p-2">
            <option value="">Select Course</option>
            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>

        <!-- Specific Course -->
        <div>
          <label class="block mb-1 text-gray-700 font-medium">Specific Course</label>
          <select v-model="form.specific_courses_id" class="w-full border rounded p-2">
            <option value="">Select Specific Course</option>
            <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">{{ sc.name }}</option>
          </select>
        </div>

        <!-- Enrollment ID -->
        <div>
          <label class="block mb-1 text-gray-700 font-medium">Enrollment ID</label>
          <input
            v-model="form.enrollment_id"
            type="text"
            class="w-full border rounded p-2"
          />
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-6">
          <Link href="/teacher-enrollments" class="text-gray-600 hover:underline">Cancel</Link>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Updating...' : 'Update' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
