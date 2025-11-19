<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
  resource: { type: Object, required: true },
  courses: { type: Array, default: () => [] },
  specificCourses: { type: Array, default: () => [] },
  errors: { type: Object, default: () => ({}) }
})

const form = useForm({
  courses_id: props.resource.courses_id || '',
  specific_courses_id: props.resource.specific_courses_id || '',
  text: props.resource.text || ''
})

function submit() {
  form.put(`/resources/${props.resource.id}`, {
    onSuccess: () => {
      window.location.href = '/resources'
    }
  })
}

onMounted(() => {
  console.log('Edit Resource Mounted', form.data())
})
</script>

<template>
  <Head title="Edit Resource" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Edit Resource
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Resource</h2>
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
                      class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.courses_id }">
                <option value="">Select Course</option>
                <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.course_name }}</option>
              </select>
              <p v-if="errors.courses_id" class="text-red-500 text-sm mt-1">{{ errors.courses_id }}</p>
            </div>

            <!-- Specific Course -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Specific Course *</label>
              <select v-model="form.specific_courses_id"
                      class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.specific_courses_id }">
                <option value="">Select Specific Course</option>
                <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">{{ sc.name }}</option>
              </select>
              <p v-if="errors.specific_courses_id" class="text-red-500 text-sm mt-1">{{ errors.specific_courses_id }}</p>
            </div>

            <!-- Resource Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Resource Text *</label>
              <textarea v-model="form.text"
                        rows="4"
                        class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.text }"></textarea>
              <p v-if="errors.text" class="text-red-500 text-sm mt-1">{{ errors.text }}</p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t">
              <Link href="/resources" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                Cancel
              </Link>
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                {{ form.processing ? 'Updating...' : 'Update Resource' }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
