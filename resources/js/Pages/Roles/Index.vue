<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  roles: { type: Array, default: () => [] }
})

const form = useForm()

// Delete role
const deleteRole = (id, roleName) => {
  if (!confirm(`Are you sure you want to delete the role "${roleName}" permanently?`)) return;

  form.delete(`/roles/${id}`, {
    onSuccess: () => {
      const index = props.roles.findIndex(r => r.id === id)
      if (index !== -1) props.roles.splice(index, 1)
      alert(`Role "${roleName}" deleted successfully.`)
    },
    onError: (errors) => {
      console.error(errors)
      alert(`Failed to delete role "${roleName}".`)
    }
  })
}

const roleList = computed(() => props.roles)
</script>

<template>
  <Head title="Roles Management" />

  <!-- Removed AuthenticatedLayout wrapper -->
  <div class="min-h-screen bg-gray-50 py-8 px-4">

    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 mb-6">
      Roles
    </h2>

    <div class="mx-auto max-w-4xl">
      <div class="bg-white p-6 shadow-sm sm:rounded-lg">

        <!-- Add new role -->
        <div class="mb-4">
          <Link
            href="/roles/create"
            class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          >
            + Add New Role
          </Link>
        </div>

        <!-- Roles Table -->
        <div class="overflow-x-auto">
          <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-left">
              <tr>
                <th class="px-4 py-2 border">#</th>
                <th class="px-4 py-2 border">Role Name</th>
                <th class="px-4 py-2 border">Permissions</th>
                <th class="px-4 py-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(role, index) in roleList" :key="role.id" class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ index + 1 }}</td>
                <td class="px-4 py-2 border">{{ role.name }}</td>
                <td class="px-4 py-2 border">
                  <span v-for="perm in role.permissions" :key="perm.id" class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 mr-1 rounded">
                    {{ perm.name }}
                  </span>
                </td>
                <td class="px-4 py-2 border space-x-2">
                  <Link
                    :href="`/roles/${role.id}/edit`"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deleteRole(role.id, role.name)"
                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="roleList.length === 0">
                <td colspan="4" class="text-center py-6 text-gray-500">
                  No roles found.
                </td>
              </tr>

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</template>
