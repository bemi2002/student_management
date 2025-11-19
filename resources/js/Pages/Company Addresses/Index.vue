<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  companies: { type: Array, default: () => [] }
})

const form = useForm()

function deleteCompany(id) {
  if (confirm('Are you sure you want to delete this company?')) {
    form.delete(`/company-addresses/${id}`, {
      preserveScroll: true,
      onSuccess: () => console.log('Company deleted successfully')
    })
  }
}
</script>

<template>
  <Head title="Company Addresses" />

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
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Company Name</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Email</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Phone</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="company in companies" :key="company.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-700">{{ company.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ company.company_name }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ company.email }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ company.contact_phone || '-' }}</td>
            <td class="px-6 py-4 text-right flex gap-2 justify-end">
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
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
              No companies found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
