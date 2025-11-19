<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Student</h2>
    </template>

    <div class="container mx-auto px-4 py-8">
      <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-900">Edit Student</h1>
          <Link :href="route('students.index')" class="text-gray-600 hover:text-gray-900">
            Back to Students
          </Link>
        </div>

        <!-- Validation Errors -->
        <div v-if="Object.keys(form.errors).length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <ul>
            <li v-for="(error, key) in form.errors" :key="key">{{ error[0] }}</li>
          </ul>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Full Name -->
              <div class="md:col-span-2">
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                <input
                  v-model="form.full_name"
                  type="text"
                  id="full_name"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                  required
                />
              </div>

              <!-- Email -->
              <div class="md:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input
                  v-model="form.email"
                  type="email"
                  id="email"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                  required
                />
              </div>

              <!-- Contact Phone -->
              <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                <input
                  v-model="form.contact_phone"
                  type="text"
                  id="contact_phone"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                />
              </div>

              <!-- Registration Date -->
              <div>
                <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date *</label>
                <input
                  v-model="form.registration_date"
                  type="date"
                  id="registration_date"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                  required
                />
              </div>

              <!-- Interested Course -->
              <div>
                <label for="interested_course" class="block text-sm font-medium text-gray-700">Interested Course</label>
                <input
                  v-model="form.interested_course"
                  type="text"
                  id="interested_course"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                />
              </div>

              <!-- Heard About Us -->
              <div>
                <label for="heard_about_us" class="block text-sm font-medium text-gray-700">Heard About Us</label>
                <select
                  v-model="form.heard_about_us"
                  id="heard_about_us"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                >
                  <option value="">Select an option</option>
                  <option value="friend">Friend</option>
                  <option value="social_media">Social Media</option>
                  <option value="website">Website</option>
                  <option value="advertisement">Advertisement</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select
                  v-model="form.status"
                  id="status"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                  required
                  @change="handleStatusChange"
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="terminated">Terminated</option>
                  <option value="completed">Completed</option>
                </select>
              </div>

              <!-- Termination Date -->
              <div v-if="form.status === 'terminated' || form.status === 'completed'">
                <label for="termination_date" class="block text-sm font-medium text-gray-700">Termination Date *</label>
                <input
                  v-model="form.termination_date"
                  type="date"
                  id="termination_date"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                  :required="form.status === 'terminated' || form.status === 'completed'"
                />
              </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
              <Link :href="route('students.index')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                Cancel
              </Link>
              <button 
                type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Updating...' : 'Update Student' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  student: { type: Object, required: true }
})

const form = useForm({
  full_name: props.student.full_name,
  email: props.student.email,
  contact_phone: props.student.contact_phone || '',
  registration_date: props.student.registration_date,
  interested_course: props.student.interested_course || '',
  heard_about_us: props.student.heard_about_us || '',
  status: props.student.status,
  termination_date: props.student.termination_date || ''
})

const handleStatusChange = () => {
  if (form.status === 'active' || form.status === 'inactive') {
    form.termination_date = ''
  }
}

const submit = () => {
  form.put(route('students.update', props.student.id), {
    preserveScroll: true,
    onSuccess: () => {
      // success flash will be handled automatically
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}
</script>
