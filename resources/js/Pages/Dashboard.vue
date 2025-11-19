<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

// Chart.js
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)

// -----------------
// Props
// -----------------
const props = defineProps({
  counts: {
    type: Object,
    default: () => ({ students: 0, teachers: 0, courses: 0, enrollments: 0 })
  },
  users: { type: Array, default: () => [] },         
  messages: { type: Array, default: () => [] },      
  authUser: { type: Object, default: () => ({}) }    
})

// -----------------
// Bar Chart Setup
// -----------------
const barData = {
  labels: ['Students', 'Courses', 'Enrollments'],
  datasets: [{
    label: 'Counts',
    data: [
      props.counts.students,
      
      props.counts.courses,
      props.counts.enrollments,
    ],
    backgroundColor: ['#3B82F6','#10B981','#FBBF24','#8B5CF6','#14B8A6']
  }]
}
const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false }, title: { display: true, text: 'Dashboard Overview' } },
  scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
}

// -----------------
// Enhanced Calendar Setup
// -----------------
const events = ref([])
const loading = ref(false)
const showCreateForm = ref(false)
const editingEvent = ref(null)
const successMessage = ref('')

// Form data
const newEventForm = ref({
  title: '',
  content: '',
  start: '',
  end: ''
})

// Load events
const loadEvents = async () => {
  loading.value = true
  try { 
    const res = await axios.get('/calendar-events')
    events.value = res.data 
  } catch (err) { 
    console.error('Error loading events:', err)
    alert('Failed to load events')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadEvents()
  // Set default start time to current datetime
  const now = new Date()
  newEventForm.value.start = now.toISOString().slice(0, 16)
})

// Format date for display
const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Format date for input field
const formatDateForInput = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toISOString().slice(0, 16)
}

// Create new event
const handleCreate = async (eventData) => {
  try {
    const response = await axios.post('/calendar-events', eventData)
    events.value.push(response.data)
    showCreateForm.value = false
    resetForm()
    successMessage.value = 'Event created successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error creating event:', error)
    if (error.response && error.response.data && error.response.data.errors) {
      const errorMessages = Object.values(error.response.data.errors).flat().join(', ')
      alert('Failed to create event: ' + errorMessages)
    } else {
      alert('Failed to create event: ' + (error.response?.data?.message || error.message))
    }
  }
}

// Edit event
const editEvent = (event) => {
  editingEvent.value = { 
    ...event,
    start: formatDateForInput(event.start),
    end: formatDateForInput(event.end)
  }
}

// Update event
const handleUpdate = async (eventData) => {
  try {
    const response = await axios.put('/calendar-events/' + editingEvent.value.id, eventData)
    const index = events.value.findIndex(e => e.id === editingEvent.value.id)
    if (index !== -1) {
      events.value[index] = response.data
    }
    editingEvent.value = null
    successMessage.value = 'Event updated successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error updating event:', error)
    if (error.response && error.response.data && error.response.data.errors) {
      const errorMessages = Object.values(error.response.data.errors).flat().join(', ')
      alert('Failed to update event: ' + errorMessages)
    } else {
      alert('Failed to update event: ' + (error.response?.data?.message || error.message))
    }
  }
}

// Delete event
const deleteEvent = async (eventId) => {
  if (!confirm('Are you sure you want to delete this event?')) return
  
  try {
    await axios.delete('/calendar-events/' + eventId)
    events.value = events.value.filter(e => e.id !== eventId)
    successMessage.value = 'Event deleted successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (error) {
    console.error('Error deleting event:', error)
    alert('Failed to delete event: ' + (error.response?.data?.message || error.message))
  }
}

// Reset form
const resetForm = () => {
  newEventForm.value = {
    title: '',
    content: '',
    start: new Date().toISOString().slice(0, 16),
    end: ''
  }
}

// Open create form
const openCreateForm = () => {
  resetForm()
  showCreateForm.value = true
}

// -----------------
// Chat Setup
// -----------------
const selectedUser = ref(null)
const chatMessages = ref([...props.messages])
const newMessage = ref('')
const chatBox = ref(null)

const filteredMessages = computed(() => {
  if (!selectedUser.value) return []
  return chatMessages.value.filter(
    (m) =>
      (m.sender_id === props.authUser.id && m.receiver_id === selectedUser.value.id) ||
      (m.receiver_id === props.authUser.id && m.sender_id === selectedUser.value.id)
  )
})

const scrollToBottom = () => { 
  nextTick(() => { 
    if(chatBox.value) chatBox.value.scrollTop = chatBox.value.scrollHeight 
  }) 
}

onMounted(() => scrollToBottom())

const selectChatUser = (user) => { 
  selectedUser.value = user; 
  scrollToBottom() 
}

const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) return
  try {
    const { data } = await axios.post('/chat', { 
      receiver_id: selectedUser.value.id, 
      message: newMessage.value 
    })
    chatMessages.value.push(data)
    newMessage.value = ''
    scrollToBottom()
  } catch (err) { 
    console.error(err) 
  }
}
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Dashboard Overview</h2>
        <ApplicationLogo class="h-10 w-auto text-blue-700" />
      </div>
    </template>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

      <!-- Success Message -->
      <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ successMessage }}
      </div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <Link :href="route('students.index')" class="card bg-blue-100 hover:bg-blue-200">
          <h3 class="text-lg font-semibold text-blue-800">Students</h3>
          <p class="mt-4 text-5xl font-bold text-blue-900">{{ props.counts.students }}</p>
        </Link>
        
        <Link :href="route('courses.index')" class="card bg-yellow-100 hover:bg-yellow-200">
          <h3 class="text-lg font-semibold text-yellow-800">Courses</h3>
          <p class="mt-4 text-5xl font-bold text-yellow-900">{{ props.counts.courses }}</p>
        </Link>

        <!-- Enrollment Card -->
        <Link :href="route('enrollmentss.index')" class="card bg-purple-100 hover:bg-purple-200">
          <h3 class="text-lg font-semibold text-purple-800">Enrollments</h3>
          <p class="mt-4 text-5xl font-bold text-purple-900">{{ props.counts.enrollments }}</p>
        </Link>
      </div>

      <!-- Bar Chart + Calendar -->
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-md lg:flex-1" style="height:400px;">
          <h3 class="text-xl font-semibold mb-4">Dashboard Chart</h3>
          <Bar :data="barData" :options="barOptions" />
        </div>

        <!-- Enhanced Calendar Section -->
        <div class="bg-white p-6 rounded-2xl shadow-md lg:flex-1">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Calendar Events</h3>
            <button
              @click="openCreateForm"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm"
            >
              Create New Event
            </button>
          </div>

          <!-- Events Table -->
          <div class="overflow-hidden">
            <table class="min-w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
                  <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ event.title }}</td>
                  <td class="px-4 py-2 text-sm text-gray-500">{{ formatDate(event.start) }}</td>
                  <td class="px-4 py-2 text-sm font-medium space-x-2">
                    <button
                      @click="editEvent(event)"
                      class="text-indigo-600 hover:text-indigo-900 transition-colors text-xs"
                    >
                      Edit
                    </button>
                    <button
                      @click="deleteEvent(event.id)"
                      class="text-red-600 hover:text-red-900 transition-colors text-xs"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="events.length === 0 && !loading">
                  <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
                    No events found. Create your first event!
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <p class="mt-1 text-sm text-gray-600">Loading events...</p>
          </div>
        </div>
      </div>

      <!-- Create Event Modal -->
      <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
          <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Create New Event</h2>
            <form @submit.prevent="handleCreate(newEventForm)" class="space-y-4">
              <!-- Title -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input
                  v-model="newEventForm.title"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Event title"
                />
              </div>

              <!-- Content -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea
                  v-model="newEventForm.content"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Event description"
                ></textarea>
              </div>

              <!-- Start Date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                <input
                  v-model="newEventForm.start"
                  type="datetime-local"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>

              <!-- End Date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input
                  v-model="newEventForm.end"
                  type="datetime-local"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>

              <!-- Buttons -->
              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="showCreateForm = false"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Create Event
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Event Modal -->
      <div v-if="editingEvent" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
          <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Edit Event</h2>
            <form @submit.prevent="handleUpdate(editingEvent)" class="space-y-4">
              <!-- Title -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input
                  v-model="editingEvent.title"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Event title"
                />
              </div>

              <!-- Content -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea
                  v-model="editingEvent.content"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Event description"
                ></textarea>
              </div>

              <!-- Start Date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                <input
                  v-model="editingEvent.start"
                  type="datetime-local"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>

              <!-- End Date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input
                  v-model="editingEvent.end"
                  type="datetime-local"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>

              <!-- Buttons -->
              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="editingEvent = null"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Update Event
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Full-width Chat Panel -->
      <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col">
        <h3 class="text-xl font-semibold mb-4">💬 Admin–Teacher Chat</h3>

        <!-- User List -->
        <div class="mb-4 overflow-x-auto">
          <ul class="flex space-x-2">
            <li v-for="user in props.users" :key="user.id">
              <button
                @click="selectChatUser(user)"
                class="px-3 py-1 rounded-lg border"
                :class="selectedUser?.id === user.id ? 'bg-blue-600 text-white' : 'bg-gray-100'"
              >
                {{ user.name }}
              </button>
            </li>
          </ul>
        </div>

        <!-- Chat Window -->
        <div v-if="selectedUser" ref="chatBox" class="flex-1 overflow-y-auto p-3 space-y-2 border rounded-lg min-h-[300px]">
          <div
            v-for="msg in filteredMessages"
            :key="msg.id"
            class="flex"
            :class="{'justify-end': msg.sender_id===props.authUser.id,'justify-start': msg.receiver_id===props.authUser.id}"
          >
            <div :class="msg.sender_id===props.authUser.id ? 'bg-blue-600 text-white px-3 py-2 rounded-xl rounded-br-none' : 'bg-gray-100 text-gray-800 px-3 py-2 rounded-xl rounded-bl-none'">
              <p>{{ msg.message }}</p>
              <p class="text-xs mt-1 opacity-70 text-right">
                {{ msg.sender_id===props.authUser.id ? 'You' : msg.sender?.name }}
              </p>
            </div>
          </div>
        </div>

        <!-- Message Input -->
        <div v-if="selectedUser" class="mt-3 flex">
          <input v-model="newMessage" @keyup.enter="sendMessage" type="text" class="flex-1 border rounded-l-lg px-3 py-2" placeholder="Type a message..."/>
          <button @click="sendMessage" class="bg-blue-600 text-white px-4 rounded-r-lg">Send</button>
        </div>

        <div v-else class="flex-1 flex items-center justify-center text-gray-400 mt-4">
          👈 Select a user to start chatting.
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.card { padding:2rem; border-radius:1rem; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition:all 0.2s; }
.card:hover { transform:translateY(-4px); box-shadow:0 8px 16px rgba(0,0,0,0.15); }
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius:10px; }
::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
</style>