<script setup>
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props passed from controller
const props = defineProps({
  company: Object
})

// Initialize form with existing company values
const form = useForm({
  company_name: props.company?.company_name ?? '',
  email: props.company?.email ?? '',
  contact_phone: props.company?.contact_phone ?? '',
  city: props.company?.city ?? '',
  sub_city: props.company?.sub_city ?? '',
  building_name: props.company?.building_name ?? '',
  building_number: props.company?.building_number ?? '',
  website: props.company?.website ?? ''
})

const errors = ref({})

// Submit handler
function submit() {
  form.put(`/company-addresses/${props.company.id}`, {
    preserveScroll: true,
    onSuccess: () => router.visit('/company-addresses'),
    onError: (errs) => {
      errors.value = errs
    }
  })
}
</script>

<template>
  <Head title="Edit Company" />

  <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Edit Company</h2>
      <Link href="/company-addresses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
        Back to Companies
      </Link>
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <input type="text" v-model="form.company_name" placeholder="Company Name" class="w-full border rounded p-2" required />
      <input type="email" v-model="form.email" placeholder="Email" class="w-full border rounded p-2" required />
      <input type="text" v-model="form.contact_phone" placeholder="Phone" class="w-full border rounded p-2" />

      <input type="text" v-model="form.city" placeholder="City" class="w-full border rounded p-2" />
      <input type="text" v-model="form.sub_city" placeholder="Sub City" class="w-full border rounded p-2" />
      <input type="text" v-model="form.building_name" placeholder="Building Name" class="w-full border rounded p-2" />
      <input type="text" v-model="form.building_number" placeholder="Building Number" class="w-full border rounded p-2" />
      <input type="text" v-model="form.website" placeholder="Website" class="w-full border rounded p-2" />

      <div class="flex justify-end gap-2 mt-4">
        <Link href="/company-addresses" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          {{ form.processing ? 'Updating...' : 'Update' }}
        </button>
      </div>
    </form>
  </div>
</template>
