<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from 'chart.js'
import { Bar } from 'vue-chartjs'
import dayjs from 'dayjs'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'

dayjs.extend(isSameOrAfter)
dayjs.extend(isSameOrBefore)

// Props
const props = defineProps({
  counts: { type: Object, default: () => ({ students: 0, teachers: 0, courses: 0, enrollments: 0 }) }
})

// Chart setup
ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)
const barData = {
  labels: ['Students', 'Courses', 'Enrollments'],
  datasets: [{
    label: 'Counts',
    data: [props.counts.students, props.counts.courses, props.counts.enrollments],
    backgroundColor: ['#3B82F6', '#10B981', '#FBBF24']
  }]
}
const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { 
    legend: { display: false }, 
    title: { display: true, text: 'Dashboard Overview' } 
  },
  scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
}

// Calendar & events
const events = ref([])
const enrollments = ref([])
const loading = ref(false)
const successMessage = ref('')

// Forms
const creatingNew = ref(false)
const createForm = ref({ title: '', content: '', start: '', end: '', enrollmentss_id: null })
const editingEventId = ref(null)
const editForm = ref({ title: '', content: '', start: '', end: '', enrollmentss_id: null })

// Dropdowns
const dropdownOpen = ref(false)
const editDropdownOpen = ref(false)
const selectedEnrollmentText = ref('')
const editSelectedEnrollmentText = ref('')

// Enrollment colors
const enrollmentColors = ref({})
const colorPalette = [
  '#3B82F6','#EF4444','#10B981','#F59E0B','#8B5CF6',
  '#EC4899','#06B6D4','#84CC16','#F97316','#6366F1',
  '#14B8A6','#EAB308','#A855F7','#F43F5E','#0EA5E9'
]

const generateColorMapping = () => {
  const mapping = {}
  enrollments.value.forEach((enrollment, index) => {
    mapping[enrollment.id] = colorPalette[index % colorPalette.length]
  })
  enrollmentColors.value = mapping
}

// Load events and enrollments
const loadData = async () => {
  loading.value = true
  try {
    const startOfMonth = currentMonth.value.startOf('month').format('YYYY-MM-DD')
    const endOfMonth = currentMonth.value.endOf('month').format('YYYY-MM-DD')
    
    const [eventsRes, enrollRes] = await Promise.all([
      axios.get(`/calendar-events?start=${startOfMonth}&end=${endOfMonth}`),
      axios.get('/enrollments/simple')
    ])
    events.value = eventsRes.data || []
    enrollments.value = enrollRes.data || []
    generateColorMapping()
  } catch (err) {
    console.error('Load error:', err)
  } finally {
    loading.value = false
  }
}
onMounted(loadData)

// Calendar navigation
const currentMonth = ref(dayjs().startOf('month'))
const nextMonth = () => currentMonth.value = currentMonth.value.add(1, 'month')
const prevMonth = () => currentMonth.value = currentMonth.value.subtract(1, 'month')

// Watch for month changes and reload events
watch(currentMonth, () => {
  loadData()
})

// Calendar grid
const monthDays = computed(() => {
  const start = currentMonth.value.startOf('month').startOf('week')
  const end = currentMonth.value.endOf('month').endOf('week')
  const days = []
  let date = start
  while (date.isBefore(end) || date.isSame(end, 'day')) {
    days.push(date)
    date = date.add(1, 'day')
  }
  return days
})

// Events per day
const eventsForDay = (day) => events.value.filter(e => {
  const start = dayjs(e.start)
  const end = e.end ? dayjs(e.end) : start
  return day.isSameOrAfter(start, 'day') && day.isSameOrBefore(end, 'day')
})

// Create/Edit events
const startCreating = (day = null) => {
  creatingNew.value = true
  createForm.value = {
    title: '', 
    content: '', 
    start: day ? day.format('YYYY-MM-DDTHH:mm') : dayjs().format('YYYY-MM-DDTHH:mm'), 
    end: '', 
    enrollmentss_id: null
  }
  selectedEnrollmentText.value = ''
}

const selectEnrollment = (en) => {
  createForm.value.enrollmentss_id = en.id
  selectedEnrollmentText.value = en.title
  dropdownOpen.value = false
}

const createEvent = async () => {
  if (!createForm.value.title.trim()) return alert('Title required!')
  if (!createForm.value.enrollmentss_id) return alert('Select enrollment!')
  try {
    const payload = { 
      title: createForm.value.title.trim(),
      content: createForm.value.content.trim() || null,
      start: dayjs(createForm.value.start).format('YYYY-MM-DD HH:mm:ss'),
      end: createForm.value.end ? dayjs(createForm.value.end).format('YYYY-MM-DD HH:mm:ss') : null,
      enrollmentss_id: createForm.value.enrollmentss_id
    }
    const res = await axios.post('/calendar-events', payload)
    
    // Clear the form
    creatingNew.value = false
    createForm.value = { title: '', content: '', start: '', end: '', enrollmentss_id: null }
    selectedEnrollmentText.value = ''
    
    // Reload data from server to ensure consistency
    await loadData()
    
    successMessage.value = 'Event created successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Failed to create event')
  }
}

const startEditing = (event) => {
  editingEventId.value = event.id
  editForm.value = { 
    ...event, 
    start: dayjs(event.start).format('YYYY-MM-DDTHH:mm'), 
    end: event.end ? dayjs(event.end).format('YYYY-MM-DDTHH:mm') : '' 
  }
  const selected = enrollments.value.find(e => e.id === editForm.value.enrollmentss_id)
  editSelectedEnrollmentText.value = selected ? selected.title : ''
}

const selectEditEnrollment = (en) => {
  editForm.value.enrollmentss_id = en.id
  editSelectedEnrollmentText.value = en.title
  editDropdownOpen.value = false
}

const saveEdit = async () => {
  if (!editForm.value.title.trim()) return alert('Title required!')
  if (!editForm.value.enrollmentss_id) return alert('Select enrollment!')
  try {
    await axios.put(`/calendar-events/${editingEventId.value}`, {
      title: editForm.value.title.trim(),
      content: editForm.value.content.trim() || null,
      start: dayjs(editForm.value.start).format('YYYY-MM-DD HH:mm:ss'),
      end: editForm.value.end ? dayjs(editForm.value.end).format('YYYY-MM-DD HH:mm:ss') : null,
      enrollmentss_id: editForm.value.enrollmentss_id
    })
    
    // Clear the editing state
    editingEventId.value = null
    editForm.value = { title: '', content: '', start: '', end: '', enrollmentss_id: null }
    editSelectedEnrollmentText.value = ''
    
    // Reload data from server
    await loadData()
    
    successMessage.value = 'Event updated successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || 'Failed to update event')
  }
}

const deleteEvent = async (id) => {
  if (!confirm('Are you sure?')) return
  try {
    await axios.delete(`/calendar-events/${id}`)
    // Reload data from server
    await loadData()
    successMessage.value = 'Event deleted successfully!'
    setTimeout(() => successMessage.value = '', 3000)
  } catch (err) {
    console.error(err)
    alert('Failed to delete event')
  }
}

// Enrollment color
const getEnrollmentColor = (id) => enrollmentColors.value[id] || '#3B82F6'
</script>

<template>
  <Head title="Dashboard"/>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gradient bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">Dashboard Overview</h2>
        <ApplicationLogo class="h-10"/>
      </div>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <!-- Loading Overlay -->
      <div v-if="loading" class="fixed inset-0 bg-gradient-to-br from-blue-50/80 to-purple-50/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-200">
          <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent mx-auto"></div>
          <p class="mt-3 text-gray-600 font-medium">Loading events...</p>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="p-4 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center">
        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        {{ successMessage }}
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Link :href="route('students.index')" class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border border-blue-200 rounded-2xl p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:-translate-y-1">
          <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-blue-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-blue-100 rounded-lg mr-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
              </div>
              <div class="text-lg font-bold text-blue-800">Students</div>
            </div>
            <div class="text-5xl font-black text-gray-800">{{ props.counts.students }}</div>
            <div class="mt-2 text-sm text-blue-600 font-medium">View all students →</div>
          </div>
        </Link>

        <Link :href="route('settings.index')" class="group relative overflow-hidden bg-gradient-to-br from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 border border-amber-200 rounded-2xl p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:-translate-y-1">
          <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-amber-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-amber-100 rounded-lg mr-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
              </div>
              <div class="text-lg font-bold text-amber-800">Courses</div>
            </div>
            <div class="text-5xl font-black text-gray-800">{{ props.counts.courses }}</div>
            <div class="mt-2 text-sm text-amber-600 font-medium">Manage courses →</div>
          </div>
        </Link>

        <Link :href="route('enrollmentss.index')" class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border border-purple-200 rounded-2xl p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:-translate-y-1">
          <div class="absolute top-0 right-0 w-24 h-24 -mt-6 -mr-6 bg-purple-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-purple-100 rounded-lg mr-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
              </div>
              <div class="text-lg font-bold text-purple-800">Enrollments</div>
            </div>
            <div class="text-5xl font-black text-gray-800">{{ props.counts.enrollments }}</div>
            <div class="mt-2 text-sm text-purple-600 font-medium">View enrollments →</div>
          </div>
        </Link>
      </div>

      <!-- Chart -->
      <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-2xl shadow-lg border border-gray-200" style="height:350px;">
        <div class="flex items-center mb-4">
          <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mr-3">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-800">System Overview</h3>
        </div>
        <Bar :data="barData" :options="barOptions" />
      </div>

      <!-- Calendar Section -->
      <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Calendar Header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <div class="flex items-center">
              <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-gray-800">Academic Calendar</h3>
            </div>
            <div class="flex items-center space-x-2">
              <button @click="prevMonth" class="p-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm transition-colors group">
                <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
              </button>
              <div class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm">
                <span class="text-lg font-semibold text-gray-800">{{ currentMonth.format('MMMM YYYY') }}</span>
              </div>
              <button @click="nextMonth" class="p-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm transition-colors group">
                <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
              <button @click="startCreating()" class="ml-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all hover:shadow-md">
                + New Event
              </button>
            </div>
          </div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 border-b">
          <div v-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="d" 
               class="p-3 text-center font-bold text-gray-600 bg-gray-50 border-r last:border-r-0">
            {{ d }}
          </div>
        </div>
        
        <div class="grid grid-cols-7">
          <div v-for="day in monthDays" :key="day.format('YYYY-MM-DD')" 
               @click="startCreating(day)"
               :class="[
                 'min-h-[120px] border-r border-b p-2 cursor-pointer transition-all hover:bg-blue-50/50',
                 day.month() !== currentMonth.month() ? 'bg-gray-50/50' : 'bg-white',
                 'group'
               ]">
            <div class="flex justify-between items-start mb-1">
              <span :class="[
                'inline-flex items-center justify-center w-7 h-7 rounded-full text-sm font-semibold transition-all',
                day.isSame(dayjs(), 'day') 
                  ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' 
                  : day.month() !== currentMonth.month() 
                    ? 'text-gray-400 group-hover:text-gray-600' 
                    : 'text-gray-700 group-hover:text-blue-600'
              ]">
                {{ day.date() }}
              </span>
              <svg v-if="day.isSame(dayjs(), 'day')" class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <circle cx="10" cy="10" r="2"/>
              </svg>
            </div>
            
            <div class="space-y-1 overflow-y-auto max-h-24">
              <div v-for="ev in eventsForDay(day)" :key="ev.id"
                   @click.stop="startEditing(ev)"
                   class="px-2 py-1.5 rounded-lg text-white cursor-pointer transform transition-transform hover:scale-[1.02] hover:shadow-sm"
                   :style="{ 
                     background: `linear-gradient(135deg, ${getEnrollmentColor(ev.enrollmentss_id)}CC 0%, ${getEnrollmentColor(ev.enrollmentss_id)} 100%)`,
                     borderLeft: `4px solid ${getEnrollmentColor(ev.enrollmentss_id)}`
                   }">
                <div class="text-xs font-bold truncate">{{ ev.title }}</div>
                <div class="text-[0.65rem] opacity-90 truncate">{{ ev.content }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CREATE Modal -->
      <div v-if="creatingNew" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-b from-white to-gray-50 p-6 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200">
          <div class="flex items-center mb-4">
            <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mr-3">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Create New Event</h3>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Event Title *</label>
              <input v-model="createForm.title" placeholder="Enter event title" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"/>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea v-model="createForm.content" placeholder="Enter event description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
                <input type="datetime-local" v-model="createForm.start" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"/>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input type="datetime-local" v-model="createForm.end" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"/>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment *</label>
              <div class="relative">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-left bg-white hover:bg-gray-50 transition-all flex items-center justify-between">
                  <span :class="selectedEnrollmentText ? 'text-gray-800' : 'text-gray-500'">
                    {{ selectedEnrollmentText || 'Select an enrollment' }}
                  </span>
                  <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                
                <div v-if="dropdownOpen" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-xl max-h-64 overflow-y-auto">
                  <div v-for="en in enrollments" :key="en.id" 
                       @click="selectEnrollment(en)"
                       class="px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-blue-50 cursor-pointer flex items-center transition-colors">
                    <div class="w-3 h-3 rounded-full mr-3 flex-shrink-0" :style="{ backgroundColor: getEnrollmentColor(en.id) }"></div>
                    <span class="text-gray-800 font-medium">{{ en.title }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
            <button @click="creatingNew = false" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
              Cancel
            </button>
            <button @click="createEvent" 
                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all">
              Create Event
            </button>
          </div>
        </div>
      </div>

      <!-- EDIT Modal -->
      <div v-if="editingEventId" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-b from-white to-gray-50 p-6 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200">
          <div class="flex items-center mb-4">
            <div class="p-2 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg mr-3">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Edit Event</h3>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Event Title *</label>
              <input v-model="editForm.title" placeholder="Enter event title" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"/>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea v-model="editForm.content" placeholder="Enter event description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
                <input type="datetime-local" v-model="editForm.start" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"/>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input type="datetime-local" v-model="editForm.end" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"/>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment *</label>
              <div class="relative">
                <button @click="editDropdownOpen = !editDropdownOpen" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-left bg-white hover:bg-gray-50 transition-all flex items-center justify-between">
                  <span :class="editSelectedEnrollmentText ? 'text-gray-800' : 'text-gray-500'">
                    {{ editSelectedEnrollmentText || 'Select an enrollment' }}
                  </span>
                  <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="{ 'rotate-180': editDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                
                <div v-if="editDropdownOpen" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-xl max-h-64 overflow-y-auto">
                  <div v-for="en in enrollments" :key="en.id" 
                       @click="selectEditEnrollment(en)"
                       class="px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-emerald-50 cursor-pointer flex items-center transition-colors">
                    <div class="w-3 h-3 rounded-full mr-3 flex-shrink-0" :style="{ backgroundColor: getEnrollmentColor(en.id) }"></div>
                    <span class="text-gray-800 font-medium">{{ en.title }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
            <button @click="deleteEvent(editingEventId)" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Delete
            </button>
            
            <div class="flex space-x-3">
              <button @click="editingEventId = null" 
                      class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                Cancel
              </button>
              <button @click="saveEdit" 
                      class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all">
                Save Changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.text-gradient {
  background-clip: text;
  -webkit-background-clip: text;
}

.card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.7s;
}

.card:hover::before {
  left: 100%;
}

/* Smooth scrolling for dropdown */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

/* Modal animation */
@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.fixed.inset-0.bg-black\/50 {
  animation: modalFadeIn 0.2s ease-out;
}

.bg-white.p-6 {
  animation: modalFadeIn 0.3s ease-out;
}

/* Calendar day hover effect */
.min-h-\[120px\].border-r {
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.min-h-\[120px\].border-r:hover {
  transform: translateY(-2px);
}

/* Event card hover effect */
.event-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.event-card:hover {
  transform: translateY(-1px) scale(1.01);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>