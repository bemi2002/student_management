<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from backend
const props = defineProps({
  users: Array // Each user: id, name, email, created_at, plain_password (optional)
})

// Use a ref array for reactivity
const userList = ref(
  props.users.map(u => ({ ...u, showPassword: false }))
)

// Delete handler
const deleteUser = (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    const form = useForm()
    form.delete(`/users/${id}`, {
      onSuccess: () => {
        alert('User deleted successfully!')
        // Remove locally from list
        userList.value = userList.value.filter(u => u.id !== id)
      },
      onError: (errors) => {
        alert('Failed to delete user: ' + JSON.stringify(errors))
      }
    })
  }
}
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Users</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
        <div class="flex justify-between mb-4">
          <p class="text-gray-700">Manage users in your application.</p>

          <Link
            :href="route('users.create')"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          >
            Create User
          </Link>
        </div>

        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
          <table class="w-full table-auto border-collapse">
            <thead>
              <tr class="bg-gray-100 text-left">
                <th class="px-4 py-2 border">#</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="px-4 py-2 border">Password</th>
               
                <th class="px-4 py-2 border">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, index) in userList" :key="user.id" class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ index + 1 }}</td>
                <td class="px-4 py-2 border">{{ user.name }}</td>
                <td class="px-4 py-2 border">{{ user.email }}</td>

                <!-- Password column with working Show/Hide -->
                <td class="px-4 py-2 border flex items-center">
                  <span>{{ user.showPassword ? user.plain_password || '********' : '********' }}</span>
                  <button
                    v-if="user.plain_password"
                    @click="user.showPassword = !user.showPassword"
                    class="ml-2 px-2 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300"
                  >
                    {{ user.showPassword ? 'Hide' : 'Show' }}
                  </button>
                </td>

               

                <td class="px-4 py-2 border space-x-2">
                  <Link
                    :href="route('users.edit', user.id)"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deleteUser(user.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="userList.length === 0">
                <td colspan="6" class="px-4 py-2 border text-center text-gray-500">
                  No users found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
button, .inline-flex {
  transition: all 0.2s ease-in-out;
}
</style>




