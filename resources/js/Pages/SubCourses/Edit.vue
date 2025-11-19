<script setup>
import { ref } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props from backend
const props = defineProps({
  subCourse: Object
})

// Form
const form = useForm({
  name: props.subCourse.name || '',
  resource_type: props.subCourse.resource_type || 'url', // 'url' or 'file'
  resource_path: props.subCourse.resource_type === 'url' ? props.subCourse.resource_path : null,
})

// File input reference
const fileInput = ref(null)

// Handle file selection
function handleFileChange(event) {
  const file = event.target.files[0]
  if (file) {
    form.resource_type = 'file'
    form.resource_path = file
  }
}

// Submit
function submit() {
  const data = new FormData()
  data.append('name', form.name)
  data.append('resource_type', form.resource_type)

  if (form.resource_type === 'file' && form.resource_path) {
    data.append('resource_path', form.resource_path)
  } else {
    data.append('resource_path', form.resource_path || '')
  }

  form.put(`/sub-courses/${props.subCourse.id}`, data, {
    forceFormData: true,
    onSuccess: () => {
      window.location.href = `/courses`
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit SubCourse" />
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <h2 class="text-2xl font-bold mb-4">Edit SubCourse</h2>

      <form @submit.prevent="submit" class="space-y-4">

        <!-- SubCourse Name -->
        <input
          v-model="form.name"
          type="text"
          placeholder="SubCourse Name"
          class="w-full border rounded-lg p-2"
          required
        />

        <!-- Resource URL -->
        <input
          v-if="form.resource_type === 'url'"
          v-model="form.resource_path"
          type="text"
          placeholder="Resource URL (optional)"
          class="w-full border rounded-lg p-2"
        />

        <!-- File Input -->
        <input
          v-if="form.resource_type === 'file'"
          ref="fileInput"
          type="file"
          class="w-full border rounded-lg p-2"
          @change="handleFileChange"
        />

        <!-- Toggle Resource Type -->
        <div class="flex gap-4 mt-2">
          <button
            type="button"
            class="px-3 py-1 border rounded hover:bg-gray-100"
            @click="form.resource_type='url'; form.resource_path=''"
          >
            Use URL
          </button>
          <button
            type="button"
            class="px-3 py-1 border rounded hover:bg-gray-100"
            @click="form.resource_type='file'; fileInput.value.value=null; form.resource_path=null"
          >
            Upload File
          </button>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-2 mt-4">
          <Link
            href="/courses"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
          >
            Cancel
          </Link>
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
