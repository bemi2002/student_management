<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Student</h2>
    </template>

    <div class="max-w-3xl mx-auto px-6 py-10">
      
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Add New Student</h1>
        <Link :href="route('students.index')" class="text-gray-600 hover:text-gray-900">← Back to Students</Link>
      </div>

      <!-- Validation Errors -->
      <div
        v-if="Object.keys(form.errors).length"
        class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700"
      >
        <ul>
          <li v-for="(error, key) in form.errors" :key="key">{{ error[0] }}</li>
        </ul>
      </div>

      <!-- Form -->
      <div class="bg-white shadow-lg rounded-lg p-6">
        <form @submit.prevent="submit">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Full Name -->
            <div class="md:col-span-2">
              <label class="form-label">Full Name *</label>
              <input v-model="form.full_name" type="text" class="form-input" required />
            </div>

            <!-- Email -->
            <div class="md:col-span-2">
              <label class="form-label">Email *</label>
              <input v-model="form.email" type="email" class="form-input" required />
            </div>

            <!-- Phone -->
            <div>
              <label class="form-label">Contact Phone</label>
              <input v-model="form.contact_phone" type="text" class="form-input" />
            </div>

            <!-- Registration Date -->
            <div>
              <label class="form-label">Registration Date *</label>
              <input v-model="form.registration_date" type="date" class="form-input" required />
            </div>

            <!-- Interested Course -->
            <div>
              <label class="form-label">Interested Course</label>
              <input v-model="form.interested_course" type="text" class="form-input" />
            </div>

            <!-- Heard About Us -->
            <div>
              <label class="form-label">Heard About Us</label>
              <select v-model="form.heard_about_us" class="form-input">
                <option value="">Select option</option>
                <option value="friend">Friend</option>
                <option value="social_media">Social Media</option>
                <option value="website">Website</option>
                <option value="advertisement">Advertisement</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Status -->
            <div>
              <label class="form-label">Status *</label>
              <select v-model="form.status" class="form-input" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="terminated">Terminated</option>
                <option value="completed">Completed</option>
              </select>
            </div>

            <!-- Termination Date -->
            <div v-if="['terminated', 'completed'].includes(form.status)">
              <label class="form-label">Termination Date *</label>
              <input
                v-model="form.termination_date"
                type="date"
                class="form-input"
                :required="['terminated', 'completed'].includes(form.status)"
              />
            </div>

            <!-- Location -->
            <div>
              <label class="form-label">Location *</label>
              <select v-model="form.location_id" class="form-input" required>
                <option value="">Select Location</option>
                <option v-for="loc in locations" :key="loc.id" :value="loc.id">
                  {{ loc.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- Buttons -->
          <div class="mt-8 flex justify-end space-x-4">
            <Link :href="route('students.index')" class="btn-cancel">Cancel</Link>
            <button type="submit" class="btn-primary" :disabled="form.processing">
              {{ form.processing ? "Creating..." : "Create Student" }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  locations: Array
});

const form = useForm({
  full_name: "",
  email: "",
  contact_phone: "",
  registration_date: "",
  interested_course: "",
  heard_about_us: "",
  status: "active",
  termination_date: "",
  location_id: "",
});

const submit = () => {
  form.post(route('students.store'), {
    preserveScroll: true
  });
};
</script>

<style>
.form-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.form-input {
  @apply mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
  focus:ring-indigo-500 focus:border-indigo-500;
}

.btn-primary {
  @apply bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition;
}

.btn-cancel {
  @apply bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition;
}
</style>
