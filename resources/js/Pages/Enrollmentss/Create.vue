<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Enrollment
      </h2>
    </template>

    <div class="max-w-3xl mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Enrollment</h1>
        <Link
          :href="route('enrollmentss.index')"
          class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
        >
          ← Back to Enrollments
        </Link>
      </div>

      <form @submit.prevent="submit" class="grid gap-4 bg-white p-6 rounded shadow">
        <!-- Title -->
        <input v-model="form.title" type="text" placeholder="Title" class="border p-2 rounded w-full" />
        <p v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</p>

        <!-- Description -->
        <textarea v-model="form.description" placeholder="Description" class="border p-2 rounded w-full"></textarea>
        <p v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</p>

        <!-- Course -->
        <select v-model.number="form.course_id" class="border p-2 rounded w-full">
          <option value="">Select Course</option>
          <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.course_name }}</option>
        </select>
        <p v-if="form.errors.course_id" class="text-red-600 text-sm">{{ form.errors.course_id }}</p>

        <!-- Course Type -->
        <select v-model.number="form.course_type_id" class="border p-2 rounded w-full">
          <option value="">Select Course Type</option>
          <option v-for="ct in courseTypes" :key="ct.id" :value="ct.id">{{ ct.course_type }}</option>
        </select>
        <p v-if="form.errors.course_type_id" class="text-red-600 text-sm">{{ form.errors.course_type_id }}</p>

        <!-- Company Address -->
        <select v-model.number="form.company_address_id" class="border p-2 rounded w-full">
          <option value="">Select Company Address</option>
          <option v-for="ca in companyAddresses" :key="ca.id" :value="ca.id">{{ ca.company_name }}</option>
        </select>
        <p v-if="form.errors.company_address_id" class="text-red-600 text-sm">{{ form.errors.company_address_id }}</p>

        <!-- Student Capacity -->
        <input v-model.number="form.student_capacity" type="number" min="0" placeholder="Student Capacity" class="border p-2 rounded w-full" />
        <p v-if="form.errors.student_capacity" class="text-red-600 text-sm">{{ form.errors.student_capacity }}</p>

        <!-- Amount to be Paid -->
        <input v-model.number="form.amount_to_be_paid" type="number" min="0" placeholder="Amount" class="border p-2 rounded w-full" />
        <p v-if="form.errors.amount_to_be_paid" class="text-red-600 text-sm">{{ form.errors.amount_to_be_paid }}</p>

        <!-- Completion Status -->
        <select v-model="form.completion_status" class="border p-2 rounded w-full">
          <option v-for="status in statusOptions" :key="status" :value="status">{{ status }}</option>
        </select>
        <p v-if="form.errors.completion_status" class="text-red-600 text-sm">{{ form.errors.completion_status }}</p>

        <!-- Telegram Link -->
        <input v-model="form.telegram_link" type="text" placeholder="Telegram Link" class="border p-2 rounded w-full" />
        <p v-if="form.errors.telegram_link" class="text-red-600 text-sm">{{ form.errors.telegram_link }}</p>

        <!-- Enrollment Date -->
        <input v-model="form.enrollment_date" type="date" class="border p-2 rounded w-full" />
        <p v-if="form.errors.enrollment_date" class="text-red-600 text-sm">{{ form.errors.enrollment_date }}</p>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700" :disabled="form.processing">
          Save Enrollment
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  courses: Array,
  courseTypes: Array,
  companyAddresses: Array
})

const statusOptions = ['Not Started', 'In Progress', 'Completed']

// Initialize form
const form = useForm({
  title: '',
  description: '',
  course_id: null,
  course_type_id: null,
  company_address_id: null,
  student_capacity: 0,
  amount_to_be_paid: 0,
  completion_status: 'Not Started',
  telegram_link: '',
  enrollment_date: ''
})

// Submit handler
function submit() {
  form.post(route('enrollmentss.store'), {
    onSuccess: () => router.visit(route('enrollmentss.index')),
    onError: (errors) => console.log(errors)
  })
}
</script>
