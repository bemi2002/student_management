<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-6">
          <button 
            @click="$inertia.visit(route('enrollmentss.index'))"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 mb-4"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Enrollments
          </button>
          
          <div class="flex justify-between items-start">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">{{ enrollment.title }}</h1>
              <p class="mt-2 text-gray-600">{{ enrollment.description }}</p>
            </div>
            
            <div class="flex space-x-3">
              <button 
                @click="refreshData" 
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="loading"
              >
                <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ loading ? 'Refreshing...' : 'Refresh' }}
              </button>
              
              <Link 
                :href="route('enrollmentss.edit', enrollment.id)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Enrollment
              </Link>
            </div>
          </div>
        </div>

        <!-- Success/Error Messages -->
        <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-green-800">{{ successMessage }}</p>
          </div>
        </div>
        
        <div v-if="errorMessage" class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-red-800">{{ errorMessage }}</p>
          </div>
        </div>

        <!-- Modal Trigger Button -->
        <div class="mb-6">
          <button 
            @click="showModal = true"
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Manage Enrollment Details
          </button>
        </div>

        <!-- Modal Overlay -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
          <div class="relative top-20 mx-auto p-5 border w-full max-w-7xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
              <h3 class="text-2xl font-bold text-gray-900">Enrollment Management - {{ enrollment.title }}</h3>
              <button 
                @click="showModal = false"
                class="text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Modal Content - Your existing grid layout -->
            <div class="mt-4">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Enrollment Details -->
                <div class="lg:col-span-1 space-y-6">
                  <!-- Enrollment Information Card -->
                  <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Enrollment Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        <span :class="getStatusBadgeClass(enrollment.completion_status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                          {{ enrollment.completion_status || 'Not specified' }}
                        </span>
                      </div>
                      
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Student Capacity</span>
                        <span class="text-sm text-gray-900">
                          {{ assignedStudents.length }} / {{ enrollment.student_capacity || 'Unlimited' }}
                        </span>
                      </div>
                      
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Enrollment Date</span>
                        <span class="text-sm text-gray-900">{{ formatDate(enrollment.enrollment_date) }}</span>
                      </div>
                      
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Amount to be Paid</span>
                        <span class="text-sm text-gray-900">{{ formatCurrency(enrollment.amount_to_be_paid) }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Course Information Card -->
                  <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Course Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                      <div v-if="enrollment.course">
                        <span class="text-sm font-medium text-gray-500 block mb-1">Course</span>
                        <span class="text-sm text-gray-900">{{ enrollment.course.course_name }}</span>
                      </div>
                      
                      <div v-if="enrollment.course_type">
                        <span class="text-sm font-medium text-gray-500 block mb-1">Course Type</span>
                        <span class="text-sm text-gray-900">{{ enrollment.course_type.course_type }}</span>
                      </div>
                      
                      <div v-if="enrollment.company_address">
                        <span class="text-sm font-medium text-gray-500 block mb-1">Company</span>
                        <span class="text-sm text-gray-900">{{ enrollment.company_address.company_name }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Quick Actions Card -->
                  <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                      <button 
                        @click="refreshData"
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
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
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.14.141-.259.259-.374.261l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.136-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                        </svg>
                        Join Telegram
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Right Column - Students Management -->
                <div class="lg:col-span-2 space-y-6">
                  <!-- Assigned Students Card -->
                  <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                      <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Assigned Students</h3>
                        <span class="text-sm text-gray-500">{{ assignedStudents.length }} students</span>
                      </div>
                    </div>
                    
                    <div class="p-6">
                      <div v-if="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-sm text-gray-600">Loading students...</p>
                      </div>
                      
                      <div v-else-if="assignedStudents.length === 0" class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h4 class="mt-2 text-sm font-medium text-gray-900">No students assigned</h4>
                        <p class="mt-1 text-sm text-gray-500">Get started by assigning students to this enrollment.</p>
                      </div>
                      
                      <ul v-else class="divide-y divide-gray-200">
                        <li v-for="student in assignedStudents" :key="student.id" class="py-4">
                          <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                              <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                  <span class="text-blue-600 font-semibold text-sm">
                                    {{ getInitials(student.full_name) }}
                                  </span>
                                </div>
                              </div>
                              <div>
                                <p class="text-sm font-medium text-gray-900">{{ student.full_name }}</p>
                                <p class="text-sm text-gray-500">{{ student.email }}</p>
                                <p v-if="student.contact_phone" class="text-xs text-gray-400">{{ student.contact_phone }}</p>
                              </div>
                            </div>
                            <button 
                              @click="dropoutStudent(student.id)" 
                              class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                              :disabled="processingStudent === student.id"
                            >
                              <span v-if="processingStudent === student.id" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-700" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Removing...
                              </span>
                              <span v-else class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Dropout
                              </span>
                            </button>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>

                  <!-- Assign Students Card -->
                  <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Assign Students</h3>
                    </div>
                    
                    <div class="p-6">
                      <div v-if="isCapacityReached" class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                        <div class="flex">
                          <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                          </svg>
                          <div>
                            <h4 class="text-sm font-medium text-yellow-800">Enrollment Full</h4>
                            <p class="text-sm text-yellow-700 mt-1">This enrollment has reached its capacity of {{ enrollment.student_capacity }} students.</p>
                          </div>
                        </div>
                      </div>

                      <div v-else>
                        <div class="mb-4">
                          <label for="student-select" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Student to Assign
                          </label>
                          <select 
                            id="student-select"
                            v-model="selectedStudent" 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            :disabled="loadingAvailable"
                          >
                            <option value="">Choose a student...</option>
                            <option v-for="student in availableStudents" :key="student.id" :value="student.id">
                              {{ student.full_name }} ({{ student.email }})
                            </option>
                          </select>
                          
                          <div v-if="loadingAvailable" class="mt-2 text-sm text-gray-500">
                            Loading available students...
                          </div>
                          <div v-else-if="availableStudents.length === 0" class="mt-2 text-sm text-gray-500">
                            No available students found.
                          </div>
                          <div v-else class="mt-2 text-sm text-gray-500">
                            {{ availableStudents.length }} students available for assignment
                          </div>
                        </div>
                        
                        <button
                          @click="assignStudent"
                          :disabled="!selectedStudent || assigningStudent"
                          class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                          <span v-if="assigningStudent" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Assigning Student...
                          </span>
                          <span v-else class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Assign Student
                          </span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end pt-6 border-t mt-6">
              <button 
                @click="showModal = false"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors mr-3"
              >
                Close
              </button>
              <button 
                @click="refreshData"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
              >
                Refresh Data
              </button>
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
  availableStudentsUrl: String
})

// Add modal state
const showModal = ref(false)

// Your existing reactive variables and functions remain exactly the same
const selectedStudent = ref('')
const availableStudents = ref([])
const assignedStudents = ref([])
const loading = ref(false)
const loadingAvailable = ref(false)
const assigningStudent = ref(false)
const processingStudent = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

// Initialize assignedStudents from props
assignedStudents.value = Array.isArray(props.enrollment.students) 
  ? [...props.enrollment.students] 
  : []

// Computed property to check capacity
const isCapacityReached = computed(() => {
  if (!props.enrollment.student_capacity) return false
  return assignedStudents.value.length >= props.enrollment.student_capacity
})

// Watch for changes in props.enrollment.students
watch(() => props.enrollment.students, (newStudents) => {
  assignedStudents.value = Array.isArray(newStudents) ? [...newStudents] : []
}, { immediate: true, deep: true })

// All your existing helper functions remain exactly the same
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
  if (!amount) return 'Not set'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

const getStatusBadgeClass = (status) => {
  const statusClasses = {
    'completed': 'bg-green-100 text-green-800',
    'in progress': 'bg-blue-100 text-blue-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'cancelled': 'bg-red-100 text-red-800',
    'not started': 'bg-gray-100 text-gray-800'
  }
  return statusClasses[status?.toLowerCase()] || 'bg-gray-100 text-gray-800'
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

// All your existing functions (fetchAvailableStudents, fetchAssignedStudents, assignStudent, dropoutStudent, refreshData, etc.) remain exactly the same
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

async function fetchAssignedStudents() {
  loading.value = true
  try {
    const res = await axios.get(`/enrollmentss/${props.enrollment.id}`, {
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    if (res.data.enrollment && res.data.enrollment.students) {
      assignedStudents.value = Array.isArray(res.data.enrollment.students) 
        ? [...res.data.enrollment.students] 
        : []
    } else if (res.data.students) {
      assignedStudents.value = Array.isArray(res.data.students) 
        ? [...res.data.students] 
        : []
    }
  } catch (err) {
    console.error('Failed to fetch assigned students:', err)
    showError('Failed to load assigned students')
  } finally {
    loading.value = false
  }
}

async function assignStudent() {
  if (!selectedStudent.value) return

  assigningStudent.value = true
  clearMessages()
  
  try {
    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/assign-student`, {
      student_id: selectedStudent.value
    })
    
    console.log('Assignment response:', response.data)
    
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

async function dropoutStudent(studentId) {
  const reason = prompt('Please enter the reason for removing this student:')
  if (!reason) return

  processingStudent.value = studentId
  clearMessages()
  
  try {
    const response = await axios.post(`/enrollmentss/${props.enrollment.id}/unassign-student`, {
      student_id: studentId,
      reason: reason
    })
    
    console.log('Unassign response:', response.data)
    
    if (response.data.success) {
      const removedStudent = assignedStudents.value.find(student => student.id == studentId)
      
      if (removedStudent) {
        assignedStudents.value = assignedStudents.value.filter(student => student.id != studentId)
        const alreadyInAvailable = availableStudents.value.some(student => student.id == studentId)
        if (!alreadyInAvailable && removedStudent) {
          availableStudents.value.push({...removedStudent})
        }
        await nextTick()
      } else {
        await refreshData()
      }
      
      showSuccess(response.data.message || 'Student removed from enrollment')
    } else {
      showError(response.data.message || 'Failed to remove student')
    }
    
  } catch (err) {
    console.error('Dropout error:', err)
    
    if (err.response?.data?.message) {
      showError(err.response.data.message)
    } else if (err.response?.data?.error) {
      showError(err.response.data.error)
    } else {
      showError('Failed to remove student. Please try again.')
    }
    
    await refreshData()
  } finally {
    processingStudent.value = null
  }
}

async function refreshData() {
  loading.value = true
  try {
    router.reload({ 
      only: ['enrollment'],
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
      fetchAvailableStudents()
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