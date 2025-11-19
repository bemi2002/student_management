<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
  subCourse: Object // Passed from backend with course_id
})

const form = useForm({
  name: subCourse?.name ?? '',
  resource: subCourse?.resource ?? '',
  course_id: subCourse?.course_id
})

const errors = ref({})

function submit() {
  form.put(`/sub-courses/${subCourse.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      router.visit(`/courses/${subCourse.course_id}`)
      errors.value = {}
    },
    onError: (errs) => {
      console.error(errs)
      errors.value = errs
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit SubCourse" />

    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit SubCourse</h2>
        <Link :href="`/courses/${subCourse.course_id}`" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Back to Course</Link>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SubCourse Name</label>
          <input
            type="text"
            v-model="form.name"
            class="w-full border rounded-lg p-2"
            :class="{ 'border-red-500': errors.name }"
            required
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Resource URL</label>
          <input
            type="url"
            v-model="form.resource"
            class="w-full border rounded-lg p-2"
            :class="{ 'border-red-500': errors.resource }"
          />
          <p v-if="errors.resource" class="text-red-500 text-sm mt-1">{{ errors.resource }}</p>
        </div>

        <div class="flex justify-end space-x-2 mt-4">
          <Link :href="`/courses/${subCourse.course_id}`" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
          >
            {{ form.processing ? 'Updating...' : 'Update' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
