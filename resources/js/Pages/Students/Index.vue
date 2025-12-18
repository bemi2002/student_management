<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="bg-gradient-to-r from-sky-600 to-blue-500 -mx-6 -my-2 px-6 py-4">
        <h2 class="font-bold text-xl text-white leading-tight">Students Management</h2>
      </div>
    </template>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header with Gradient -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Student Management
          </h1>
          <p class="text-gray-600">
            Manage all student registrations and information
          </p>
        </div>

        <Link
          v-if="canCreate"
          :href="route('students.create')"
          class="group inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 transform hover:-translate-y-0.5"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          Add New Student
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

      <!-- Students Table Card -->
      <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
              <span class="text-sm font-semibold text-gray-700">
                {{ students.length }} {{ students.length === 1 ? 'Student' : 'Students' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50/80">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-gray-100">
                  Full Name
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Phone
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Interested Course
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Heard About Us
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Registration Date
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                  Location
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gradient-to-l from-gray-50 to-gray-100">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="(student, index) in students"
                :key="student.id"
                class="hover:bg-gradient-to-r hover:from-sky-50/30 hover:to-blue-50/30 transition-all duration-200"
                :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center mr-3">
                      <span class="text-sm font-semibold text-sky-700">
                        {{ getInitials(student.full_name) }}
                      </span>
                    </div>
                    <div>
                      <div class="text-sm font-semibold text-gray-900">{{ student.full_name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-700">{{ student.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-700">
                    <span v-if="student.contact_phone" class="inline-flex items-center">
                      <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                      {{ student.contact_phone }}
                    </span>
                    <span v-else class="text-gray-400 italic">—</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="student.interested_course" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800">
                    {{ student.interested_course }}
                  </span>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="student.heard_about_us" class="flex items-center">
                    <div v-html="getHeardAboutUsIcon(student.heard_about_us)" class="mr-2"></div>
                    <span class="text-sm text-gray-700 capitalize">
                      {{ formatHeardAboutUs(student.heard_about_us) }}
                    </span>
                  </div>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-700">{{ formatDate(student.registration_date) }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="student.location?.name" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-sky-100 to-blue-100 text-sky-800">
                    {{ student.location.name }}
                  </span>
                  <span v-else class="text-gray-400 italic">—</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="route('students.show', student.id)"
                      class="group inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      View
                    </Link>

                    <Link
                      v-if="canEdit"
                      :href="route('students.edit', student.id)"
                      class="group inline-flex items-center text-sm font-medium text-sky-600 hover:text-sky-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Edit
                    </Link>

                    <button
                      v-if="canDelete"
                      @click="deleteStudent(student)"
                      class="group inline-flex items-center text-sm font-medium text-rose-600 hover:text-rose-800 transition-colors"
                    >
                      <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="students.length === 0" class="text-center py-16 px-4">
        <div class="max-w-md mx-auto">
          <div class="relative w-24 h-24 mx-auto mb-6">
            <div class="absolute inset-0 bg-gradient-to-r from-sky-100 to-blue-100 rounded-full"></div>
            <div class="absolute inset-4 bg-gradient-to-r from-sky-200 to-blue-200 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
            </div>
          </div>
          
          <h3 class="text-lg font-semibold text-gray-900 mb-2">No students found</h3>
          <p class="text-gray-600 mb-6">Get started by adding your first student to the system.</p>

          <Link
            v-if="canCreate"
            :href="route('students.create')"
            class="group inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg"
          >
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Your First Student
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  students: Array,
});

// Permissions (replace with real auth logic)
const canCreate = computed(() => true);
const canEdit = computed(() => true);
const canDelete = computed(() => true);

// Format date for display
const formatDate = (dateString) => {
  if (!dateString) return "—";
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Format "heard_about_us" for better display
const formatHeardAboutUs = (value) => {
  const formats = {
    'friend': 'Friend',
    'social_media': 'Social Media',
    'website': 'Website',
    'advertisement': 'Advertisement',
    'other': 'Other'
  };
  return formats[value] || value.replace('_', ' ');
};

// Get user initials for avatar
const getInitials = (fullName) => {
  if (!fullName) return '?';
  return fullName
    .split(' ')
    .map(name => name[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
};

// Get icon for "heard_about_us"
const getHeardAboutUsIcon = (value) => {
  const icons = {
    'friend': 'w-4 h-4 text-sky-500',
    'social_media': 'w-4 h-4 text-sky-500',
    'website': 'w-4 h-4 text-sky-500',
    'advertisement': 'w-4 h-4 text-sky-500',
    'other': 'w-4 h-4 text-gray-500'
  };
  
  const defaultIcon = 'w-4 h-4 text-gray-400';
  const iconClass = icons[value] || defaultIcon;
  
  // Return SVG based on value
  switch(value) {
    case 'friend':
      return `<svg class="${iconClass}" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>`;
    case 'social_media':
      return `<svg class="${iconClass}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>`;
    case 'website':
      return `<svg class="${iconClass}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/></svg>`;
    default:
      return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
  }
};

// Delete Student
const deleteStudent = (student) => {
  if (confirm(`Are you sure you want to delete ${student.full_name}? This action cannot be undone.`)) {
    router.delete(route('students.destroy', student.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Flash message will show automatically
      },
      onError: (errors) => {
        alert('Failed to delete student');
        console.error(errors);
      }
    });
  }
};
</script>