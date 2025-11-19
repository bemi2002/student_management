<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from backend
const props = defineProps({
  user: Object,
  roles: Array,
  userRoles: Array
})

// Reactive form
const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',       // optional; if empty, current password stays
  roles: props.userRoles
})

// Show/hide password
const showPassword = ref(false)
const togglePassword = () => { showPassword.value = !showPassword.value }

// Submit handler
const submit = () => {
  form.put(`/users/${props.user.id}`, {
    onSuccess: () => {
      alert('User updated successfully!')
    }
  })
}
</script>

<template>
  <Head title="Edit User" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit User</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

          <Link href="/users" class="inline-block mb-4 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</Link>

          <form @submit.prevent="submit" class="space-y-4">

            <!-- Name -->
            <div>
              <label class="block font-medium text-gray-700">Name</label>
              <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
              <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
            </div>

            <!-- Email -->
            <div>
              <label class="block font-medium text-gray-700">Email</label>
              <input v-model="form.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
              <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
            </div>

            <!-- Password -->
            <div>
              <label class="block font-medium text-gray-700">Password (leave empty to keep current)</label>
              <div class="flex">
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
                <button type="button" @click="togglePassword" class="ml-2 px-3 py-1 bg-gray-200 rounded">
                  {{ showPassword ? 'Hide' : 'Show' }}
                </button>
              </div>
              <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</div>
            </div>

            <!-- Roles Checkboxes -->
            <div>
              <label class="block font-medium text-gray-700 mb-2">Assign Roles</label>
              <div v-for="role in roles" :key="role.id" class="flex items-center mb-1">
                <input type="checkbox" :value="role.id" v-model="form.roles" class="mr-2" />
                <span>{{ role.name }}</span>
              </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">Update User</button>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


