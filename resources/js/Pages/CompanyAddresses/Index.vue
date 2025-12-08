<script setup>
import { useForm, Link } from '@inertiajs/vue3'

defineProps({
  companies: {
    type: Array,
    default: () => []
  }
})

const form = useForm()

function deleteCompany(id) {
  if (confirm('Are you sure you want to delete this company?')) {
    form.delete(route('company-addresses.destroy', id), {
      preserveScroll: true,
      onSuccess: () => console.log('Company deleted successfully'),
    })
  }
}
</script>

<template>
  <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Company Addresses</h2>

      <Link
        href="/company-addresses/create"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
      >
        + Add Company
      </Link>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">City</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Sub City</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Building Name</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Building Number</th>
            <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="company in companies" :key="company.id" class="hover:bg-gray-50 transition">
            <td class="px-4 py-2 text-sm text-gray-700">{{ company.id }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ company.city }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ company.sub_city || '-' }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ company.building_name || '-' }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ company.building_number || '-' }}</td>

            <td class="px-4 py-2 text-right text-sm font-medium flex justify-end gap-2">
              <Link
                :href="`/company-addresses/${company.id}/edit`"
                class="text-blue-600 hover:text-blue-900 font-semibold"
              >
                Edit
              </Link>

              <button
                @click="deleteCompany(company.id)"
                class="text-red-600 hover:text-red-900 font-semibold"
              >
                Delete
              </button>
            </td>
          </tr>

          <tr v-if="companies.length === 0">
            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
              No companies found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
