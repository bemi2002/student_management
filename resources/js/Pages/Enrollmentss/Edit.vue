<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Enrollment
      </h2>
    </template>

    <div class="max-w-3xl mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Enrollment</h1>
        <Link
          :href="route('enrollmentss.index')"
          class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
        >
          ← Back to Enrollments
        </Link>
      </div>

      <form v-if="form" @submit.prevent="submit" class="grid gap-4 bg-white p-6 rounded shadow">
        <!-- Title -->
        <input v-model="form.title" class="border p-2 rounded" placeholder="Title" />
        <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>

        <!-- Description -->
        <textarea v-model="form.description" class="border p-2 rounded" placeholder="Description"></textarea>
        <div v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</div>

        <!-- Course -->
        <select v-model="form.course_id" class="border p-2 rounded">
          <option :value="null">Select Course</option>
          <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.course_name }}</option>
        </select>
        <div v-if="form.errors.course_id" class="text-red-600 text-sm">{{ form.errors.course_id }}</div>

        <!-- Course Type -->
        <select v-model="form.course_type_id" class="border p-2 rounded">
          <option :value="null">Select Course Type</option>
          <option v-for="ct in courseTypes" :key="ct.id" :value="ct.id">{{ ct.course_type }}</option>
        </select>
        <div v-if="form.errors.course_type_id" class="text-red-600 text-sm">{{ form.errors.course_type_id }}</div>

        <!-- Company Address -->
        <select v-model="form.company_address_id" class="border p-2 rounded">
          <option :value="null">Select Company Address</option>
          <option v-for="a in companyAddresses" :key="a.id" :value="a.id">{{ a.company_name }}</option>
        </select>
        <div v-if="form.errors.company_address_id" class="text-red-600 text-sm">{{ form.errors.company_address_id }}</div>

        <!-- Student Capacity -->
        <input 
          v-model.number="form.student_capacity" 
          type="number" 
          class="border p-2 rounded" 
          placeholder="Student Capacity" 
          min="0"
        />
        <div v-if="form.errors.student_capacity" class="text-red-600 text-sm">{{ form.errors.student_capacity }}</div>

        <!-- Amount to be Paid -->
        <input 
          v-model.number="form.amount_to_be_paid" 
          type="number" 
          class="border p-2 rounded" 
          placeholder="Amount to be Paid" 
          min="0"
        />
        <div v-if="form.errors.amount_to_be_paid" class="text-red-600 text-sm">{{ form.errors.amount_to_be_paid }}</div>

        <!-- Completion Status -->
        <select v-model="form.completion_status" class="border p-2 rounded">
          <option v-for="status in statusOptions" :key="status" :value="status">{{ status }}</option>
        </select>
        <div v-if="form.errors.completion_status" class="text-red-600 text-sm">{{ form.errors.completion_status }}</div>

        <!-- Telegram Link -->
        <input v-model="form.telegram_link" class="border p-2 rounded" placeholder="Telegram Link" />
        <div v-if="form.errors.telegram_link" class="text-red-600 text-sm">{{ form.errors.telegram_link }}</div>

        <!-- Enrollment Date -->
        <input v-model="form.enrollment_date" type="date" class="border p-2 rounded" />
        <div v-if="form.errors.enrollment_date" class="text-red-600 text-sm">{{ form.errors.enrollment_date }}</div>

        <!-- Submit -->
        <button class="bg-green-600 text-white p-2 rounded hover:bg-green-700">
          Update Enrollment
        </button>
      </form>

      <p v-else class="text-gray-500">Loading enrollment data...</p>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  enrollmentss: Object,   // Enrollment object from backend
  courses: Array,
  courseTypes: Array,
  companyAddresses: Array,
})

const statusOptions = ['Not Started', 'In Progress', 'Completed']

// Initialize form with default values
const form = useForm({
  title: '',
  description: '',
  course_id: null,
  course_type_id: null,
  company_address_id: null,
  student_capacity: '',
  amount_to_be_paid: '',
  completion_status: 'Not Started',
  telegram_link: '',
  enrollment_date: '',
})

// Watch for the enrollment prop and populate form
watch(
  () => props.enrollmentss,
  (e) => {
    if (e && Object.keys(e).length > 0) {
      form.title = e.title || ''
      form.description = e.description || ''
      form.course_id = e.course_id ?? null
      form.course_type_id = e.course_type_id ?? null
      form.company_address_id = e.company_address_id ?? null
      form.student_capacity = e.student_capacity ?? ''
      form.amount_to_be_paid = e.amount_to_be_paid ?? ''
      form.completion_status = e.completion_status || 'Not Started'
      form.telegram_link = e.telegram_link || ''
      form.enrollment_date = e.enrollment_date
        ? new Date(e.enrollment_date).toISOString().split('T')[0]
        : ''
    }
  },
  { immediate: true }
)

// Submit form
function submit() {
  if (!props.enrollmentss?.id) return alert('Enrollment not loaded yet')

  form.put(route('enrollmentss.update', props.enrollmentss.id), {
    preserveScroll: true,
    onSuccess: () => alert('Enrollment updated successfully!'),
  })
}
</script>
