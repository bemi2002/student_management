<!-- resources/js/Pages/Enrollmentss/YourAssignComponent.vue -->
<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  enrollment: Object
})

const showAssignBox = ref(false)
const availableStudents = ref([])

const assignForm = useForm({
  student_id: '',
  enrollment_data: props.enrollment?.enrollment || ''
})

const fetchAvailableStudents = async () => {
  if (!props.enrollment?.id) {
    console.error('Enrollment ID is missing')
    return
  }
  
  try {
    const res = await axios.get(`/enrollmentss/${props.enrollment.id}/available-students`)
    availableStudents.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const toggleAssignBox = () => {
  if (!props.enrollment?.id) {
    alert('Enrollment ID is missing! Cannot assign students.')
    return
  }
  
  showAssignBox.value = !showAssignBox.value
  if (showAssignBox.value) fetchAvailableStudents()
}

const assignStudent = () => {
  if (!assignForm.student_id) return alert('Please select a student.')

  if (!props.enrollment?.id) {
    alert('Enrollment ID is missing! Cannot assign student.')
    return
  }

  assignForm.post(`/enrollmentss/${props.enrollment.id}/assign`, {
    onSuccess: () => {
      window.location.href = '/enrollmentss'
    }
  })
}
</script>

<template>
  <div class="max-w-md mx-auto my-4 border rounded shadow p-4 bg-white">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Enrollment: {{ props.enrollment?.title }}</h2>
      <button 
        @click="toggleAssignBox" 
        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
        + Assign Student
      </button>
    </div>

    <!-- Assign Box -->
    <div v-if="showAssignBox" class="border-t pt-4 mt-2">
      <label class="block mb-2 font-medium">Select Student</label>
      <select v-model="assignForm.student_id" class="w-full border border-gray-300 rounded px-3 py-2 mb-4">
        <option value="">-- Choose Student --</option>
        <option v-for="s in availableStudents" :key="s.id" :value="s.id">
          {{ s.full_name }} ({{ s.email }})
        </option>
      </select>

      <div class="flex justify-end gap-3">
        <button @click="showAssignBox = false" class="px-4 py-2 border rounded hover:bg-gray-100 transition">
          Cancel
        </button>
        <button @click="assignStudent" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
          Assign
        </button>
      </div>
    </div>
  </div>
</template>