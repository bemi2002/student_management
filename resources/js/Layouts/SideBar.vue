<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'


const showingSidebar = ref(true)
</script>

<template>
  <div class="flex h-screen bg-blue-50">
    <!-- Sidebar -->
    <aside
      :class="{'w-64': showingSidebar, 'w-16': !showingSidebar}"
      class="flex flex-col bg-blue-100 shadow-lg transition-all duration-300"
    >
      <!-- Logo -->
      <div class="flex items-center justify-center h-20 border-b border-blue-200">
        <Link :href="route('dashboard')">
          <ApplicationLogo class="h-10 w-auto text-blue-700" />
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto mt-4">
        <ul class="space-y-1 px-2">
          <li><Link :href="route('dashboard')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Dashboard</Link></li>
          <li><Link :href="route('permissions.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Permissions</Link></li>
          <li><Link :href="route('roles.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Roles</Link></li>
          <li><Link :href="route('admins.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Admins</Link></li>
          <li><Link :href="route('users.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Users</Link></li>
          <li><Link :href="route('resources.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Resources</Link></li>
          <li><Link :href="route('batches.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Batches</Link></li>
          <li><Link :href="route('enrollments.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Enrollments</Link></li>
          <li><Link :href="route('teacher-enrollments.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Teacher Enrollments</Link></li>
          <li><Link :href="route('student-enrollments.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Student Enrollments</Link></li>
          <li><Link :href="route('courses.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Courses</Link></li>
          <li><Link :href="route('students.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Students</Link></li>
          <li><Link :href="route('company-addresses.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Company Addresses</Link></li>
          <li><Link :href="route('specific-courses.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Specific Courses</Link></li>
          <li><Link :href="route('teachers.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Teachers</Link></li>
          <li><Link :href="route('locations.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Locations</Link></li>
          <li><Link :href="route('chat.index')" class="block px-4 py-2 rounded-md text-blue-800 hover:bg-blue-200 hover:text-blue-900">Chats</Link></li>
        </ul>
      </nav>

      <!-- Profile -->
      <div class="border-t border-blue-200 p-4">
        <div class="text-blue-800 font-medium">{{ $page.props.auth.user.name }}</div>
        <div class="text-blue-600 text-sm">{{ $page.props.auth.user.email }}</div>
        <div class="mt-2">
          <Link
            :href="route('profile.edit')"
            class="block px-3 py-1 rounded-md text-blue-700 hover:bg-blue-200 hover:text-blue-900"
          >
            Profile
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="block px-3 py-1 rounded-md text-red-600 hover:bg-red-200 hover:text-red-800 mt-1"
          >
            Log Out
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-6">
      <slot />
    </main>
  </div>
</template>

<style scoped>
a {
  transition: all 0.2s ease-in-out;
}
</style>
