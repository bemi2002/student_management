<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  locations: { type: Array, default: () => [] }
})

const form = useForm()

const deleteLocation = (id) => {
  if (confirm('Are you sure you want to delete this location?')) {
    form.delete(`/company-locations/${id}`, {
      onSuccess: () => {
        const index = props.locations.findIndex(loc => loc.id === id)
        if (index !== -1) props.locations.splice(index, 1)
        alert('Location deleted successfully.')
      }
    })
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Company Locations" />

    <div class="max-w-7xl mx-auto p-8 bg-white rounded-xl shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Company Locations</h2>
        <Link href="/company-locations/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
          + Add Location
        </Link>
      </div>

      <table class="min-w-full border divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Company Name</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Email</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Phone</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="location in locations" :key="location.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">{{ location.id }}</td>
            <td class="px-6 py-4">{{ location.company_name }}</td>
            <td class="px-6 py-4">{{ location.email }}</td>
            <td class="px-6 py-4">{{ location.phone }}</td>
            <td class="px-6 py-4 flex gap-2 justify-end">
              <Link :href="`/company-locations/${location.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>
              <button @click="deleteLocation(location.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>

          <tr v-if="locations.length === 0">
            <td colspan="5" class="text-center py-4 text-gray-500">No locations found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>
