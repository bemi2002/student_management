<script setup>
import { useForm, Link } from '@inertiajs/vue3'

defineProps({
  teachers: Array,
  students: Array,
  batches: Array,
  courses: Array,
  resources: Array,
  specificCourses: Array,
})

const form = useForm({
  teacher_id: '',
  student_id: '',
  batches_id: '',
  course_id: '',
  resources: '',
  specific_courses_id: '',
})

const submit = () => form.post(route('admins.store'))
</script>

<template>
  <div class="max-w-3xl mx-auto p-8 bg-white shadow rounded-xl">
    <h1 class="text-2xl font-semibold mb-6">Create Admin Record</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <!-- Teacher -->
      <div>
        <label class="block mb-1 font-medium">Teacher</label>
        <select v-model="form.teacher_id" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Teacher</option>
          <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
            {{ teacher.name }}
          </option>
        </select>
        <p v-if="form.errors.teacher_id" class="text-red-500 text-sm mt-1">{{ form.errors.teacher_id }}</p>
      </div>

      <!-- Student -->
      <div>
        <label class="block mb-1 font-medium">Student</label>
        <select v-model="form.student_id" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Student</option>
          <option v-for="student in students" :key="student.id" :value="student.id">
            {{ student.name }}
          </option>
        </select>
        <p v-if="form.errors.student_id" class="text-red-500 text-sm mt-1">{{ form.errors.student_id }}</p>
      </div>

      <!-- Batch -->
      <div>
        <label class="block mb-1 font-medium">Batch</label>
        <select v-model="form.batches_id" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Batch</option>
          <option v-for="batch in batches" :key="batch.id" :value="batch.id">
            {{ batch.name }}
          </option>
        </select>
        <p v-if="form.errors.batches_id" class="text-red-500 text-sm mt-1">{{ form.errors.batches_id }}</p>
      </div>

      <!-- Course -->
      <div>
        <label class="block mb-1 font-medium">Course</label>
        <select v-model="form.course_id" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Course</option>
          <option v-for="course in courses" :key="course.id" :value="course.id">
            {{ course.course_name }}
          </option>
        </select>
        <p v-if="form.errors.course_id" class="text-red-500 text-sm mt-1">{{ form.errors.course_id }}</p>
      </div>

      <!-- Resource -->
      <div>
        <label class="block mb-1 font-medium">Resource</label>
        <select v-model="form.resources" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Resource</option>
          <option v-for="r in resources" :key="r.id" :value="r.id">
            {{ r.text || r.name || `Resource #${r.id}` }}
          </option>
        </select>
        <p v-if="form.errors.resources" class="text-red-500 text-sm mt-1">{{ form.errors.resources }}</p>
      </div>

      <!-- Specific Course -->
      <div>
        <label class="block mb-1 font-medium">Specific Course</label>
        <select v-model="form.specific_courses_id" class="w-full border rounded px-3 py-2">
          <option disabled value="">Select Specific Course</option>
          <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">
            {{ sc.name }}
          </option>
        </select>
        <p v-if="form.errors.specific_courses_id" class="text-red-500 text-sm mt-1">{{ form.errors.specific_courses_id }}</p>
      </div>

      <!-- Buttons -->
      <div class="flex justify-between mt-6">
        <Link :href="route('admins.index')" class="text-gray-600 hover:underline">Cancel</Link>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Saving...' : 'Save Record' }}
        </button>
      </div>
    </form>
  </div>
</template>
