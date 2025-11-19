<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  batch: ''
})

function submit() {
  form.post('/batches', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <Head title="Create Batch" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Batch
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Create Batch</h2>
            <Link href="/batches" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
              ← Back
            </Link>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name *</label>
              <input
                v-model="form.batch"
                type="text"
                placeholder="Enter batch name"
                class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.batch" class="text-red-500 text-sm mt-1">{{ form.errors.batch }}</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
              <Link href="/batches" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                Cancel
              </Link>
              <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Creating...' : 'Create Batch' }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
