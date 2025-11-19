<script setup>

import { Link } from '@inertiajs/vue3'

defineProps({
  items: Array
})
</script>

<template>
  
    <div class="max-w-7xl mx-auto p-8 bg-white rounded-lg shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Course Types</h2>
        <Link href="/course-type/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          + Add New
        </Link>
      </div>

      <table class="w-full border divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course Type</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Student Capacity</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in items" :key="item.id">
            <td class="px-6 py-4 text-sm text-gray-700">{{ item.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ item.course_type }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ item.student_capacity }}</td>
            <td class="px-6 py-4 text-right flex gap-2 justify-end">
              <Link :href="`/course-type/${item.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>

              <form :action="`/course-type/${item.id}`" method="post">
                <input type="hidden" name="_method" value="delete" />
                <button class="text-red-600 hover:text-red-900" type="submit">Delete</button>
              </form>
            </td>
          </tr>
          <tr v-if="items.length === 0">
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No course types found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  
</template>
