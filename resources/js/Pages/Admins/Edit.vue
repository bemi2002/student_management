<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'

// Props from Laravel
const props = defineProps({
  admin: { type: Object, required: true },
  teachers: Array,
  students: Array,
  batches: Array,
  courses: Array,
  resources: Array,
  specificCourses: Array,
  errors: { type: Object, default: () => ({}) }
})

// Initialize form
const form = useForm({
  teacher_id: props.admin?.teacher_id || '',
  student_id: props.admin?.student_id || '',
  batches_id: props.admin?.batches_id || '',
  course_id: props.admin?.course_id || '',
  resources: props.admin?.resources || '',
  specific_courses_id: props.admin?.specific_courses_id || ''
})

// Submit handler
const submit = () => {
  form.put(`/admins/${props.admin.id}`, {
    preserveScroll: true,
    onSuccess: () => window.location.href = '/admins'
  })
}

// Debug mounted
onMounted(() => {
  console.log('Edit Admin component mounted')
  console.log('Form data:', form.data())
})
</script>

<template>
  <Head title="Edit Admin Record" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-[#001f4d]">
        Edit Admin Record
      </h2>
    </template>

    <div class="p-8 bg-gray-100 min-h-screen">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Admin</h1>
        <Link href="/admins" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
          ← Back to Admins
        </Link>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">

          <!-- Teacher -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
            <select v-model="form.teacher_id" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.teacher_id }">
              <option value="">Select Teacher</option>
              <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
            <p v-if="errors.teacher_id" class="text-red-500 text-sm mt-1">{{ errors.teacher_id }}</p>
          </div>

          <!-- Student -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
            <select v-model="form.student_id" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.student_id }">
              <option value="">Select Student</option>
              <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
            <p v-if="errors.student_id" class="text-red-500 text-sm mt-1">{{ errors.student_id }}</p>
          </div>

          <!-- Batch -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Batch *</label>
            <select v-model="form.batches_id" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.batches_id }">
              <option value="">Select Batch</option>
              <option v-for="b in batches" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
            <p v-if="errors.batches_id" class="text-red-500 text-sm mt-1">{{ errors.batches_id }}</p>
          </div>

          <!-- Course -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
            <select v-model="form.course_id" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.course_id }">
              <option value="">Select Course</option>
              <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name || c.course_name }}</option>
            </select>
            <p v-if="errors.course_id" class="text-red-500 text-sm mt-1">{{ errors.course_id }}</p>
          </div>

          <!-- Resource -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Resource *</label>
            <select v-model="form.resources" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.resources }">
              <option value="">Select Resource</option>
              <option v-for="r in resources" :key="r.id" :value="r.id">{{ r.name }}</option>
            </select>
            <p v-if="errors.resources" class="text-red-500 text-sm mt-1">{{ errors.resources }}</p>
          </div>

          <!-- Specific Course -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Specific Course *</label>
            <select v-model="form.specific_courses_id" class="w-full border border-gray-300 rounded-lg p-3 text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.specific_courses_id }">
              <option value="">Select Specific Course</option>
              <option v-for="sc in specificCourses" :key="sc.id" :value="sc.id">{{ sc.name }}</option>
            </select>
            <p v-if="errors.specific_courses_id" class="text-red-500 text-sm mt-1">{{ errors.specific_courses_id }}</p>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-3 pt-6 border-t">
            <Link href="/admins" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</Link>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition" :disabled="form.processing">
              {{ form.processing ? 'Updating...' : 'Update Admin' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Admin Info Card -->
      <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Admin Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
          <div>
            <span class="font-medium text-gray-600">Admin ID:</span>
            <p class="mt-1">{{ admin.id }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-600">Created:</span>
            <p class="mt-1">{{ admin.created_at ? new Date(admin.created_at).toLocaleDateString() : '' }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-600">Last Updated:</span>
            <p class="mt-1">{{ admin.updated_at ? new Date(admin.updated_at).toLocaleDateString() : '' }}</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Ensure all dropdowns text is visible in black */
select, select option {
  color: black !important;
  background-color: white !important;
}
</style>
