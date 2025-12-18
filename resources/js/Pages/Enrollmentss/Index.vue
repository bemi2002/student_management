<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="bg-gradient-to-r from-sky-600 to-blue-500 -mx-6 -my-2 px-6 py-4">
        <h2 class="font-bold text-xl text-white leading-tight">Enrollments Management</h2>
      </div>
    </template>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header with Gradient -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Enrollment Management
          </h1>
          <p class="text-gray-600">
            Manage all enrollment records and information
          </p>
        </div>

        <Link
          :href="route('enrollmentss.create')"
          class="group inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 transform hover:-translate-y-0.5"
        >
          <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          Create New Enrollment
        </Link>
      </div>

      <!-- Flash Messages -->
      <div class="space-y-3 mb-6">
        <div
          v-if="$page.props.flash?.success"
          class="rounded-lg p-4 bg-gradient-to-r from-sky-50 to-blue-50 border border-sky-200 shadow-sm"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-sky-800">{{ $page.props.flash.success }}</p>
            </div>
          </div>
        </div>

        <div
          v-if="$page.props.flash?.error"
          class="rounded-lg p-4 bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 shadow-sm"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-rose-800">{{ $page.props.flash.error }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="mb-6">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input
            v-model="search"
            type="text"
            placeholder="Search enrollments by title..."
            class="pl-10 pr-4 py-3 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 shadow-sm outline-none transition-all"
          />
        </div>
      </div>

      <!-- Enrollments Table Card -->
      <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <span class="text-sm font-semibold text-gray-700">
                {{ filteredEnrollments.length }} {{ filteredEnrollments.length === 1 ? 'Enrollment' : 'Enrollments' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50/80">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Title
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Course
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Course-Type
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Location
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Organization
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Capacity
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Amount
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Enrollment Date
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gradient-to-l from-gray-50 to-gray-100">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="(enrollment, index) in filteredEnrollments"
                :key="enrollment.id"
                class="hover:bg-gradient-to-r hover:from-sky-50/30 hover:to-blue-50/30 transition-all duration-200"
                :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center">
                      <span class="text-sm font-semibold text-sky-700">
                        {{ enrollment.id }}
                      </span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <div class="text-sm font-semibold text-gray-900">{{ enrollment.title || "—" }}</div>
                    <div v-if="enrollment.description" class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                      {{ enrollment.description }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="enrollment.course?.course_name" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800">
                    {{ enrollment.course.course_name }}
                  </span>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="enrollment.course_type?.course_type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800">
                    {{ enrollment.course_type.course_type }}
                  </span>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="enrollment.company_address?.city" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800">
                    {{ enrollment.company_address.city }}
                  </span>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-700">{{ enrollment.organization || "—" }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-gray-900">
                    {{ enrollment.student_capacity ?? 0 }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800">
                    {{ (enrollment.amount_to_be_paid) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStatusClass(enrollment.completion_status)">
                    {{ enrollment.completion_status || "Not Started" }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-700">{{ formatDate(enrollment.enrollment_date) }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="route('enrollmentss.show', enrollment.id)"
                      class="group inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      View
                    </Link>

                    <Link
                      :href="route('enrollmentss.edit', enrollment.id)"
                      class="group inline-flex items-center text-sm font-medium text-sky-600 hover:text-sky-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Edit
                    </Link>

                    <button
                      @click="deleteEnrollment(enrollment)"
                      class="group inline-flex items-center text-sm font-medium text-rose-600 hover:text-rose-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Delete
                    </button>
                    
                    <a
                      v-if="enrollment.telegram_link"
                      :href="enrollment.telegram_link"
                      target="_blank"
                      class="group inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors"
                      title="Join Telegram Group"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.111c-.252 2.793-1.337 9.571-1.886 12.701-.147.847-.437 1.131-.896 1.158-.75.045-1.32-.494-2.047-.967-1.137-.75-1.78-1.217-2.883-1.949-1.271-.826-.447-1.28.277-2.022.19-.192 3.5-3.205 3.565-3.478.008-.034.016-.151-.059-.214-.074-.063-.182-.042-.26-.024-.111.024-1.882 1.189-5.316 3.492-.503.344-.958.512-1.365.504-.448-.008-1.311-.254-1.953-.464-.786-.259-1.411-.4-1.358-.845.028-.22.337-.446.927-.677 3.575-1.566 5.961-2.626 7.159-3.18 3.425-1.577 4.133-1.854 4.594-1.864.103-.002.333.024.482.147.118.103.15.24.165.337.016.094.034.31.018.48z"/>
                      </svg>
                      Join
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredEnrollments.length === 0" class="text-center py-16 px-4">
        <div class="max-w-md mx-auto">
          <div class="relative w-24 h-24 mx-auto mb-6">
            <div class="absolute inset-0 bg-gradient-to-r from-sky-100 to-blue-100 rounded-full"></div>
            <div class="absolute inset-4 bg-gradient-to-r from-sky-200 to-blue-200 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
          </div>
          
          <h3 class="text-lg font-semibold text-gray-900 mb-2">No enrollments found</h3>
          <p v-if="search" class="text-gray-600 mb-6">Try adjusting your search criteria</p>
          <p v-else class="text-gray-600 mb-6">Get started by creating your first enrollment</p>

          <Link
            :href="route('enrollmentss.create')"
            class="group inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg"
          >
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Your First Enrollment
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  enrollments: { type: Array, default: () => [] },
});

const search = ref("");

const filteredEnrollments = computed(() => {
  if (!search.value) return props.enrollments;
  
  const searchTerm = search.value.toLowerCase();
  return props.enrollments.filter(e => 
    e.title?.toLowerCase().includes(searchTerm) ||
    e.course?.course_name?.toLowerCase().includes(searchTerm) ||
    e.organization?.toLowerCase().includes(searchTerm) ||
    e.company_address?.city?.toLowerCase().includes(searchTerm)
  );
});

function deleteEnrollment(enrollment) {
  if (confirm(`Are you sure you want to delete "${enrollment.title}"? This action cannot be undone.`)) {
    router.delete(route("enrollmentss.destroy", enrollment.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Flash message will show automatically
      },
      onError: (errors) => {
        alert('Failed to delete enrollment');
        console.error(errors);
      }
    });
  }
}

function formatDate(date) {
  if (!date) return "—";
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

function formatAmount(amount) {
  if (!amount) return "$0";
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount);
}

function getStatusClass(status) {
  const map = {
    completed: "bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800",
    in_progress: "bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800",
    pending: "bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800",
    cancelled: "bg-gradient-to-r from-rose-100 to-red-100 text-rose-800",
    not_started: "bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800",
  };
  return map[status?.toLowerCase()] ?? "bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800";
}
</script>