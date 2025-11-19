
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Reactive form
const form = useForm({
  name: ''
})

// Local success message
const successMessage = ref('')

// Submit handler
const submit = () => {
  form.post('/permissions', {
    onSuccess: () => {
      form.reset('name')        // Clear form
      successMessage.value = 'Permission created successfully!' // Show message
    }
  })
}
</script>

<template>
  <Head title="Create Permission" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Permission
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

          <!-- Simple success message -->
          <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ successMessage }}
          </div>

          <!-- Back button -->
          <div class="mb-4">
            <Link
              href="/permissions"
              class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
            >
              Back
            </Link>
          </div>

          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label for="name" class="block font-medium text-gray-700">
                Permission Name
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
              <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                {{ form.errors.name }}
              </div>
            </div>

            <button
              type="submit"
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
              :disabled="form.processing"
            >
              Submit
            </button>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


