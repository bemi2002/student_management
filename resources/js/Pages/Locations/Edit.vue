<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  name: ''
})

function submit() {
  form.post('/locations')
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create Location" />

    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <h2 class="text-2xl font-bold mb-6">Create Location</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" v-model="form.name" class="w-full border rounded-lg p-2" />
        </div>

        <div class="flex justify-end space-x-2 mt-4">
          <Link href="/locations" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</Link>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
