<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props passed from controller
const props = defineProps({
  role: Object,
  permissions: { type: Array, default: () => [] },
  rolePermissions: { type: Array, default: () => [] }
})

const form = useForm({
  name: props.role.name || '',
  permissions: props.rolePermissions || []
})

const successMessage = ref('')

// Submit handler
function submit() {
  form.put(`/roles/${props.role.id}`, {
    preserveState: true,
    onSuccess: (page) => {
      successMessage.value = page.props.flash?.success || 'Role updated successfully!'
    },
    onError: () => {
      successMessage.value = ''
    }
  })
}
</script>

<template>
  <Head title="Edit Role" />

  <!-- Removed AuthenticatedLayout wrapper -->
  <div class="min-h-screen bg-gray-50 py-10 px-4">

    <!-- Header -->
    <h2 class="text-xl font-semibold leading-tight text-gray-800 mb-6">
      Edit Role
    </h2>

    <div class="mx-auto max-w-2xl">
      <div class="bg-white p-6 shadow-sm sm:rounded-lg">

        <!-- Back Link -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg font-bold text-gray-800">Edit Role</h2>
          <Link href="/roles" class="text-gray-700 hover:underline">
            ← Back to Roles
          </Link>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 rounded">
          {{ successMessage }}
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-6">

          <!-- Role Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter role name"
              class="w-full border border-gray-300 rounded-lg px-3 py-2
                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">
              {{ form.errors.name }}
            </p>
          </div>

          <!-- Assign Permissions -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Assign Permissions</label>

            <div class="flex flex-wrap gap-3">
              <label
                v-for="perm in props.permissions"
                :key="perm.id"
                class="flex items-center gap-2 bg-gray-100 px-2 py-1 rounded"
              >
                <input
                  type="checkbox"
                  v-model="form.permissions"
                  :value="perm.id"
                  class="accent-blue-600"
                />
                <span class="text-gray-800">{{ perm.name }}</span>
              </label>
            </div>

            <p v-if="form.errors.permissions" class="text-red-600 text-sm mt-1">
              {{ form.errors.permissions }}
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-3 pt-4 border-t">
            <Link
              href="/roles"
              class="bg-gray-300 text-gray-800 px-4 py-2 rounded
                     hover:bg-gray-400 transition"
            >
              Cancel
            </Link>

            <button
              type="submit"
              :disabled="form.processing"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
              class="bg-blue-600 text-white px-4 py-2 rounded
                     hover:bg-blue-700 transition"
            >
              {{ form.processing ? 'Updating...' : 'Update Role' }}
            </button>
          </div>

        </form>

        <!-- Role Info Card -->
        <div class="mt-8 bg-gray-50 p-4 rounded shadow">
          <h3 class="text-sm font-semibold mb-2 text-gray-700">Role Information</h3>

          <p>
            <span class="font-medium text-gray-600">Role ID:</span>
            {{ role.id }}
          </p>

          <p>
            <span class="font-medium text-gray-600">Created:</span>
            {{ role.created_at ? new Date(role.created_at).toLocaleDateString() : 'N/A' }}
          </p>

          <p>
            <span class="font-medium text-gray-600">Last Updated:</span>
            {{ role.updated_at ? new Date(role.updated_at).toLocaleDateString() : 'N/A' }}
          </p>
        </div>

      </div>
    </div>

  </div>
</template>
