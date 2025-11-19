<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  course: Object
})

const form = useForm({
  course_name: props.course?.course_name ?? '',
  description: props.course?.description ?? '',
  duration_dates: props.course?.duration_dates ?? '',
  level: props.course?.level ?? '',
  start_date: props.course?.start_date ?? '',
  end_date: props.course?.end_date ?? ''
})

const errors = ref({})

function submit() {
  form.put(`/courses/${props.course.id}`, {
    preserveScroll: true,
    onSuccess: () => router.visit('/courses'),
    onError: (errs) => errors.value = errs
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit Course" />

    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Course</h2>
        <Link href="/courses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Back to Courses</Link>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <input type="text" v-model="form.course_name" placeholder="Course Name" class="w-full border rounded p-2" required />
        <textarea v-model="form.description" placeholder="Description" class="w-full border rounded p-2"></textarea>
        <input type="number" v-model="form.duration_dates" placeholder="Duration (days)" class="w-full border rounded p-2" />

        <select v-model="form.level" class="w-full border rounded p-2">
          <option value="">Select Level</option>
          <option value="beginner">Beginner</option>
          <option value="intermediate">Intermediate</option>
          <option value="advanced">Advanced</option>
        </select>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" v-model="form.start_date" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" v-model="form.end_date" class="w-full border rounded p-2" />
          </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
          <Link href="/courses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ form.processing ? 'Updating...' : 'Update' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
