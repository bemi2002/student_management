<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  permission: {
    type: Object,
    required: true
  }
})

// Initialize form with existing permission data
const form = useForm({
  name: props.permission.name ?? ''
})

// Submit handler → PUT /permissions/{id}
const submit = () => {
  form.put(`/permissions/${props.permission.id}`, {
    preserveScroll: true
  })
}
</script>

<template>
  <Head title="Edit Permission" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-bold text-gray-800">
        Edit Permission
      </h2>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-xl p-6">

          <!-- Back -->
          <div class="mb-6">
            <Link
              href="/permissions"
              class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900"
            >
              ← Back to Permissions
            </Link>
          </div>

          <!-- Edit Form -->
          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label
                for="name"
                class="block text-sm font-medium text-gray-700"
              >
                Permission Name
              </label>

              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="e.g. edit users"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500"
              />

              <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
              <Link
                href="/permissions"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </Link>

              <button
                type="submit"
                :disabled="form.processing"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium
                       hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ form.processing ? 'Updating...' : 'Update Permission' }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
