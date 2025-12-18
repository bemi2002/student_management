<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Student</h2>
    </template>

    <div class="container mx-auto px-4 py-8">
      <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-sky-600 to-blue-500 rounded-xl shadow-lg mb-8 overflow-hidden">
          <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
              <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-white">Edit Student</h1>
                <p class="text-sky-100 mt-2">Update student information in the system</p>
              </div>
              <Link 
                :href="route('students.index')" 
                class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center space-x-2 group"
              >
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Students</span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Validation Errors -->
        <div 
          v-if="Object.keys(form.errors).length" 
          class="bg-gradient-to-r from-rose-50 to-red-50 border-l-4 border-rose-500 shadow-md rounded-r-lg mb-8 animate-pulse-once"
        >
          <div class="p-6">
            <div class="flex items-center mb-3">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="ml-3 text-lg font-semibold text-rose-800">Please fix the following errors:</h3>
            </div>
            <ul class="mt-2 list-disc list-inside space-y-1">
              <li v-for="(error, key) in form.errors" :key="key" class="text-rose-700 font-medium">
                {{ error[0] }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
          <!-- Card Header -->
          <div class="bg-gradient-to-r from-gray-50 to-sky-50 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
              <div class="p-3 bg-sky-100 rounded-xl">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Student Information</h2>
                <p class="text-gray-600">Update the student details below</p>
              </div>
            </div>
          </div>

          <!-- Form Content -->
          <div class="p-8">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Full Name -->
                <div class="md:col-span-2">
                  <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Full Name
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.full_name"
                      type="text"
                      id="full_name"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Enter full name"
                      required
                    />
                  </div>
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Email Address
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.email"
                      type="email"
                      id="email"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="student@example.com"
                      required
                    />
                  </div>
                </div>

                <!-- Contact Phone -->
                <div>
                  <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Contact Phone
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.contact_phone"
                      type="text"
                      id="contact_phone"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="+1 (555) 123-4567"
                    />
                  </div>
                </div>

                <!-- Registration Date -->
                <div>
                  <label for="registration_date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Registration Date
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.registration_date"
                      type="date"
                      id="registration_date"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      required
                    />
                  </div>
                </div>

                <!-- Interested Course -->
                <div>
                  <label for="interested_course" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Interested Course
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.interested_course"
                      type="text"
                      id="interested_course"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="e.g., Web Development, Data Science"
                    />
                  </div>
                </div>

                <!-- Heard About Us -->
                <div>
                  <label for="heard_about_us" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Heard About Us
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.heard_about_us"
                      id="heard_about_us"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                    >
                      <option value="" class="text-gray-400">Select an option</option>
                      <option value="friend" class="text-gray-700">Friend</option>
                      <option value="social_media" class="text-gray-700">Social Media</option>
                      <option value="website" class="text-gray-700">Website</option>
                      <option value="other" class="text-gray-700">Other</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Location -->
                <div>
                  <label for="location_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Location
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.location_id"
                      id="location_id"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                      required
                    >
                      <option value="" class="text-gray-400">Select a location</option>
                      <option 
                        v-for="location in locations" 
                        :key="location.id" 
                        :value="location.id"
                        class="text-gray-700"
                      >
                        {{ location.name }}
                      </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-500">
                  <span class="font-semibold">Last Updated:</span> {{ new Date().toLocaleDateString() }}
                </div>
                <div class="flex space-x-4">
                  <Link 
                    :href="route('students.index')" 
                    class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-8 py-3 rounded-xl shadow-sm hover:shadow-md hover:from-gray-200 hover:to-gray-300 transition-all duration-300 font-semibold flex items-center space-x-2"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Cancel</span>
                  </Link>
                  <button 
                    type="submit" 
                    class="bg-gradient-to-r from-sky-600 to-blue-600 text-white px-10 py-3 rounded-xl shadow-lg hover:shadow-xl hover:from-sky-700 hover:to-blue-700 transition-all duration-300 font-semibold flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="form.processing"
                    :class="{ 'cursor-not-allowed': form.processing }"
                  >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ form.processing ? 'Updating...' : 'Update Student' }}</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  student: { type: Object, required: true },
  locations: { type: Array, required: true }
})

const form = useForm({
  full_name: props.student.full_name,
  email: props.student.email,
  contact_phone: props.student.contact_phone || '',
  registration_date: props.student.registration_date,
  interested_course: props.student.interested_course || '',
  heard_about_us: props.student.heard_about_us || '',
  location_id: props.student.location_id || '',
})

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

<style>
@keyframes pulse-once {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
}

.animate-pulse-once {
  animation: pulse-once 1s ease-in-out;
}

input:focus, select:focus {
  outline: none;
}

/* Custom scrollbar for select elements */
select::-webkit-scrollbar {
  width: 8px;
}

select::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

select::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

select::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>