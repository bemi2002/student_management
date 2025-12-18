<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Enrollment</h2>
    </template>

    <div class="container mx-auto px-4 py-8">
      <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-sky-600 to-blue-500 rounded-xl shadow-lg mb-8 overflow-hidden">
          <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
              <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-white">Edit Enrollment</h1>
                <p class="text-sky-100 mt-2">Update enrollment information in the system</p>
              </div>
              <Link 
                :href="route('enrollmentss.index')" 
                class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center space-x-2 group"
              >
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Enrollments</span>
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
                {{ error }}
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Enrollment Information</h2>
                <p class="text-gray-600">Update the enrollment details below</p>
              </div>
            </div>
          </div>

          <!-- Form Content -->
          <div class="p-8">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Title -->
                <div class="md:col-span-2">
                  <label for="title" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Title
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.title"
                      type="text"
                      id="title"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Enter enrollment title"
                      required
                    />
                  </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                  <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Description
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                      </svg>
                    </div>
                    <textarea
                      v-model="form.description"
                      id="description"
                      rows="4"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Enter a brief description"
                    ></textarea>
                  </div>
                </div>

                <!-- Course -->
                <div>
                  <label for="course_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Course
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.course_id"
                      id="course_id"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                      required
                    >
                      <option :value="null" class="text-gray-400">Select Course</option>
                      <option v-for="c in courses" :key="c.id" :value="c.id" class="text-gray-700">
                        {{ c.course_name }}
                      </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Course Type -->
                <div>
                  <label for="course_type_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Course Type
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.course_type_id"
                      id="course_type_id"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                      required
                    >
                      <option :value="null" class="text-gray-400">Select Course Type</option>
                      <option v-for="ct in courseTypes" :key="ct.id" :value="ct.id" class="text-gray-700">
                        {{ ct.course_type }}
                      </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Company Address -->
                <div>
                  <label for="company_address_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <span class="bg-gradient-to-r from-sky-600 to-blue-600 text-white text-xs font-bold px-2 py-1 rounded mr-2">REQUIRED</span>
                    Company Address
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.company_address_id"
                      id="company_address_id"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                      required
                    >
                      <option :value="null" class="text-gray-400">Select Company Address</option>
                      <option v-for="a in companyAddresses" :key="a.id" :value="a.id" class="text-gray-700">
                        {{ a.city }}
                      </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Organization -->
                <div>
                  <label for="organization" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Organization
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.organization"
                      type="text"
                      id="organization"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Organization (optional)"
                    />
                  </div>
                </div>

                <!-- Student Capacity -->
                <div>
                  <label for="student_capacity" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Student Capacity
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                    </div>
                    <input
                      v-model.number="form.student_capacity"
                      type="number"
                      id="student_capacity"
                      min="0"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Student Capacity"
                    />
                  </div>
                </div>

                <!-- Amount to be Paid -->
                <div>
                  <label for="amount_to_be_paid" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Amount to be Paid
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <input
                      v-model.number="form.amount_to_be_paid"
                      type="number"
                      id="amount_to_be_paid"
                      min="0"
                      step="0.01"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Amount"
                    />
                  </div>
                </div>

                <!-- Completion Status -->
                <div>
                  <label for="completion_status" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Completion Status
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <select
                      v-model="form.completion_status"
                      id="completion_status"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300 appearance-none"
                    >
                      <option v-for="status in statusOptions" :key="status" :value="status" class="text-gray-700">
                        {{ status }}
                      </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Telegram Link -->
                <div>
                  <label for="telegram_link" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Telegram Link
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.telegram_link"
                      type="text"
                      id="telegram_link"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                      placeholder="Telegram Link"
                    />
                  </div>
                </div>

                <!-- Enrollment Date -->
                <div>
                  <label for="enrollment_date" class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-sky-600">•</span> Enrollment Date
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <input
                      v-model="form.enrollment_date"
                      type="date"
                      id="enrollment_date"
                      class="pl-10 w-full border-2 border-gray-200 rounded-xl shadow-sm p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all duration-300"
                    />
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
                    :href="route('enrollmentss.index')" 
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
                    <span>{{ form.processing ? 'Updating...' : 'Update Enrollment' }}</span>
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
import { watch } from 'vue'

const props = defineProps({
  enrollmentss: Object,
  courses: Array,
  courseTypes: Array,
  companyAddresses: Array,
})

const statusOptions = ['Not Started', 'In Progress', 'Completed']

const form = useForm({
  title: '',
  description: '',
  course_id: null,
  course_type_id: null,
  company_address_id: null,
  organization: '',
  student_capacity: '',
  amount_to_be_paid: '',
  completion_status: '',
  telegram_link: '',
  enrollment_date: '',
})

watch(
  () => props.enrollmentss,
  (e) => {
    if (e) {
      form.title = e.title || ''
      form.description = e.description || ''
      form.course_id = e.course_id
      form.course_type_id = e.course_type_id
      form.company_address_id = e.company_address_id
      form.organization = e.organization || ''
      form.student_capacity = e.student_capacity
      form.amount_to_be_paid = e.amount_to_be_paid
      form.completion_status = e.completion_status || 'Not Started'
      form.telegram_link = e.telegram_link || ''
      form.enrollment_date = e.enrollment_date
        ? new Date(e.enrollment_date).toISOString().split('T')[0]
        : ''
    }
  },
  { immediate: true }
)

function submit() {
  form.put(route('enrollmentss.update', props.enrollmentss.id), {
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

input:focus, select:focus, textarea:focus {
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