<script setup>
import { ref } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Get course_id from query string
const urlParams = new URLSearchParams(window.location.search)
const courseId = urlParams.get('course_id')

// Form
const form = useForm({
  course_id: courseId,
  name: '',
  resource: ''
})

// Submit
function submit() {
  form.post('/sub-courses', {
    onSuccess: () => {
      // redirect to courses page
      window.location.href = '/courses'
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Add SubCourse" />
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <h2 class="text-2xl font-bold mb-4">Add SubCourse</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="SubCourse Name"
            class="w-full border rounded-lg p-2"
            required
          />
        </div>
        <div>
          <input
            v-model="form.resource"
            type="text"
            placeholder="Resource URL (optional)"
            class="w-full border rounded-lg p-2"
          />
        </div>
        <div class="flex justify-end space-x-2 mt-4">
          <Link href="/courses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
          <button
            type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            :disabled="form.processing"
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
          >
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
