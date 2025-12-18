<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  permissions: Array
})

const permissionList = ref([...props.permissions])

// Delete permission
const deletePermission = (id) => {
  if (!confirm('Are you sure you want to delete this permission?')) return

  router.delete(`/permissions/${id}`, {
    onSuccess: () => {
      // Remove deleted permission from the local list
      permissionList.value = permissionList.value.filter(p => p.id !== id)
      alert('Permission deleted successfully!')
    },
    onError: (errors) => {
      console.error(errors)
      alert('Failed to delete permission.')
    }
  })
}

</script>

<template>
  <Head title="Permissions List" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Permissions
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
          <!-- Add new permission -->
          <div class="mb-4">
            <Link
              href="/permissions/create"
              class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
              Add Permission
            </Link>
          </div>

          <!-- Table -->
          <table class="w-full table-auto border-collapse">
            <thead>
              <tr class="bg-gray-100 text-left">
                <th class="px-4 py-2 border">#</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Created At</th>
                <th class="px-4 py-2 border">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(permission, index) in permissionList"
                :key="permission.id"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-2 border">{{ index + 1 }}</td>
                <td class="px-4 py-2 border">{{ permission.name }}</td>
                <td class="px-4 py-2 border">{{ new Date(permission.created_at).toLocaleString() }}</td>
                <td class="px-4 py-2 border space-x-2">
                  <Link
                    :href="`/permissions/${permission.id}/edit`"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    Edit
                  </Link>

                  <button
                    @click="deletePermission(permission.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                  >
                    Delete
                  </button>
                </td>
              </tr>
              <tr v-if="permissionList.length === 0">
                <td colspan="4" class="text-center py-4 text-gray-500">
                  No permissions found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
