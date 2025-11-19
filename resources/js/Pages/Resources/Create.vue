<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  courses: { type: Array, default: () => [] },
  specificCourses: { type: Array, default: () => [] },
})

const form = useForm({
  courses_id: '',
  specific_courses_id: '',
  text: '',
})

const submit = () => {
  form.post(route('resources.store'), {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <Head title="Create Resource" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Resource
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Create Resource</h2>
            <Link href="/resources" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
              ← Back
            </Link>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="space-y-6">

            <!-- Course -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Course *</label>
              <select v-model="form.courses_id"
                      class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                <option value="">Select Course</option>
                <option v-for="course in courses" :key="course.id" :value="course.id">
                  {{ course.course_name }}
                </option>
              </select>
            </div>

            <!-- Specific Course -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Specific Course *</label>
              <select v-model="form.specific_courses_id"
                      class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                <option value="">Select Specific Course</option>
                <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">
                  {{ sc.name }}
                </option>
              </select>
            </div>

            <!-- Resource Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Resource Text *</label>
              <textarea v-model="form.text"
                        rows="4"
                        class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t">
              <Link href="/resources" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                Cancel
              </Link>
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                {{ form.processing ? 'Creating...' : 'Create Resource' }}
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
