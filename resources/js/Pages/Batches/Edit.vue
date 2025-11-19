<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Define props
const props = defineProps({
  batch: Object
})

// Initialize form with batch data
const form = useForm({
  batch: props.batch?.batch ?? ''
})

// Submit handler
function submit() {
  form.put(`/batches/${props.batch.id}`, {
    onSuccess: () => {
      window.location.href = '/batches'
    }
  })
}
</script>

<template>
  <Head title="Edit Batch" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Edit Batch
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow mt-10">
        <h2 class="text-2xl font-bold mb-4">Edit Batch</h2>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name</label>
            <input
              v-model="form.batch"
              type="text"
              placeholder="Enter batch name"
              class="w-full border rounded-lg p-2"
            />
            <div v-if="form.errors.batch" class="text-red-500 text-sm mt-1">
              {{ form.errors.batch }}
            </div>
          </div>

          <div class="flex justify-end space-x-2">
            <Link
              href="/batches"
              class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Cancel
            </Link>
            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Updating...' : 'Update' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
