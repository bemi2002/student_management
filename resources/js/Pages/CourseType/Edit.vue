<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  item: Object
})

const form = useForm({
  course_type: props.item?.course_type ?? '',
  student_capacity: props.item?.student_capacity ?? 0
})

const errors = ref({})

function submit() {
  form.put(`/course-type/${props.item.id}`, {
    preserveScroll: true,
    onSuccess: () => router.visit('/course-type'),
    onError: (errs) => errors.value = errs
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit Course Type" />

    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Course Type</h2>
        <Link href="/course-type" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Back</Link>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <select v-model="form.course_type" class="w-full border rounded p-2" required>
          <option value="">Select Course Type</option>
          <option value="morning">Morning</option>
          <option value="evening">Evening</option>
          <option value="weekend">Weekend</option>
        </select>

        <input type="number" v-model="form.student_capacity" placeholder="Student Capacity" class="w-full border rounded p-2" required />

        <div class="flex justify-end gap-2 mt-4">
          <Link href="/course-type" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</Link>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ form.processing ? 'Updating...' : 'Update' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
