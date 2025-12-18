<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Student Profile
          </h2>
          <p class="text-gray-600 text-sm mt-1">Manage student information and enrollments</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="px-3 py-1 rounded-full text-sm font-medium" :class="getStatusBadgeClass('active')">
            Active Student
          </div>
        </div>
      </div>
    </template>

    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <!-- Flash Messages -->
      <div v-if="$page.props.flash?.success" class="mb-6 animate-fade-in">
        <div class="bg-sky-50 border-l-4 border-sky-500 p-4 rounded-r-lg shadow-sm">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-sky-700">{{ $page.props.flash.success }}</p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="$page.props.flash?.error" class="mb-6 animate-fade-in">
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-rose-700">{{ $page.props.flash.error }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Header Actions -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 p-5 bg-gradient-to-r from-sky-50 to-white rounded-xl shadow-sm border border-gray-100">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-3xl font-bold text-gray-900">{{ student.full_name }}</h1>
          <div class="flex items-center mt-2 text-gray-600">
            <svg class="h-5 w-5 mr-1.5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            <span class="mr-4">{{ student.email }}</span>
            <svg class="h-5 w-5 mr-1.5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
            </svg>
            <span>{{ student.contact_phone || 'No phone' }}</span>
          </div>
        </div>
        <div class="flex flex-wrap gap-3">
          <Link :href="route('students.index')" class="flex items-center bg-white text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-all duration-200 border border-gray-200 shadow-sm hover:shadow">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Students
          </Link>

          <Link v-if="canEdit" :href="route('students.edit', student.id)"
            class="flex items-center bg-gradient-to-r from-sky-500 to-sky-600 text-white px-4 py-2.5 rounded-lg hover:from-sky-600 hover:to-sky-700 transition-all duration-200 shadow hover:shadow-md">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Profile
          </Link>

          <button v-if="canDelete" @click="deleteStudent"
            class="flex items-center bg-gradient-to-r from-rose-500 to-rose-600 text-white px-4 py-2.5 rounded-lg hover:from-rose-600 hover:to-rose-700 transition-all duration-200 shadow hover:shadow-md">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Profile Info -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Student Info Card -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-sky-500 to-sky-600">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Information
              </h2>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ student.full_name }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ student.email }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ student.contact_phone || 'N/A' }}</span>
                    </div>
                  </div>
                </div>
                
                <div class="space-y-5">
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ student.location?.name || 'No Location' }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Registration Date</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ formatDate(student.registration_date) }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Referral Source</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ formatHeardAboutUs(student.heard_about_us) || 'N/A' }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Interested Course</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                      <svg class="h-5 w-5 text-sky-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                      </svg>
                      <span class="font-medium text-gray-900">{{ student.interested_course || 'N/A' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Current Enrollments Card -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Current Enrollments
              </h2>
            </div>
            <div class="p-6">
              <div v-if="student.enrollments?.length">
                <div class="grid grid-cols-1 gap-4">
                  <div v-for="enrollment in student.enrollments" :key="enrollment.id" 
                       class="p-5 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                      <div class="mb-3 sm:mb-0">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ enrollment.course?.course_name || 'N/A' }}</h3>
                        <div class="flex items-center text-gray-600 text-sm">
                          <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                          </svg>
                          <span>Enrolled on {{ formatDate(enrollment.enrollment_date) }}</span>
                        </div>
                      </div>
                      <div class="flex items-center">
                        <span :class="getStatusBadgeClass(enrollment.completion_status)">
                          {{ enrollment.completion_status || 'Active' }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <p v-else class="text-gray-500 p-8 text-center bg-gray-50 rounded-lg">
                <svg class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                No current enrollments found for this student.
              </p>
            </div>
          </div>

          <!-- Dropout History Card -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-sky-500 to-sky-600">
              <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                  <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                  Dropout History
                </h2>
                <span class="bg-sky-100 text-sky-800 text-sm font-bold px-3 py-1 rounded-full">
                  {{ student.dropout_history?.length || 0 }} records
                </span>
              </div>
            </div>
            <div class="p-6">
              <div v-if="student.dropout_history?.length">
                <div class="grid grid-cols-1 gap-4">
                  <div v-for="record in student.dropout_history" :key="record.id" 
                       class="p-5 border border-gray-100 bg-sky-50 rounded-xl hover:bg-sky-100 transition-all duration-200">
                    <div class="flex flex-col">
                      <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-900">{{ record.enrollment?.course?.course_name || 'Unknown Course' }}</h3>
                        <div class="text-sm text-sky-600 font-medium">
                          <svg class="h-4 w-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                          </svg>
                          Dropped Out
                        </div>
                      </div>
                      <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dropout Reason</label>
                        <p class="text-gray-900 bg-white p-3 rounded-lg border border-gray-200">
                          {{ record.reason || 'No reason provided' }}
                        </p>
                      </div>
                      <div class="text-sm text-gray-500 flex items-center">
                        <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Dropped on {{ formatDate(record.created_at) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <p v-else class="text-gray-500 p-8 text-center bg-gray-50 rounded-lg">
                <svg class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                No dropout history found for this student.
              </p>
            </div>
          </div>
        </div>

        <!-- Right Column: Management Panel -->
        <div class="space-y-8">
          <!-- Enrollment Management Card -->
          <div v-if="canAssign" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-6">
            <div class="px-6 py-5 bg-gradient-to-r from-indigo-500 to-indigo-600">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Manage Enrollments
              </h2>
            </div>
            <div class="p-6">
              <div v-if="availableEnrollments?.length">
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Select Enrollment to Manage</label>
                  <div class="relative">
                    <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <select v-model="selectedEnrollment" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white">
                      <option value="" disabled selected>Choose an enrollment...</option>
                      <option v-for="enrollment in availableEnrollments" :key="enrollment.id" :value="enrollment.id">
                        {{ enrollment.course?.course_name || 'N/A' }} 
                        ({{ enrollment.completion_status || 'Active' }})
                      </option>
                    </select>
                  </div>
                </div>

                <div class="space-y-3">
                  <button @click="assignEnrollment"
                    :disabled="!selectedEnrollment"
                    :class="selectedEnrollment ? 'bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700' : 'bg-gray-300 cursor-not-allowed'"
                    class="w-full text-white px-4 py-3.5 rounded-xl font-medium transition-all duration-200 flex items-center justify-center shadow hover:shadow-md">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Assign Enrollment
                  </button>

                  <button @click="unassignEnrollment"
                    :disabled="!selectedEnrollment"
                    :class="selectedEnrollment ? 'bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700' : 'bg-gray-300 cursor-not-allowed'"
                    class="w-full text-white px-4 py-3.5 rounded-xl font-medium transition-all duration-200 flex items-center justify-center shadow hover:shadow-md">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    Unassign (Dropout)
                  </button>
                </div>
                
                <div class="mt-6 p-4 bg-sky-50 border border-sky-100 rounded-xl">
                  <div class="flex items-start">
                    <svg class="h-5 w-5 text-sky-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-sky-700">
                      <strong>Note:</strong> Unassigning will mark the student as dropped out from the selected course and require a reason.
                    </p>
                  </div>
                </div>
              </div>
              
              <p v-else class="text-gray-500 p-6 text-center bg-gray-50 rounded-lg">
                <svg class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                No available enrollments to assign.
              </p>
            </div>
          </div>

          <!-- Quick Stats Card -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-600 to-slate-700">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Student Overview
              </h2>
            </div>
            <div class="p-6">
              <div class="space-y-5">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                  <div class="flex items-center">
                    <div class="p-2 bg-sky-100 rounded-lg mr-4">
                      <svg class="h-6 w-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-600">Current Enrollments</p>
                      <p class="text-2xl font-bold text-gray-900">{{ student.enrollments?.length || 0 }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                  <div class="flex items-center">
                    <div class="p-2 bg-sky-100 rounded-lg mr-4">
                      <svg class="h-6 w-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-600">Dropout Records</p>
                      <p class="text-2xl font-bold text-gray-900">{{ student.dropout_history?.length || 0 }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                  <div class="flex items-center">
                    <div class="p-2 bg-sky-100 rounded-lg mr-4">
                      <svg class="h-6 w-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-600">Member Since</p>
                      <p class="text-lg font-bold text-gray-900">{{ formatDate(student.registration_date).split(' ')[0] }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  student: Object,
  availableEnrollments: Array,
});

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

// Admin & Super-Admin
const canEdit = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));
const canDelete = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));
const canAssign = computed(() => ["Super-Admin", "Admin"].includes(user.value.role));

const selectedEnrollment = ref("");
const reassigning = ref(false);

function deleteStudent() {
  if (confirm(`Are you sure you want to delete ${props.student.full_name}? This action cannot be undone.`)) {
    router.delete(route('students.destroy', props.student.id));
  }
}

function assignEnrollment() {
  if (!selectedEnrollment.value) {
    alert("Please select an enrollment to assign.");
    return;
  }
  router.post(`/students/${props.student.id}/assign`, {
    enrollment_id: selectedEnrollment.value,
  });
}

function unassignEnrollment() {
  if (!selectedEnrollment.value) {
    alert("Please select an enrollment to unassign.");
    return;
  }
  
  const reason = prompt("Please enter the dropout reason:", "Personal reasons");
  if (!reason) {
    alert("Dropout reason is required. Operation cancelled.");
    return;
  }
  
  if (confirm(`Are you sure you want to mark this student as dropped out?`)) {
    router.post(`/students/${props.student.id}/unassign`, {
      enrollment_id: selectedEnrollment.value,
      reason,
    });
  }
}

function formatDate(dateStr) {
  if (!dateStr) return "N/A";
  return new Date(dateStr).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

function formatHeardAboutUs(value) {
  if (!value) return 'N/A';
  const formats = {
    'friend': 'Friend',
    'social_media': 'Social Media',
    'website': 'Website',
    'advertisement': 'Advertisement',
    'other': 'Other'
  };
  return formats[value] || value.replace('_', ' ');
}

function getStatusBadgeClass(status) {
  const statusLower = status?.toLowerCase() || 'active';
  const statusClasses = {
    'completed': 'bg-sky-100 text-sky-800 px-3 py-1.5 rounded-lg text-sm font-bold',
    'in progress': 'bg-blue-100 text-blue-800 px-3 py-1.5 rounded-lg text-sm font-bold',
    'pending': 'bg-sky-100 text-sky-800 px-3 py-1.5 rounded-lg text-sm font-bold',
    'cancelled': 'bg-rose-100 text-rose-800 px-3 py-1.5 rounded-lg text-sm font-bold',
    'not started': 'bg-gray-100 text-gray-800 px-3 py-1.5 rounded-lg text-sm font-bold',
    'active': 'bg-sky-100 text-sky-800 px-3 py-1.5 rounded-lg text-sm font-bold'
  };
  return statusClasses[statusLower] || 'bg-gray-100 text-gray-800 px-3 py-1.5 rounded-lg text-sm font-bold';
}
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}
</style>