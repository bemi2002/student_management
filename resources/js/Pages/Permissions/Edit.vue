<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  permission: Object
})

// Initialize form with existing permission data
const form = useForm({
  name: props.permission.name || ''
})

// Submit handler → PUT /permissions/{id}
const submit = () => {
  form.put(`/permissions/${props.permission.id}`, {
    onSuccess: () => {
      alert('Permission updated successfully!')
    }
  })
}
</script>

<template>
  <Head title="Edit Permission" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Edit Permission
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

          <!-- Back Button -->
          <div class="mb-4">
            <Link
              href="/permissions"
              class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
            >
              Back
            </Link>
          </div>

          <!-- Edit Form -->
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label for="name" class="block font-medium text-gray-700">
                Permission Name
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
              <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                {{ form.errors.name }}
              </div>
            </div>

            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              :disabled="form.processing"
            >
              Edit
            </button>
            


          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
