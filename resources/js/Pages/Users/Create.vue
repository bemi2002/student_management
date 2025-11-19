
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from backend
const props = defineProps({
  roles: Array // all available roles
})

// Reactive form
const form = useForm({
  name: '',
  email: '',
  password: '',
  roles: []
})

// Show/hide password
const showPassword = ref(false)
const togglePassword = () => {
  showPassword.value = !showPassword.value
}

// Submit handler
const submit = () => {
  form.post('/users', {
    onSuccess: () => {
      form.reset()
      alert('User created successfully!')
    }
  })
}

// Display current datetime
const createdAt = new Date().toLocaleString()
</script>

<template>
  <Head title="Create User" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Create User</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

          <!-- Back Button -->
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
              <label class="block font-medium text-gray-700">Password</label>
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
              <div v-for="role in props.roles" :key="role.id" class="flex items-center mb-1">
                <input type="checkbox" :value="role.id" v-model="form.roles" class="mr-2" />
                <span>{{ role.name }}</span>
              </div>
            </div>

            <!-- Created At -->
            

            <!-- Submit -->
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" :disabled="form.processing">Create User</button>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


