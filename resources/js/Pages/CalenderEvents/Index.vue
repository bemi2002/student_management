<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Calendar Events</h1>
      <button
        @click="showCreateForm = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
      >
        Create New Event
      </button>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
      {{ successMessage }}
    </div>

    <!-- Events Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ event.title }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ event.content || '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(event.start) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ event.end ? formatDate(event.end) : '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
              <button
                @click="editEvent(event)"
                class="text-indigo-600 hover:text-indigo-900 transition-colors"
              >
                Edit
              </button>
              <button
                @click="deleteEvent(event.id)"
                class="text-red-600 hover:text-red-900 transition-colors"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="events.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
              No events found. Create your first event!
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-sm text-gray-600">Loading events...</p>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6">
          <h2 class="text-xl font-semibold mb-4">Create New Event</h2>
          <EventForm
            :event="null"
            @save="handleCreate"
            @cancel="showCreateForm = false"
          />
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="editingEvent" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6">
          <h2 class="text-xl font-semibold mb-4">Edit Event</h2>
          <EventForm
            :event="editingEvent"
            @save="handleUpdate"
            @cancel="editingEvent = null"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import EventForm from './EventForm.vue'

const events = ref([])
const loading = ref(false)
const showCreateForm = ref(false)
const editingEvent = ref(null)
const successMessage = ref('')

// Load events
const loadEvents = async () => {
  loading.value = true
  try {
    const response = await axios.get('/calendar-events')
    events.value = response.data
  } catch (error) {
    console.error('Error loading events:', error)
    alert('Failed to load events')
  } finally {
    loading.value = false
  }
}

// Format date for display
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Create event
const handleCreate = async (eventData) => {
  try {
    const response = await axios.post('/calendar-events', eventData)
    events.value.push(response.data)
    showCreateForm.value = false
    successMessage.value = 'Event created successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error creating event:', error)
    alert('Failed to create event: ' + (error.response?.data?.message || error.message))
  }
}

// Edit event
const editEvent = (event) => {
  editingEvent.value = { ...event }
}

// Update event
const handleUpdate = async (eventData) => {
  try {
    const response = await axios.put(`/calendar-events/${editingEvent.value.id}`, eventData)
    const index = events.value.findIndex(e => e.id === editingEvent.value.id)
    if (index !== -1) {
      events.value[index] = response.data
    }
    editingEvent.value = null
    successMessage.value = 'Event updated successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error updating event:', error)
    alert('Failed to update event: ' + (error.response?.data?.message || error.message))
  }
}

// Delete event
const deleteEvent = async (eventId) => {
  if (!confirm('Are you sure you want to delete this event?')) return
  
  try {
    await axios.delete(`/calendar-events/${eventId}`)
    events.value = events.value.filter(e => e.id !== eventId)
    successMessage.value = 'Event deleted successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error deleting event:', error)
    alert('Failed to delete event: ' + (error.response?.data?.message || error.message))
  }
}

onMounted(() => {
  loadEvents()
})
</script>