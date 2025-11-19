<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Students Management</h2>
    </template>

    <div class="max-w-7xl mx-auto px-6 py-10">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-4 md:mb-0">
          Students Management
        </h1>

        <Link
          v-if="canCreate"
          :href="route('students.create')"
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow transition-all"
        >
          + Add New Student
        </Link>
      </div>

      <!-- Flash Messages -->
      <div
        v-if="$page.props.flash?.success"
        class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800"
      >
        {{ $page.props.flash.success }}
      </div>

      <div
        v-if="$page.props.flash?.error"
        class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800"
      >
        {{ $page.props.flash.error }}
      </div>

      <!-- Students Table -->
      <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Full Name</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Phone</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Interested Course</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Heard About Us</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Registration Date</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Location</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="student in students"
              :key="student.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ student.full_name }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.email }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.contact_phone || "—" }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.interested_course || "—" }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.heard_about_us || "—" }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.registration_date || "—" }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ student.location?.name || "—" }}</td>
              <td class="px-6 py-4">
                <span
                  :class="[
                    'px-3 py-1 text-xs font-semibold rounded-full',
                    student.status === 'active' ? 'bg-green-100 text-green-800' : '',
                    student.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : '',
                    student.status === 'terminated' ? 'bg-red-100 text-red-800' : '',
                    student.status === 'completed' ? 'bg-blue-100 text-blue-800' : ''
                  ]"
                >
                  {{ student.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm">
                <div class="flex gap-3">
                  <Link
                    :href="route('students.show', student.id)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </Link>

                  <Link
                    v-if="canEdit"
                    :href="route('students.edit', student.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </Link>

                  <button
                    v-if="canDelete"
                    @click="deleteStudent(student)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="students.length === 0" class="text-center py-14">
        <p class="text-gray-500 text-lg">No students found.</p>

        <Link
          v-if="canCreate"
          :href="route('students.create')"
          class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow transition"
        >
          + Add Your First Student
        </Link>
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

// Delete Student
const deleteStudent = (student) => {
  if (confirm(`Are you sure you want to delete ${student.full_name}?`)) {
    router.delete(route('students.destroy', student.id), {
      preserveScroll: true,
      onSuccess: () => {
        // flash message will show automatically
      },
      onError: (errors) => {
        alert('Failed to delete student');
        console.error(errors);
      }
    });
  }
};
</script>
