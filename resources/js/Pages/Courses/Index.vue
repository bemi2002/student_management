<script setup>

import { Head, Link } from '@inertiajs/vue3'

defineProps({
  courses: Array
})
</script>

<template>
  
    <Head title="Courses" />

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Courses</h2>
        <Link href="/courses/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          + Add New
        </Link>
      </div>

      <table class="w-full border divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course Name</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Duration (days)</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Level</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Start Date</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">End Date</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="course in courses" :key="course.id">
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.course_name }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.description }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.duration_dates }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.level }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.start_date }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ course.end_date }}</td>
            <td class="px-6 py-4 text-right flex gap-2 justify-end">
              <Link :href="`/courses/${course.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>
              <form :action="`/courses/${course.id}`" method="POST">
                <input type="hidden" name="_method" value="DELETE" />
                <button class="text-red-600 hover:text-red-900" type="submit">Delete</button>
              </form>
            </td>
          </tr>

          <tr v-if="courses.length === 0">
            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No courses found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  
</template>
