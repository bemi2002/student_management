<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  company_name: '',
  email: '',
  contact_phone: '', // updated
})

const submit = () => {
  form.post(route('company-addresses.store'))
}
</script>

<template>
  <div class="max-w-xl mx-auto p-8 bg-white shadow rounded-xl">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Add Company</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1 text-gray-600">Company Name</label>
        <input
          v-model="form.company_name"
          type="text"
          class="w-full border-gray-300 rounded-lg"
        />
        <div v-if="form.errors.company_name" class="text-red-600 text-sm mt-1">
          {{ form.errors.company_name }}
        </div>
      </div>

      <div>
        <label class="block mb-1 text-gray-600">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full border-gray-300 rounded-lg"
        />
        <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
          {{ form.errors.email }}
        </div>
      </div>

      <div>
        <label class="block mb-1 text-gray-600">Phone</label>
        <input
          v-model="form.contact_phone"  <!-- updated -->
          type="text"
          class="w-full border-gray-300 rounded-lg"
        />
        <div v-if="form.errors.contact_phone" class="text-red-600 text-sm mt-1">
          {{ form.errors.contact_phone }}
        </div>
      </div>

      <div class="flex justify-between mt-6">
        <Link
          :href="route('company-addresses.index')"
          class="text-gray-600 hover:underline"
        >
          Cancel
        </Link>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          :disabled="form.processing"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</template>
