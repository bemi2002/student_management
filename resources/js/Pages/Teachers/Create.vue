<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

// Initialize form
const form = useForm({

  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  specialization: '',
  availability_days: '',
  preferred_start_time: '',
  preferred_end_time: ''
})

// Form submission
function submit() {
  if (
    
    !form.first_name ||
    !form.last_name ||
    !form.email
  ) {
    alert('Please fill in all required fields.')
    return
  }

  form.post('/teachers')
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow mt-10">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-black">Add Teacher</h2>
        <Link href="/teachers" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400">
          Back
        </Link>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6">
        

        <!-- First Name -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">First Name</label>
          <input
            v-model="form.first_name"
            type="text"
            placeholder="Enter First Name"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Last Name -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Last Name</label>
          <input
            v-model="form.last_name"
            type="text"
            placeholder="Enter Last Name"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="Enter Email Address"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Phone</label>
          <input
            v-model="form.phone"
            type="text"
            placeholder="Enter Phone Number"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Specialization -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Specialization</label>
          <input
            v-model="form.specialization"
            type="text"
            placeholder="Enter Specialization"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Availability Days -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Availability Days</label>
          <input
            v-model="form.availability_days"
            type="text"
            placeholder="e.g. Monday, Wednesday, Friday"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Preferred Start Time -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Preferred Start Time</label>
          <input
            v-model="form.preferred_start_time"
            type="time"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Preferred End Time -->
        <div>
          <label class="block text-sm font-medium text-black mb-1">Preferred End Time</label>
          <input
            v-model="form.preferred_end_time"
            type="time"
            class="w-full border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
          />
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 mt-6">
          <Link href="/teachers" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400">
            Cancel
          </Link>
          <button
            type="submit"
            class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
        </div>

        <!-- Validation Errors -->
        <div v-if="Object.keys(form.errors).length" class="mt-4 text-red-500">
          <ul>
            <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
          </ul>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
