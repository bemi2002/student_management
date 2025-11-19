<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  specificCourse: Object,
  courses: Array,
  teachers: Array,
  batches: Array
})

// Reactive form
const form = useForm({
  course_id: props.specificCourse?.course_id || '',
  teacher_id: props.specificCourse?.teacher_id || '',
  batch_id: props.specificCourse?.batch_id || '',
  specific_courses_code: props.specificCourse?.specific_courses_code || '',
  description: props.specificCourse?.description || '',
  class_days: Array.isArray(props.specificCourse?.class_days) ? props.specificCourse.class_days : [],
  difficulty_level: props.specificCourse?.difficulty_level || 'beginner',
  resources: props.specificCourse?.resources || ''
})

// Weekdays
const weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

// Submit handler
function submit() {
  form.put(`/specific-courses/${props.specificCourse.id}`, {
    onSuccess: () => router.visit('/specific-courses')
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit Specific Course" />

    <div class="p-8 bg-gray-100 min-h-screen">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Specific Course</h1>
        <Link href="/specific-courses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
          Back to Specific Courses
        </Link>
      </div>

      <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <form @submit.prevent="submit" class="space-y-4">

          <!-- Course -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
            <select v-model="form.course_id"
                    class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
              <option value="">Select Course</option>
              <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.courses_name }}</option>
            </select>
          </div>

          <!-- Teacher -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
            <select v-model="form.teacher_id"
                    class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
              <option value="">Select Teacher</option>
              <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.first_name }} {{ t.last_name }}</option>
            </select>
          </div>

          <!-- Batch -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
            <select v-model="form.batch_id"
                    class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select Batch</option>
              <option v-for="b in batches" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
          </div>

          <!-- Course Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
            <input type="text" v-model="form.specific_courses_code"
                   class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
          </div>

          <!-- Difficulty Level -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
            <select v-model="form.difficulty_level"
                    class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
              <option value="expert">Expert</option>
            </select>
          </div>

          <!-- Class Days -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Class Days</label>
            <div class="flex flex-wrap gap-2">
              <label v-for="day in weekdays" :key="day" class="inline-flex items-center gap-2">
                <input type="checkbox" :value="day" v-model="form.class_days"
                       class="w-4 h-4 border-gray-300 rounded text-blue-600 focus:ring-blue-500" />
                <span class="text-gray-700">{{ day }}</span>
              </label>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea v-model="form.description" rows="3"
                      class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>
          <select
  v-model="form.course_id"
  class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none"
>
  <option value="">Select Course</option>
  <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.courses_name }}</option>
</select>


          <!-- Resources -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Resources</label>
            <textarea v-model="form.resources" rows="3"
                      class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2 mt-4">
            <Link href="/specific-courses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              {{ form.processing ? 'Updating...' : 'Update Specific Course' }}
            </button>
          </div>

          <!-- Validation Errors -->
          <div v-if="Object.keys(form.errors).length" class="mt-4 text-red-600">
            <ul class="list-disc list-inside">
              <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
            </ul>
          </div>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
