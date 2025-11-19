
<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.user || {}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col p-6">
      <div class="text-2xl font-bold text-gray-800 mb-6">
        <Link href="/dashboard">Dashboard</Link>
      </div>

      <nav class="flex-1 space-y-3">
        <Link href="/dashboard" class="block text-gray-700 hover:text-blue-600">Dashboard</Link>

        <!-- Admin Links -->
        <template v-if="user.role === 'admin'">
          <Link href="/admins" class="block text-gray-700 hover:text-blue-600">Admins</Link>
          <Link href="/students" class="block text-gray-700 hover:text-blue-600">Students</Link>
          <Link href="/teachers" class="block text-gray-700 hover:text-blue-600">Teachers</Link>
          <Link href="/courses" class="block text-gray-700 hover:text-blue-600">Courses</Link>
          <Link href="/teacher-enrollments" class="block text-gray-700 hover:text-blue-600">Teacher Enrollments</Link>
          <Link href="/student-enrollments" class="block text-gray-700 hover:text-blue-600">Student Enrollments</Link>
          <Link href="/specific-courses" class="block text-gray-700 hover:text-blue-600">Specific Courses</Link>
          <Link href="/resources" class="block text-gray-700 hover:text-blue-600">Resources</Link>
          <Link href="/batches" class="block text-gray-700 hover:text-blue-600">Batches</Link>
          <Link href="/enrollments" class="block text-gray-700 hover:text-blue-600">Enrollments</Link>
        </template>

        <!-- Teacher Links -->
        <template v-else-if="user.role === 'teacher'">
          <Link href="/teachers" class="block text-gray-700 hover:text-blue-600">Teachers</Link>
          <Link href="/teacher-enrollments" class="block text-gray-700 hover:text-blue-600">Teacher Enrollments</Link>
          <Link href="/courses" class="block text-gray-700 hover:text-blue-600">Courses</Link>
          <Link href="/specific-courses" class="block text-gray-700 hover:text-blue-600">Specific Courses</Link>
          <Link href="/resources" class="block text-gray-700 hover:text-blue-600">Resources</Link>
        </template>

        <!-- Student Links -->
        <template v-else-if="user.role === 'student'">
          <Link href="/students" class="block text-gray-700 hover:text-blue-600">Students</Link>
          <Link href="/courses" class="block text-gray-700 hover:text-blue-600">Courses</Link>
          <Link href="/batches" class="block text-gray-700 hover:text-blue-600">Batches</Link>
          <Link href="/resources" class="block text-gray-700 hover:text-blue-600">Resources</Link>
          <Link href="/locations" class="block text-gray-700 hover:text-blue-600">Locations</Link>
          <Link href="/specific-courses" class="block text-gray-700 hover:text-blue-600">Specific Courses</Link>
          <Link href="/student-enrollments" class="block text-gray-700 hover:text-blue-600">Student Enrollments</Link>
        </template>
      </nav>

      <!-- User info and Logout -->
      <div class="mt-auto pt-6 border-t border-gray-200">
        <span class="text-gray-600 block mb-2">{{ user.name }}</span>
        <Link
          href="/logout"
          method="post"
          as="button"
          class="text-red-600 hover:underline text-sm"
        >
          Logout
        </Link>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <slot />
    </main>
  </div>
</template>
