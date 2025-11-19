<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

// Props passed from Laravel
const props = defineProps({
  teacher: Object,
})

// Initialize form with existing teacher data
const form = useForm({
 
  first_name: props.teacher.first_name,
  last_name: props.teacher.last_name,
  email: props.teacher.email,
  phone: props.teacher.phone,
  specialization: props.teacher.specialization,
  availability_days: props.teacher.availability_days,
  preferred_start_time: props.teacher.preferred_start_time,
  preferred_end_time: props.teacher.preferred_end_time,
})

// Submit function using Inertia
const submit = () => form.put(`/teachers/${props.teacher.id}`)
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-8 bg-white shadow rounded-xl mt-10">
      <h1 class="text-2xl font-semibold mb-6">Edit Teacher</h1>

      <form @submit.prevent="submit" class="space-y-4">
        

        <div>
          <label>First Name</label>
          <input v-model="form.first_name" type="text" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Last Name</label>
          <input v-model="form.last_name" type="text" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Phone</label>
          <input v-model="form.phone" type="text" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Specialization</label>
          <input v-model="form.specialization" type="text" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Availability Days</label>
          <input v-model="form.availability_days" type="text" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Preferred Start Time</label>
          <input v-model="form.preferred_start_time" type="time" class="w-full border rounded p-2" />
        </div>

        <div>
          <label>Preferred End Time</label>
          <input v-model="form.preferred_end_time" type="time" class="w-full border rounded p-2" />
        </div>

        <div class="flex justify-between mt-6">
          <Link href="/teachers" class="text-gray-600 hover:underline">Cancel</Link>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
