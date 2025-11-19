<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
  batches: Array
})

// Delete batch
const deleteBatch = (id) => {
  if (confirm('Are you sure you want to delete this batch?')) {
    router.delete(`/batches/${id}`)
  }
}
</script>

<template>
  <Head title="Batches" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Batches
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Batches</h1>
            <Link
              href="/batches/create"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              + New Batch
            </Link>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
              <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                  <th class="py-3 px-4 border-b">ID</th>
                  <th class="py-3 px-4 border-b">Batch</th>
                  <th class="py-3 px-4 border-b text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="batch in batches" :key="batch.id" class="hover:bg-gray-50 border-b">
                  <td class="py-3 px-4">{{ batch.id }}</td>
                  <td class="py-3 px-4">{{ batch.batch }}</td>
                  <td class="py-3 px-4 text-right space-x-2">
                    <Link
                      :href="`/batches/${batch.id}/edit`"
                      class="text-blue-600 hover:underline"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteBatch(batch.id)"
                      class="text-red-600 hover:underline"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div v-if="batches.length === 0" class="text-center py-8 text-gray-500">
              No batches found.
            </div>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
