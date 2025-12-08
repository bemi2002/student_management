<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Events</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div id="app"></div>

    <script>
        const { createApp } = Vue;

        const CalendarEventsIndex = {
            template: `
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ event.title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ event.content || '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ event.enrollment?.name || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(event.start) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ event.end ? formatDate(event.end) : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button @click="editEvent(event)" class="text-indigo-600 hover:text-indigo-900 transition-colors">Edit</button>
                                        <button @click="deleteEvent(event.id)" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="events.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
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

                    <!-- Create/Edit Modal -->
                    <div v-if="showCreateForm || editingEvent" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">{{ editingEvent ? 'Edit Event' : 'Create New Event' }}</h2>
                                <EventForm
                                    :event="editingEvent"
                                    :enrollments="enrollments"
                                    @save="editingEvent ? handleUpdate : handleCreate"
                                    @cancel="cancelForm"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            `,
            components: {
                EventForm: {
                    template: `
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                <input v-model="form.title" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                <textarea v-model="form.content" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment *</label>
                                <select v-model="form.enrollmentss_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="" disabled>Select Enrollment</option>
                                    <option v-for="enr in enrollments" :key="enr.id" :value="enr.id">{{ enr.name }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                                <input v-model="form.start" type="datetime-local" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input v-model="form.end" type="datetime-local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" @click="$emit('cancel')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >Cancel</button>
                                <button type="submit" :disabled="loading"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-blue-400 disabled:cursor-not-allowed"
                                >{{ loading ? 'Saving...' : (event ? 'Update' : 'Create') }} Event</button>
                            </div>
                        </form>
                    `,
                    props: ['event', 'enrollments'],
                    emits: ['save', 'cancel'],
                    data() {
                        return {
                            loading: false,
                            form: {
                                title: '',
                                content: '',
                                start: '',
                                end: '',
                                enrollmentss_id: ''
                            }
                        }
                    },
                    watch: {
                        event: {
                            handler(newEvent) {
                                if (newEvent) {
                                    this.form.title = newEvent.title || ''
                                    this.form.content = newEvent.content || ''
                                    this.form.start = this.formatDateForInput(newEvent.start)
                                    this.form.end = this.formatDateForInput(newEvent.end)
                                    this.form.enrollmentss_id = newEvent.enrollmentss_id || ''
                                } else {
                                    this.form.title = ''
                                    this.form.content = ''
                                    this.form.start = this.formatDateForInput(new Date())
                                    this.form.end = ''
                                    this.form.enrollmentss_id = ''
                                }
                            },
                            immediate: true
                        }
                    },
                    methods: {
                        formatDateForInput(dateString) {
                            if (!dateString) return ''
                            const date = new Date(dateString)
                            return date.toISOString().slice(0, 16)
                        },
                        async submit() {
                            this.loading = true
                            try {
                                const eventData = { ...this.form }
                                this.$emit('save', eventData)
                            } catch (error) {
                                console.error('Form error:', error)
                            } finally {
                                this.loading = false
                            }
                        }
                    }
                }
            },
            data() {
                return {
                    events: [],
                    enrollments: [], // load from API
                    loading: false,
                    showCreateForm: false,
                    editingEvent: null,
                    successMessage: ''
                }
            },
            async mounted() {
                await this.loadEvents()
                await this.loadEnrollments()
            },
            methods: {
                async loadEvents() {
                    this.loading = true
                    try {
                        const response = await axios.get('/calendar-events')
                        this.events = response.data
                    } catch (error) {
                        console.error('Error loading events:', error)
                    } finally { this.loading = false }
                },
                async loadEnrollments() {
                    try {
                        const response = await axios.get('/enrollments') // API endpoint to fetch enrollments
                        this.enrollments = response.data
                    } catch (error) {
                        console.error('Error loading enrollments:', error)
                    }
                },
                formatDate(dateString) {
                    return new Date(dateString).toLocaleString('en-US', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' })
                },
                cancelForm() {
                    this.showCreateForm = false
                    this.editingEvent = null
                },
                async handleCreate(eventData) {
                    try {
                        const response = await axios.post('/calendar-events', eventData)
                        this.events.push(response.data)
                        this.cancelForm()
                        this.successMessage = 'Event created successfully!'
                        setTimeout(() => this.successMessage = '', 3000)
                    } catch (error) {
                        console.error('Error creating event:', error)
                        alert('Failed to create event')
                    }
                },
                editEvent(event) { this.editingEvent = { ...event } },
                async handleUpdate(eventData) {
                    try {
                        const response = await axios.put('/calendar-events/' + this.editingEvent.id, eventData)
                        const index = this.events.findIndex(e => e.id === this.editingEvent.id)
                        if (index !== -1) this.events[index] = response.data
                        this.cancelForm()
                        this.successMessage = 'Event updated successfully!'
                        setTimeout(() => this.successMessage = '', 3000)
                    } catch (error) {
                        console.error('Error updating event:', error)
                        alert('Failed to update event')
                    }
                },
                async deleteEvent(eventId) {
                    if (!confirm('Are you sure you want to delete this event?')) return
                    try {
                        await axios.delete('/calendar-events/' + eventId)
                        this.events = this.events.filter(e => e.id !== eventId)
                        this.successMessage = 'Event deleted successfully!'
                        setTimeout(() => this.successMessage = '', 3000)
                    } catch (error) {
                        console.error('Error deleting event:', error)
                        alert('Failed to delete event')
                    }
                }
            }
        }

        // Axios setup
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        createApp(CalendarEventsIndex).mount('#app')
    </script>
</body>
</html>
