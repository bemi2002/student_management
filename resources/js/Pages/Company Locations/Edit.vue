<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  location: Object
})

const form = useForm({
  company_name: props.location.company_name || '',
  email: props.location.email || '',
  phone: props.location.phone || ''
})

const submit = () => {
  form.put(`/company-locations/${props.location.id}`)
}
</script>

<template>
  <Head title="Edit Company Location" />

  <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Edit Company Location</h2>
      <Link href="/company-locations" class="text-blue-600 hover:underline">Back</Link>
    </div>

    <form @submit.prevent="submit" class="space-y-4">

      <div>
        <label class="block font-medium text-gray-700">Company Name</label>
        <input
          v-model="form.company_name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
        />
        <div v-if="form.errors.company_name" class="text-red-600 text-sm mt-1">
          {{ form.errors.company_name }}
        </div>
      </div>

      <div>
        <label class="block font-medium text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
        />
        <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
          {{ form.errors.email }}
        </div>
      </div>

      <div>
        <label class="block font-medium text-gray-700">Phone</label>
        <input
          v-model="form.phone"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
        />
        <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1">
          {{ form.errors.phone }}
        </div>
      </div>

      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        :disabled="form.processing"
      >
        Update
      </button>
    </form>
  </div>
</template>
