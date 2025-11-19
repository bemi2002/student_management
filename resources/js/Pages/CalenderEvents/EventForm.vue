<template>
  <form @submit.prevent="submit" class="space-y-4">
    <!-- Title -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
      <input
        v-model="form.title"
        type="text"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        placeholder="Event title"
      />
      <div v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</div>
    </div>

    <!-- Content -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
      <textarea
        v-model="form.content"
        rows="3"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        placeholder="Event description"
      ></textarea>
      <div v-if="errors.content" class="text-red-500 text-sm mt-1">{{ errors.content }}</div>
    </div>

    <!-- Start Date -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
      <input
        v-model="form.start"
        type="datetime-local"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
      <div v-if="errors.start" class="text-red-500 text-sm mt-1">{{ errors.start }}</div>
    </div>

    <!-- End Date -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
      <input
        v-model="form.end"
        type="datetime-local"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
      <div v-if="errors.end" class="text-red-500 text-sm mt-1">{{ errors.end }}</div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end space-x-3 pt-4">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Cancel
      </button>
      <button
        type="submit"
        :disabled="loading"
        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-blue-400 disabled:cursor-not-allowed"
      >
        <span v-if="loading">Saving...</span>
        <span v-else>{{ props.event ? 'Update' : 'Create' }} Event</span>
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({
  event: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['save', 'cancel'])

const loading = ref(false)
const errors = ref({})

const form = reactive({
  title: '',
  content: '',
  start: '',
  end: ''
})

// Format date for datetime-local input
const formatDateForInput = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toISOString().slice(0, 16)
}

// Initialize form when event prop changes
watch(() => props.event, (newEvent) => {
  if (newEvent) {
    form.title = newEvent.title || ''
    form.content = newEvent.content || ''
    form.start = formatDateForInput(newEvent.start)
    form.end = formatDateForInput(newEvent.end)
  } else {
    // Reset form for new event
    form.title = ''
    form.content = ''
    form.start = formatDateForInput(new Date())
    form.end = ''
  }
}, { immediate: true })

// Form submission
const submit = async () => {
  loading.value = true
  errors.value = {}

  try {
    // Prepare data for API
    const eventData = {
      title: form.title,
      content: form.content,
      start: form.start,
      end: form.end || null
    }

    emit('save', eventData)
  } catch (error) {
    console.error('Form error:', error)
  } finally {
    loading.value = false
  }
}
</script>