<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
          <!-- Back Navigation -->
          <div class="mb-6">
            <button 
              @click="$inertia.visit(route('enrollmentss.index'))"
              class="group inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors"
            >
              <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Back to Enrollments
            </button>
          </div>
          
          <!-- Main Header -->
          <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
              <div class="flex-1">
                <div class="flex items-start gap-4">
                  <div class="p-3 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ enrollment.title }}</h1>
                    <p class="text-gray-600 mb-4 max-w-3xl">{{ enrollment.description }}</p>
                    <div class="flex flex-wrap gap-2">
                      <span :class="getStatusBadgeClass(enrollment.completion_status)" 
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                        {{ enrollment.completion_status || 'Not specified' }}
                      </span>
                      <span v-if="enrollment.course?.course_name" 
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ enrollment.course.course_name }}
                      </span>
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ formatDate(enrollment.enrollment_date) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Action Buttons -->
              <div class="flex flex-col sm:flex-row gap-3">
                <button 
                  @click="refreshData" 
                  :disabled="loading"
                  class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                >
                  <svg class="w-5 h-5 mr-2" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                  {{ loading ? 'Refreshing...' : 'Refresh' }}
                </button>
                
                <Link 
                  :href="route('enrollmentss.edit', enrollment.id)"
                  class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 border border-transparent rounded-lg font-medium text-white hover:from-blue-700 hover:to-cyan-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  Edit Enrollment
                </Link>
              </div>
            </div>
          </div>

          <!-- Stats Overview -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
              <div class="flex items-center">
                <div class="p-3 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-lg mr-4">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Capacity</p>
                  <p class="text-2xl font-bold text-gray-900">
                    {{ assignedStudents.length }} / {{ enrollment.student_capacity || '∞' }}
                  </p>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
              <div class="flex items-center">
                <div class="p-3 bg-gradient-to-r from-green-100 to-emerald-100 rounded-lg mr-4">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Amount</p>
                  <p class="text-2xl font-bold text-gray-900">{{ (enrollment.amount_to_be_paid) }}</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
              <div class="flex items-center">
                <div class="p-3 bg-gradient-to-r from-amber-100 to-yellow-100 rounded-lg mr-4">
                  <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Location</p>
                  <p class="text-lg font-semibold text-gray-900">{{ enrollment.company_address?.city || 'N/A' }}</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
              <div class="flex items-center">
                <div class="p-3 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-lg mr-4">
                  <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.14.141-.259.259-.374.261l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.136-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Telegram</p>
                  <a v-if="enrollment.telegram_link" 
                     :href="enrollment.telegram_link" 
                     target="_blank"
                     class="text-lg font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                    Join Group
                  </a>
                  <p v-else class="text-lg font-semibold text-gray-500">Not linked</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Flash Messages -->
        <div class="space-y-3 mb-6">
          <div v-if="successMessage" class="rounded-xl p-4 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 shadow-sm">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800">{{ successMessage }}</p>
              </div>
            </div>
          </div>
          
          <div v-if="errorMessage" class="rounded-xl p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 shadow-sm">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Primary Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-4">
          <button 
            @click="showModal = true"
            class="group inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
          >
            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Manage Students
          </button>

          <button 
            @click="showDropoutHistory = true"
            class="group inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
          >
            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            Dropout History
          </button>
        </div>

        <!-- Main Management Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-2xl font-bold text-gray-900">Student Management</h3>
                    <p class="text-gray-600">{{ enrollment.title }}</p>
                  </div>
                </div>
                <button 
                  @click="showModal = false"
                  class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-colors"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Enrollment Info -->
                <div class="space-y-6">
                  <!-- Info Card -->
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                      <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      Enrollment Details
                    </h4>
                    <div class="space-y-3">
                      <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        <span :class="getStatusBadgeClass(enrollment.completion_status)" 
                              class="px-3 py-1 rounded-full text-xs font-medium">
                          {{ enrollment.completion_status || 'Not specified' }}
                        </span>
                      </div>
                      
                      <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Capacity</span>
                        <div class="flex items-center gap-2">
                          <span class="text-sm font-semibold text-gray-900">
                            {{ assignedStudents.length }}/{{ enrollment.student_capacity || '∞' }}
                          </span>
                          <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div :style="{ width: `${Math.min((assignedStudents.length / (enrollment.student_capacity || 1)) * 100, 100)}%` }" 
                                 class="h-full bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Amount</span>
                        <span class="text-sm font-semibold text-gray-900">{{(enrollment.amount_to_be_paid) }}</span>
                      </div>
                      
                      <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Course</span>
                        <span class="text-sm font-semibold text-gray-900">{{ enrollment.course?.course_name || 'N/A' }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Quick Actions -->
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                      <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                      </svg>
                      Quick Actions
                    </h4>
                    <div class="space-y-3">
                      <button 
                        @click="refreshData"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                      >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                      </button>
                      
                      <a 
                        v-if="enrollment.telegram_link" 
                        :href="enrollment.telegram_link" 
                        target="_blank"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                      >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.14.141-.259.259-.374.261l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.136-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                        </svg>
                        Join Telegram Group
                      </a>

                      <button 
                        @click="showDropoutHistory = true; showModal = false;"
                        class="w-full flex items-center justify-center px-4 py-3 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                      >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        View Dropout History
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Right Column - Students -->
                <div class="lg:col-span-2 space-y-6">
                  <!-- Assigned Students -->
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                      <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                          <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                          </svg>
                          Assigned Students
                        </h4>
                        <span class="text-sm font-medium text-gray-700 bg-white px-3 py-1 rounded-full">
                          {{ assignedStudents.length }} students
                        </span>
                      </div>
                    </div>
                    
                    <div class="p-6">
                      <!-- Loading State -->
                      <div v-if="loading" class="text-center py-8">
                        <div class="inline-flex items-center justify-center">
                          <div class="w-10 h-10 border-3 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Loading students...</p>
                      </div>
                      
                      <!-- Empty State -->
                      <div v-else-if="assignedStudents.length === 0" class="text-center py-8">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mb-4">
                          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                          </svg>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-2">No students assigned</h4>
                        <p class="text-sm text-gray-600 mb-4">Assign students to get started</p>
                      </div>
                      
                      <!-- Students List -->
                      <div v-else class="space-y-3">
                        <div v-for="student in assignedStudents" :key="student.id" 
                             class="group bg-gray-50 hover:bg-white border border-gray-200 rounded-lg p-4 transition-all duration-200 hover:shadow-md">
                          <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                              <div class="relative">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-full flex items-center justify-center">
                                  <span class="text-blue-700 font-bold text-sm">
                                    {{ getInitials(student.full_name) }}
                                  </span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-2 border-white rounded-full"></div>
                              </div>
                              <div>
                                <p class="font-semibold text-gray-900">{{ student.full_name }}</p>
                                <p class="text-sm text-gray-600">{{ student.email }}</p>
                                <div v-if="student.contact_phone" class="flex items-center mt-1">
                                  <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                  </svg>
                                  <span class="text-xs text-gray-500">{{ student.contact_phone }}</span>
                                </div>
                              </div>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                              <button 
                                @click="unassignStudent(student.id)" 
                                class="flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors"
                                :disabled="processingStudent === student.id"
                                title="Unassign Student"
                              >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span v-if="processingStudent === student.id">...</span>
                                <span v-else>Unassign</span>
                              </button>
                              <button 
                                @click="dropoutStudent(student.id)" 
                                class="flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 border border-red-600 rounded-lg transition-colors"
                                :disabled="processingStudent === student.id"
                                title="Mark as Dropout"
                              >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span v-if="processingStudent === student.id">...</span>
                                <span v-else>Dropout</span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Assign Students -->
                  <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                      <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Assign New Student
                      </h4>
                    </div>
                    
                    <div class="p-6">
                      <!-- Capacity Warning -->
                      <div v-if="isCapacityReached" class="mb-6 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start">
                          <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                          </svg>
                          <div>
                            <h4 class="text-sm font-medium text-amber-800">Enrollment Capacity Reached</h4>
                            <p class="text-sm text-amber-700 mt-1">
                              This enrollment has reached its maximum capacity of {{ enrollment.student_capacity }} students.
                              You cannot assign more students unless capacity is increased or existing students are removed.
                            </p>
                          </div>
                        </div>
                      </div>

                      <div v-else>
                        <div class="space-y-4">
                          <!-- Student Selection -->
                          <div>
                            <label for="student-select" class="block text-sm font-medium text-gray-700 mb-2">
                              Select Available Student
                            </label>
                            <div class="relative">
                              <select 
                                id="student-select"
                                v-model="selectedStudent" 
                                class="block w-full px-4 py-3 text-base border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                :disabled="loadingAvailable"
                              >
                                <option value="">Select a student...</option>
                                <option v-for="student in availableStudents" :key="student.id" :value="student.id">
                                  {{ student.full_name }} • {{ student.email }}
                                </option>
                              </select>
                              <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                              </div>
                            </div>
                            
                            <!-- Loading/Available Info -->
                            <div class="mt-2">
                              <div v-if="loadingAvailable" class="flex items-center text-sm text-gray-500">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading available students...
                              </div>
                              <div v-else-if="availableStudents.length === 0" class="text-sm text-gray-500">
                                No available students found
                              </div>
                              <div v-else class="text-sm text-gray-500">
                                {{ availableStudents.length }} students available for assignment
                              </div>
                            </div>
                          </div>
                          
                          <!-- Assign Button -->
                          <button
                            @click="assignStudent"
                            :disabled="!selectedStudent || assigningStudent"
                            class="w-full flex justify-center items-center px-6 py-3.5 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg"
                          >
                            <svg v-if="assigningStudent" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ assigningStudent ? 'Assigning Student...' : 'Assign Student' }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
              <div class="text-sm text-gray-600">
                Enrollment ID: <span class="font-semibold">{{ enrollment.id }}</span>
              </div>
              <div class="flex gap-3">
                <button 
                  @click="showModal = false"
                  class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                  Close
                </button>
                <button 
                  @click="refreshData"
                  class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                  Refresh Data
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Dropout History Modal -->
        <div v-if="showDropoutHistory" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 bg-gradient-to-r from-red-50 to-rose-50 border-b border-red-200">
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-white rounded-xl shadow-sm border border-red-200">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-2xl font-bold text-gray-900">Dropout History</h3>
                    <p class="text-gray-600">{{ enrollment.title }}</p>
                  </div>
                </div>
                <button 
                  @click="showDropoutHistory = false"
                  class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-colors"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Dropout History Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
              <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                  <div class="flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                      <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Student Dropout Records
                    </h4>
                    <span class="text-sm font-medium text-gray-700 bg-white px-3 py-1 rounded-full">
                      {{ dropoutHistory.length }} records
                    </span>
                  </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                  <!-- Loading State -->
                  <div v-if="loading" class="text-center py-8">
                    <div class="inline-flex items-center justify-center">
                      <div class="w-10 h-10 border-3 border-gray-200 border-t-red-600 rounded-full animate-spin"></div>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Loading dropout history...</p>
                  </div>
                  
                  <!-- Empty State -->
                  <div v-else-if="dropoutHistory.length === 0" class="text-center py-8">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center mb-4">
                      <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                      </svg>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No dropout history</h4>
                    <p class="text-sm text-gray-600 mb-4">No students have been dropped out from this enrollment yet.</p>
                  </div>
                  
                  <!-- Dropout List -->
                  <div v-else class="space-y-3">
                    <div v-for="record in dropoutHistory" :key="record.id" 
                         class="group bg-red-50/50 hover:bg-white border border-red-200 rounded-lg p-4 transition-all duration-200 hover:shadow-md">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                          <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                              <span class="text-red-700 font-bold text-sm">
                                {{ getInitials(record.student?.full_name) }}
                              </span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                              <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                              </svg>
                            </div>
                          </div>
                          <div>
                            <p class="font-semibold text-gray-900">{{ record.student?.full_name || 'Unknown Student' }}</p>
                            <p v-if="record.student?.email" class="text-sm text-gray-600">{{ record.student.email }}</p>
                            <div class="mt-2">
                              <p class="text-sm text-gray-700">
                                <span class="font-medium">Reason:</span> {{ record.reason }}
                              </p>
                              <p class="text-xs text-gray-500 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dropped out on {{ formatDate(record.created_at) }}
                              </p>
                            </div>
                          </div>
                        </div>
                        <button 
                          @click="reassignStudent(record.student_id)" 
                          class="flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 rounded-lg transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md"
                          :disabled="processingStudent === record.student_id"
                          v-if="record.student && !isCapacityReached"
                          title="Reassign Student"
                        >
                          <svg v-if="processingStudent === record.student_id" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                          </svg>
                          {{ processingStudent === record.student_id ? 'Reassigning...' : 'Reassign' }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
              <div class="text-sm text-gray-600">
                Total Dropouts: <span class="font-semibold text-red-600">{{ dropoutHistory.length }}</span>
              </div>
              <div class="flex gap-3">
                <button 
                  @click="showDropoutHistory = false"
                  class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                  Close
                </button>
                <button 
                  @click="showModal = true; showDropoutHistory = false;"
                  class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                  Back to Management
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  enrollment: Object,
  availableStudentsUrl: String,
  dropoutHistory: Array
})

// Modal states
const showModal = ref(false)
const showDropoutHistory = ref(false)

// Reactive variables
const selectedStudent = ref('')
const availableStudents = ref([])
const assignedStudents = ref([])
const dropoutHistory = ref([])
const loading = ref(false)
const loadingAvailable = ref(false)
const assigningStudent = ref(false)
const processingStudent = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

// FIXED: Better initialization with fallbacks
// Initialize assignedStudents from props with proper error handling
assignedStudents.value = Array.isArray(props.enrollment?.students) 
  ? [...props.enrollment.students] 
  : []

// Initialize dropout history from props with proper error handling
dropoutHistory.value = Array.isArray(props.dropoutHistory) 
  ? [...props.dropoutHistory] 
  : []

// Computed property to check capacity
const isCapacityReached = computed(() => {
  if (!props.enrollment?.student_capacity) return false
  return assignedStudents.value.length >= props.enrollment.student_capacity
})

// FIXED: Better watcher with null checks
watch(() => props.enrollment?.students, (newStudents) => {
  assignedStudents.value = Array.isArray(newStudents) ? [...newStudents] : []
}, { immediate: true, deep: true })

// Helper functions
const getInitials = (fullName) => {
  if (!fullName) return '??'
  return fullName
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const formatDate = (dateString) => {
  if (!dateString) return 'Not set'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatCurrency = (amount) => {
  if (!amount) return '$0.00'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

const getStatusBadgeClass = (status) => {
  const statusClasses = {
    'completed': 'bg-gradient-to-r from-green-100 to-emerald-100 text-emerald-800',
    'in progress': 'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800',
    'pending': 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800',
    'cancelled': 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800',
    'not started': 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800'
  }
  return statusClasses[status?.toLowerCase()] || 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800'
}

const showSuccess = (message) => {
  successMessage.value = message
  setTimeout(() => successMessage.value = '', 5000)
}

const showError = (message) => {
  errorMessage.value = message
  setTimeout(() => errorMessage.value = '', 5000)
}

const clearMessages = () => {
  successMessage.value = ''
  errorMessage.value = ''
}

// Data fetching functions
async function fetchAvailableStudents() {
  if (!props.availableStudentsUrl) return
  
  loadingAvailable.value = true
  try {
    const res = await axios.get(props.availableStudentsUrl)
    availableStudents.value = Array.isArray(res.data.data) ? [...res.data.data] : []
  } catch (err) {
    console.error('Failed to fetch available students:', err)
    availableStudents.value = []
    showError('Failed to load available students')
  } finally {
    loadingAvailable.value = false
  }
}

// FIXED: Better fetch for assigned students
async function fetchAssignedStudents() {
  loading.value = true
  try {
    const res = await axios.get(`/enrollmentss/${props.enrollment.id}`, {
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    // Debug log to see the response structure
    console.log('Enrollment API Response:', res.data)
    
    // Multiple fallback approaches to find students data
    if (res.data.enrollment?.students) {
      assignedStudents.value = Array.isArray(res.data.enrollment.students) 
        ? [...res.data.enrollment.students] 
        : []
    } else if (res.data.students) {
      assignedStudents.value = Array.isArray(res.data.students) 
        ? [...res.data.students] 
        : []
    } else if (res.data.data?.enrollment?.students) {
      assignedStudents.value = Array.isArray(res.data.data.enrollment.students) 
        ? [...res.data.data.enrollment.students] 
        : []
    } else {
      // If no students found, ensure it's an empty array
      assignedStudents.value = []
    }
    
    console.log('Final assigned students:', assignedStudents.value)
    
  } catch (err) {
    console.error('Failed to fetch assigned students:', err)
    showError('Failed to load assigned students')
    assignedStudents.value = [] // Ensure it's always an array
  } finally {
    loading.value = false
  }
}

async function fetchDropoutHistory() {
  try {
    const res = await axios.get(`/enrollmentss/${props.enrollment.id}`)
    if (res.data.dropoutHistory) {
      dropoutHistory.value = Array.isArray(res.data.dropoutHistory) 
        ? [...res.data.dropoutHistory] 
        : []
    }
  } catch (err) {
    console.error('Failed to fetch dropout history:', err)
    showError('Failed to load dropout history')
  }
}

// Student management functions
async function assignStudent() {
  if (!selectedStudent.value) return

  assigningStudent.value = true
  clearMessages()
  
  try {
    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/assign-student`, {
      student_id: selectedStudent.value
    })
    
    if (response.data.success) {
      let assignedStudentData = response.data.student
      
      if (!assignedStudentData) {
        assignedStudentData = availableStudents.value.find(student => student.id == selectedStudent.value)
      }
      
      if (assignedStudentData) {
        assignedStudents.value.push({...assignedStudentData})
        availableStudents.value = availableStudents.value.filter(student => student.id != selectedStudent.value)
        await nextTick()
      } else {
        await refreshData()
      }
      
      selectedStudent.value = ''
      showSuccess(response.data.message || 'Student assigned successfully!')
    } else {
      showError(response.data.message || 'Failed to assign student')
    }
    
  } catch (err) {
    console.error('Assignment error:', err)
    
    if (err.response?.data?.message) {
      showError(err.response.data.message)
    } else if (err.response?.data?.error) {
      showError(err.response.data.error)
    } else {
      showError('Failed to assign student. Please try again.')
    }
    
    await refreshData()
  } finally {
    assigningStudent.value = false
  }
}

// Unassign student (works like dropout but with default reason "Unassigned by admin")
async function unassignStudent(studentId) {
  const confirmed = confirm('Are you sure you want to unassign this student? This will mark them as dropout with reason "Unassigned by admin".')
  if (!confirmed) return

  processingStudent.value = studentId
  clearMessages()
  
  try {
    // First, get the student data before removing them
    const studentToUnassign = assignedStudents.value.find(student => student.id == studentId)
    
    if (!studentToUnassign) {
      showError('Student not found in assigned students')
      return
    }

    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/unassign-student`, {
      student_id: studentId,
      reason: 'Unassigned by admin' // Default reason
    })
    
    if (response.data.success) {
      // Remove from assigned students
      assignedStudents.value = assignedStudents.value.filter(student => student.id != studentId)
      await nextTick()
      
      // Create a temporary record with student data for immediate UI update
      const tempDropoutRecord = {
        id: `temp-${Date.now()}`,
        student_id: studentId,
        reason: 'Unassigned by admin',
        created_at: new Date().toISOString(),
        student: studentToUnassign
      }
      
      // Add to dropout history for immediate display
      dropoutHistory.value.unshift(tempDropoutRecord)
      
      // Refresh from server to get the actual record with proper relationships
      await fetchDropoutHistory()
      
      // Auto-open dropout history modal
      showDropoutHistory.value = true
      showModal.value = false
      
      showSuccess(response.data.message || 'Student unassigned successfully (marked as dropout)')
    } else {
      showError(response.data.message || 'Failed to unassign student')
    }
    
  } catch (err) {
    console.error('Unassign error:', err)
    
    if (err.response?.data?.message) {
      showError(err.response.data.message)
    } else if (err.response?.data?.error) {
      showError(err.response.data.error)
    } else {
      showError('Failed to unassign student. Please try again.')
    }
    
    await refreshData()
  } finally {
    processingStudent.value = null
  }
}

// Dropout student (custom reason)
async function dropoutStudent(studentId) {
  const reason = prompt('Please enter the dropout reason for this student:', '')
  if (reason === null) return // User cancelled
  if (!reason.trim()) {
    showError('Please enter a dropout reason')
    return
  }

  processingStudent.value = studentId
  clearMessages()
  
  try {
    // First, get the student data before removing them
    const studentToDropout = assignedStudents.value.find(student => student.id == studentId)
    
    if (!studentToDropout) {
      showError('Student not found in assigned students')
      return
    }

    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/unassign-student`, {
      student_id: studentId,
      reason: reason
    })
    
    if (response.data.success) {
      // Remove from assigned students
      assignedStudents.value = assignedStudents.value.filter(student => student.id != studentId)
      await nextTick()
      
      // Create a temporary record with student data for immediate UI update
      const tempDropoutRecord = {
        id: `temp-${Date.now()}`,
        student_id: studentId,
        reason: reason,
        created_at: new Date().toISOString(),
        student: studentToDropout
      }
      
      // Add to dropout history for immediate display
      dropoutHistory.value.unshift(tempDropoutRecord)
      
      // Refresh from server to get the actual record with proper relationships
      await fetchDropoutHistory()
      
      // Auto-open dropout history modal
      showDropoutHistory.value = true
      showModal.value = false
      
      showSuccess(response.data.message || 'Student marked as dropout successfully')
    } else {
      showError(response.data.message || 'Failed to mark student as dropout')
    }
    
  } catch (err) {
    console.error('Dropout error:', err)
    
    if (err.response?.data?.message) {
      showError(err.response.data.message)
    } else if (err.response?.data?.error) {
      showError(err.response.data.error)
    } else {
      showError('Failed to mark student as dropout. Please try again.')
    }
    
    await refreshData()
  } finally {
    processingStudent.value = null
  }
}

// Reassign student from dropout history
async function reassignStudent(studentId) {
  processingStudent.value = studentId
  clearMessages()
  
  try {
    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/assign-student`, {
      student_id: studentId
    })
    
    if (response.data.success) {
      // Remove from dropout history
      dropoutHistory.value = dropoutHistory.value.filter(record => record.student_id !== studentId)
      
      // Add to assigned students if student data is available
      if (response.data.student) {
        assignedStudents.value.push({...response.data.student})
      } else {
        await refreshData()
      }
      
      showSuccess('Student reassigned successfully!')
    } else {
      showError(response.data.message || 'Failed to reassign student')
    }
    
  } catch (err) {
    console.error('Reassign error:', err)
    
    if (err.response?.data?.message) {
      showError(err.response.data.message)
    } else {
      showError('Failed to reassign student. Please try again.')
    }
  } finally {
    processingStudent.value = null
  }
}

// Refresh functions
async function refreshData() {
  loading.value = true
  try {
    router.reload({ 
      only: ['enrollment', 'dropoutHistory'],
      preserveScroll: true,
      onSuccess: () => {
        showSuccess('Data refreshed successfully!')
      }
    })
  } catch (err) {
    console.error('Failed to refresh data:', err)
    showError('Failed to refresh data')
  } finally {
    loading.value = false
  }
}

async function refreshDataAxios() {
  loading.value = true
  try {
    await Promise.all([
      fetchAssignedStudents(),
      fetchAvailableStudents(),
      fetchDropoutHistory()
    ])
    showSuccess('Data refreshed successfully!')
  } catch (err) {
    console.error('Failed to refresh data:', err)
    showError('Failed to refresh data')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchAvailableStudents()
})
</script>