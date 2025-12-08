<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({ locations: Array })

const form = useForm({})

function deleteLocation(id) {
  if (!confirm('Are you sure you want to delete this location?')) return

  form.delete(`/locations/${id}`, {
    onSuccess: () => {
      // Optional: refresh page or show success message
    }
  })
}
</script>

<template>
  
    <Head title="Locations" />

    <div class="p-8 bg-gray-100 min-h-screen">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Locations</h1>
        <Link href="/locations/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
          + New Location
        </Link>
      </div>

      <!-- Table -->
      <table class="w-full bg-white shadow rounded-lg">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="loc in locations" :key="loc.id" class="border-t hover:bg-gray-50">
            <td class="p-3">{{ loc.id }}</td>
            <td class="p-3">{{ loc.name }}</td>
            <td class="p-3 text-right space-x-2">
              <Link :href="`/locations/${loc.id}/edit`" class="text-blue-600 hover:underline">Edit</Link>
              <button
                @click="deleteLocation(loc.id)"
                class="text-red-600 hover:underline"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- No Locations -->
      <div v-if="locations.length === 0" class="text-center py-8 text-gray-500">
        No locations found.
      </div>
    </div>
  
</template>
