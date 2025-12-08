<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl text-gradient bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-green-600">
          Edit Enrollment
        </h2>
        <div class="flex items-center space-x-2">
          <div class="px-3 py-1 bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 rounded-full text-sm font-medium">
            ID: {{ enrollmentss?.id || '' }}
          </div>
        </div>
      </div>
    </template>

    <div class="max-w-4xl mx-auto p-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Enrollment</h1>
          <p class="text-gray-600">Update the enrollment information below</p>
        </div>

        <Link
          :href="route('enrollmentss.index')"
          class="group inline-flex items-center px-5 py-3 border-2 border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 font-medium rounded-xl hover:bg-emerald-50 transition-all duration-300"
        >
          <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Back to Enrollments
        </Link>
      </div>

      <!-- Form Container -->
      <div v-if="form" class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Form Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-gray-200">
          <div class="flex items-center">
            <div class="p-3 bg-white rounded-xl shadow-sm mr-4">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-gray-800">Edit Enrollment Information</h3>
              <p class="text-sm text-gray-600">Update the details and save your changes</p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
          <!-- Title -->
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Title <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </div>
              <input 
                v-model="form.title" 
                placeholder="Enter enrollment title"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
            </div>
            <div v-if="form.errors.title" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.title }}
            </div>
          </div>

          <!-- Description -->
          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
              Description
            </label>
            <div class="relative">
              <div class="absolute top-3 left-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
              </div>
              <textarea 
                id="description"
                v-model="form.description" 
                placeholder="Enter a brief description"
                rows="4"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              ></textarea>
            </div>
            <div v-if="form.errors.description" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.description }}
            </div>
          </div>

          <!-- Course -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Course <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
              </div>
              <select 
                v-model="form.course_id" 
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl bg-white shadow-sm transition-all outline-none appearance-none"
              >
                <option :value="null" class="text-gray-500">Select Course</option>
                <option v-for="c in courses" :key="c.id" :value="c.id" class="text-gray-700">
                  {{ c.course_name }}
                </option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
            <div v-if="form.errors.course_id" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.course_id }}
            </div>
          </div>

          <!-- Course Type -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Course Type <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
              </div>
              <select 
                v-model="form.course_type_id" 
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 rounded-xl bg-white shadow-sm transition-all outline-none appearance-none"
              >
                <option :value="null" class="text-gray-500">Select Course Type</option>
                <option v-for="ct in courseTypes" :key="ct.id" :value="ct.id" class="text-gray-700">
                  {{ ct.course_type }}
                </option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
            <div v-if="form.errors.course_type_id" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.course_type_id }}
            </div>
          </div>

          <!-- Company Address -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Company Address <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <select 
                v-model="form.company_address_id" 
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 rounded-xl bg-white shadow-sm transition-all outline-none appearance-none"
              >
                <option :value="null" class="text-gray-500">Select Company Address</option>
                <option v-for="a in companyAddresses" :key="a.id" :value="a.id" class="text-gray-700">
                  {{ a.city }}
                </option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
            <div v-if="form.errors.company_address_id" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.company_address_id }}
            </div>
          </div>

          <!-- Organization (NEW FIELD ADDED) -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Organization (optional)
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <input 
                v-model="form.organization"
                placeholder="Organization (optional)"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
            </div>
            <div v-if="form.errors.organization" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.organization }}
            </div>
          </div>

          <!-- Student Capacity -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Student Capacity
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
              <input 
                v-model.number="form.student_capacity" 
                type="number" 
                placeholder="Student Capacity" 
                min="0"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
            </div>
            <div v-if="form.errors.student_capacity" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.student_capacity }}
            </div>
          </div>

          <!-- Amount to be Paid -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Amount to be Paid
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <input 
                v-model.number="form.amount_to_be_paid"
                type="number"
                placeholder="Amount"
                min="0"
                step="0.01"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500">$</span>
              </div>
            </div>
            <div v-if="form.errors.amount_to_be_paid" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.amount_to_be_paid }}
            </div>
          </div>

          <!-- Completion Status -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Completion Status
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <select 
                v-model="form.completion_status" 
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl bg-white shadow-sm transition-all outline-none appearance-none"
              >
                <option v-for="status in statusOptions" :key="status" :value="status" class="text-gray-700">
                  {{ status }}
                </option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
            <div v-if="form.errors.completion_status" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.completion_status }}
            </div>
          </div>

          <!-- Telegram Link -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Telegram Link
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                </svg>
              </div>
              <input 
                v-model="form.telegram_link" 
                placeholder="Telegram Link"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
            </div>
            <div v-if="form.errors.telegram_link" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.telegram_link }}
            </div>
          </div>

          <!-- Enrollment Date -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Enrollment Date
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <input 
                v-model="form.enrollment_date" 
                type="date"
                class="pl-10 pr-4 py-3 w-full border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-xl bg-white shadow-sm transition-all outline-none"
              />
            </div>
            <div v-if="form.errors.enrollment_date" class="mt-2 text-sm text-red-600 font-medium flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.enrollment_date }}
            </div>
          </div>

          <!-- Submit Button -->
          <div class="md:col-span-2 pt-6 border-t border-gray-200">
            <button
              type="submit"
              class="group w-full md:w-auto px-8 py-4 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              Update Enrollment
            </button>
          </div>
        </form>
      </div>

      <div v-else class="text-center py-12">
        <div class="inline-block p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl mb-4">
          <svg class="w-12 h-12 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </div>
        <p class="text-gray-600 font-medium">Loading enrollment data...</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

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
  organization: '',          // <-- ADDED HERE
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
      form.organization = e.organization || ''   // <-- ADDED HERE
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
  form.put(route('enrollmentss.update', props.enrollmentss.id))
}
</script>

<style scoped>
.text-gradient {
  background-clip: text;
  -webkit-background-clip: text;
}

/* Form field focus effects */
input:focus, select:focus, textarea:focus {
  transition: all 0.2s ease;
}

/* Custom select arrow */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}

/* Number input spinner */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  opacity: 1;
  height: auto;
}

/* Date input custom style */
input[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
  cursor: pointer;
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
}

/* Smooth transitions for all interactive elements */
button, input, select, textarea, a {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Loading animation */
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Error message animation */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
  20%, 40%, 60%, 80% { transform: translateX(2px); }
}

.text-red-600 {
  animation: shake 0.5s ease-in-out;
}

/* Form validation styling */
input:invalid, select:invalid, textarea:invalid {
  border-color: #f87171;
}

input:valid, select:valid, textarea:valid {
  border-color: #34d399;
}
</style>