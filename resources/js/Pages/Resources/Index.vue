<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  resources: { type: Array, default: () => [] }
})

const form = useForm()

// Delete resource
function deleteResource(id) {
  if (confirm('Are you sure you want to delete this resource?')) {
    form.delete(`/resources/${id}`, {
      onSuccess: () => {
        // Optional: toast or reload table
      }
    })
  }
}
</script>

<template>
  <Head title="Resources" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Resources Management
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
          
          <!-- Add new resource -->
          <div class="mb-6 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Resources List</h3>
            <Link
              href="/resources/create"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
              + Add Resource
            </Link>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                  <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course</th>
                  <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Specific Course</th>
                  <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Text</th>
                  <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
                </tr>
              </thead>

              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="resource in props.resources" :key="resource.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-gray-700">{{ resource.id }}</td>

                  <!-- Course: show course_name or web fallback -->
                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ resource.course?.course_name || resource.web || 'N/A' }}
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ resource.specific_course?.name || 'N/A' }}
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ resource.text || 'N/A' }}
                  </td>

                  <td class="px-6 py-4 text-right flex gap-2 justify-end">
                    <Link
                      :href="`/resources/${resource.id}/edit`"
                      class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteResource(resource.id)"
                      class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                    >
                      Delete
                    </button>
                  </td>
                </tr>

                <tr v-if="props.resources.length === 0">
                  <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    No resources found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
