<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Props from controller
defineProps({
  courses: Array,
  teachers: Array,
  batches: Array
})

// Reactive form
const form = useForm({
  course_id: '',
  teacher_id: '',
  batch_id: '',
  specific_courses_code: '',
  description: '',
  courses_fee: '',
  difficulty_level: 'beginner',
  resources: ''
})

// Submit handler
function submit() {
  form.post('/specific-courses')
}

// Weekdays for class_days checkboxes
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create Specific Course" />

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <h2 class="text-2xl font-bold mb-6">Create Specific Course</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <!-- Course -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Course</label>
            <select v-model="form.course_id" class="w-full border rounded-lg p-2">
              <option value="">Select Course</option>
              <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.courses_name }}</option>
            </select>
          </div>

          <!-- Teacher -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Teacher</label>
            <select v-model="form.teacher_id" class="w-full border rounded-lg p-2">
              <option value="">Select Teacher</option>
              <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.first_name }} {{ t.last_name }}</option>
            </select>
          </div>

          <!-- Batch -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Batch</label>
            <select v-model="form.batch_id" class="w-full border rounded-lg p-2">
              <option value="">Select Batch</option>
              <option v-for="b in batches" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
          </div>

          <!-- Course Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Course Code</label>
            <input type="text" v-model="form.specific_courses_code" class="w-full border rounded-lg p-2" placeholder="Enter Course Code" />
          </div>

          <!-- Description -->
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" class="w-full border rounded-lg p-2" placeholder="Enter Description"></textarea>
          </div>

          <!-- Difficulty Level -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Difficulty Level</label>
            <select v-model="form.difficulty_level" class="w-full border rounded-lg p-2">
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
              <option value="expert">Expert</option>
            </select>
          </div>

          <!-- Resources -->
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Resources</label>
            <textarea v-model="form.resources" class="w-full border rounded-lg p-2" placeholder="Enter Resources"></textarea>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end mt-6 space-x-2">
          <Link href="/specific-courses" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</Link>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
