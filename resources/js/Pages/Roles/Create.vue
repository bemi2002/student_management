<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  permissions: { type: Array, default: () => [] }
})

const form = useForm({
  name: '',
  permissions: []
})

const successMessage = ref('')

function submit() {
  form.post('/roles', {
    preserveState: true,
    onSuccess: (page) => {
      successMessage.value =
        page.props.flash?.success || 'Role created successfully!'
      form.reset('name', 'permissions')
    },
    onError: () => {
      successMessage.value = ''
    }
  })
}
</script>

<template>
  <Head title="Create Role" />

  <div class="min-h-screen bg-gray-50 py-10 px-4">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 mb-6">
      Create Role
    </h2>

    <div class="mx-auto max-w-2xl">
      <div class="bg-white p-6 shadow-sm sm:rounded-lg">

        <!-- Back Link -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg font-bold text-gray-800">Create New Role</h2>
          <Link href="/roles" class="text-gray-700 hover:underline">
            ← Back to Roles
          </Link>
        </div>

        <!-- Success Message -->
        <div
          v-if="successMessage"
          class="mb-4 p-3 bg-green-100 text-green-700 rounded"
        >
          {{ successMessage }}
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-6">

          <!-- Role Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Role Name
            </label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter role name"
              class="w-full border border-gray-300 rounded-lg px-3 py-2
                     focus:ring-2 focus:ring-green-500 focus:border-green-500"
            />
            <p
              v-if="form.errors.name"
              class="text-red-600 text-sm mt-1"
            >
              {{ form.errors.name }}
            </p>
          </div>

          <!-- Assign Permissions -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Assign Permissions
            </label>
            
            <!-- Show message if no permissions -->
            <div 
              v-if="!props.permissions || props.permissions.length === 0" 
              class="bg-yellow-50 border border-yellow-200 rounded p-4"
            >
              <p class="text-yellow-800 text-sm">
                No permissions available. Please create permissions first.
              </p>
            </div>

            <!-- Permissions checkboxes -->
            <div v-else class="flex flex-wrap gap-3">
              <label
                v-for="perm in props.permissions"
                :key="perm.id"
                class="flex items-center gap-2 bg-gray-100 px-3 py-2 rounded cursor-pointer hover:bg-gray-200 transition"
              >
                <input
                  type="checkbox"
                  v-model="form.permissions"
                  :value="perm.id"
                  class="accent-green-600 w-4 h-4"
                />
                <span class="text-gray-800 text-sm">{{ perm.name }}</span>
              </label>
            </div>
            
            <p
              v-if="form.errors.permissions"
              class="text-red-600 text-sm mt-1"
            >
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
              class="bg-green-600 text-white px-4 py-2 rounded
                     hover:bg-green-700 transition"
              :disabled="form.processing"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            >
              {{ form.processing ? 'Creating...' : 'Create Role' }}
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</template>