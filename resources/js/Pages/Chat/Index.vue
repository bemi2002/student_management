<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

// Props from Laravel
const props = defineProps({
  users: Array,
  authUser: Object,
})

// Reactive state
const selectedUser = ref(null)
const chatMessages = ref([])
const newMessage = ref('')
const chatBox = ref(null)
const showUserList = ref(true)
const isMobile = ref(false)
const windowWidth = ref(window.innerWidth)

// Auto scroll
const scrollToBottom = () => {
  nextTick(() => {
    if (chatBox.value) {
      chatBox.value.scrollTop = chatBox.value.scrollHeight
    }
  })
}

// Load conversation with selected user
const loadMessages = async (userId) => {
  try {
    const { data } = await axios.get(`/chat/messages/${userId}`)
    chatMessages.value = data
    scrollToBottom()
  } catch (err) {
    console.error('Failed to load messages:', err)
  }
}

// Select chat partner
const selectUser = (user) => {
  selectedUser.value = user
  loadMessages(user.id)
  // On mobile, hide user list after selecting
  if (isMobile.value) {
    showUserList.value = false
  }
}

// Send message
const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) return

  try {
    const { data } = await axios.post('/chat', {
      receiver_id: selectedUser.value.id,
      message: newMessage.value,
    })

    chatMessages.value.push(data)
    newMessage.value = ''
    scrollToBottom()
  } catch (err) {
    console.error('Failed to send message:', err)
  }
}

// Toggle user list on mobile
const toggleUserList = () => {
  showUserList.value = !showUserList.value
}

// Handle window resize
const handleResize = () => {
  windowWidth.value = window.innerWidth
  isMobile.value = window.innerWidth < 1024
  if (!isMobile.value) {
    showUserList.value = true
  }
}

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)
  
  if (isMobile.value) {
    showUserList.value = false
  }
})
</script>

<template>
  <Head title="Chat" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl sm:text-2xl font-bold text-gradient bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-sky-500">
          💬 Blue Chat System
        </h2>
        <div class="px-3 py-1 bg-gradient-to-r from-blue-100 to-sky-100 text-blue-700 rounded-full text-xs sm:text-sm font-medium">
          Users: {{ users.length }}
        </div>
      </div>
    </template>

    <div class="py-4 sm:py-6 lg:py-8 max-w-6xl mx-auto px-3 sm:px-4">
      <!-- Mobile Toggle Button - ALWAYS SHOWN ON MOBILE -->
      <button
        v-if="isMobile"
        @click="toggleUserList"
        class="lg:hidden mb-4 w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-sky-500 text-white rounded-xl flex items-center justify-center gap-2 shadow-lg hover:from-blue-600 hover:to-sky-600 transition-all"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        {{ showUserList ? 'Hide User List' : 'Show User List' }}
      </button>

      <!-- Chat Container -->
      <div class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-2xl shadow-xl sm:shadow-2xl border border-blue-100 overflow-hidden">
        <div class="flex flex-col lg:grid lg:grid-cols-3 h-[calc(100vh-200px)] sm:h-[calc(100vh-220px)] lg:h-[70vh]">
          
          <!-- User List Sidebar -->
          <div 
            :class="[
              'lg:col-span-1 border-r border-blue-100 flex flex-col transition-all duration-300',
              (showUserList || !isMobile) ? 'block' : 'hidden lg:block'
            ]"
            class="w-full lg:w-auto"
          >
            <!-- Sidebar Header -->
            <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-sky-50 border-b border-blue-100">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-blue-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3" />
                    </svg>
                    Users
                  </h3>
                  <p class="text-xs sm:text-sm text-blue-600 mt-1">Select a user to start chatting</p>
                </div>
                <!-- Close button for mobile -->
                <button
                  v-if="isMobile && showUserList"
                  @click="showUserList = false"
                  class="lg:hidden p-2 bg-blue-100 hover:bg-blue-200 rounded-lg transition text-blue-600"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- User List -->
            <div class="flex-1 overflow-y-auto p-3 sm:p-4">
              <div v-if="users.length === 0" class="text-center py-8 sm:py-12">
                <div class="p-3 sm:p-4 bg-gradient-to-r from-blue-100 to-blue-200 rounded-2xl inline-block mb-4">
                  <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                  </svg>
                </div>
                <p class="text-blue-500 text-sm sm:text-base">No users available.</p>
              </div>

              <ul class="space-y-2">
                <li
                  v-for="user in props.users"
                  :key="user.id"
                  @click="selectUser(user)"
                  class="group p-3 sm:p-4 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-md"
                  :class="[
                    selectedUser?.id === user.id 
                      ? 'bg-gradient-to-r from-blue-500 to-sky-500 text-white shadow-lg' 
                      : 'bg-gradient-to-r from-blue-50 to-sky-50 hover:from-blue-100 hover:to-sky-100 border border-blue-100'
                  ]"
                >
                  <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center shadow-lg flex-shrink-0"
                         :class="[
                           selectedUser?.id === user.id
                             ? 'bg-gradient-to-r from-white/30 to-white/20 border-2 border-white/40'
                             : 'bg-gradient-to-r from-blue-500 to-sky-500'
                         ]">
                      <span class="font-bold text-xs sm:text-sm text-white">
                        {{ user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) }}
                      </span>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                      <div class="font-bold text-sm sm:text-base truncate" :class="selectedUser?.id === user.id ? 'text-white' : 'text-blue-900'">
                        {{ user.name }}
                      </div>
                      <div class="text-xs sm:text-sm truncate" :class="selectedUser?.id === user.id ? 'text-white/80' : 'text-blue-600'">
                        {{ user.email }}
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Chat Window -->
          <div class="lg:col-span-2 flex flex-col flex-1">
            <!-- Chat Header -->
            <div v-if="selectedUser" class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-sky-50 border-b border-blue-100">
              <div class="flex items-center justify-between">
                <div class="flex items-center min-w-0">
                  <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-r from-blue-500 to-sky-500 rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                    <span class="text-white font-bold text-sm sm:text-lg">
                      {{ selectedUser.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) }}
                    </span>
                  </div>
                  <div class="ml-3 sm:ml-4 min-w-0">
                    <h3 class="text-base sm:text-xl font-bold text-blue-900 truncate">
                      {{ selectedUser.name }}
                    </h3>
                    <p class="text-xs sm:text-sm text-blue-600 truncate">{{ selectedUser.email }}</p>
                  </div>
                </div>
                <!-- Mobile back button -->
                <div class="flex items-center space-x-2">
                  <button
                    v-if="isMobile"
                    @click="showUserList = true"
                    class="lg:hidden p-2 bg-gradient-to-r from-blue-100 to-sky-100 hover:from-blue-200 hover:to-sky-200 rounded-lg transition flex items-center gap-1 text-blue-700"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="text-xs font-medium">Users</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col p-3 sm:p-4 bg-gradient-to-b from-white to-blue-50/50">
              <div v-if="selectedUser" class="flex-1 flex flex-col">
                <!-- Messages Container -->
                <div ref="chatBox"
                  class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 sm:space-y-6 rounded-xl"
                  style="background: linear-gradient(180deg, rgba(239, 246, 255, 0.8) 0%, rgba(235, 248, 255, 0.8) 100%);">

                  <!-- Empty State -->
                  <div v-if="chatMessages.length === 0" class="h-full flex flex-col items-center justify-center py-8 sm:py-12">
                    <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-sky-50 rounded-2xl mb-4 sm:mb-6 border border-blue-100">
                      <svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                      </svg>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 text-center">No messages yet</h4>
                    <p class="text-blue-600 mb-6 text-center text-sm sm:text-base">Send a message to start the conversation!</p>
                  </div>

                  <!-- Messages -->
                  <div
                    v-for="msg in chatMessages"
                    :key="msg.id"
                    class="flex"
                    :class="{
                      'justify-end': msg.sender_id === props.authUser.id,
                      'justify-start': msg.sender_id !== props.authUser.id,
                    }"
                  >
                    <div
                      class="max-w-[85%] sm:max-w-[80%] rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-[1.02]"
                      :class="msg.sender_id === props.authUser.id
                        ? 'bg-gradient-to-r from-blue-500 to-sky-500 text-white rounded-br-none'
                        : 'bg-gradient-to-r from-white to-blue-50 text-blue-900 border border-blue-200 rounded-bl-none'"
                    >
                      <div class="px-4 py-3 sm:px-5 sm:py-3">
                        <p class="text-sm sm:text-base break-words">
                          {{ msg.message }}
                        </p>
                        <div class="flex justify-between items-center mt-2">
                          <p class="text-xs opacity-80" :class="msg.sender_id === props.authUser.id ? 'text-white/90' : 'text-blue-700'">
                            {{ msg.sender_id === props.authUser.id ? 'You' : msg.sender?.name }}
                          </p>
                          <p class="text-xs" :class="msg.sender_id === props.authUser.id ? 'text-white/80' : 'text-blue-600/80'">
                            {{ new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Message Input -->
                <div class="mt-4 p-3 sm:p-4 bg-gradient-to-r from-white to-blue-50 border border-blue-200 rounded-2xl shadow-sm">
                  <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="flex-1 relative">
                      <input
                        v-model="newMessage"
                        @keyup.enter="sendMessage"
                        type="text"
                        class="w-full pl-4 pr-4 py-2 sm:py-3 text-sm sm:text-base bg-white border-2 border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl shadow-sm transition-all outline-none text-blue-900 placeholder-blue-400"
                        placeholder="Type your message..."
                      />
                    </div>

                    <button
                      @click="sendMessage"
                      class="group px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white font-bold rounded-xl shadow-lg transition-all duration-300 flex items-center flex-shrink-0"
                      :disabled="!newMessage.trim()"
                      :class="{ 'opacity-50 cursor-not-allowed': !newMessage.trim() }"
                    >
                      <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                      </svg>
                      <span class="hidden sm:inline">Send</span>
                      <span class="sm:hidden">S</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- No User Selected -->
              <div v-else class="flex-1 flex flex-col items-center justify-center p-4">
                <div class="p-6 sm:p-8 bg-gradient-to-r from-blue-50 to-sky-50 rounded-2xl mb-6 border border-blue-100">
                  <svg class="w-16 h-16 sm:w-24 sm:h-24 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-blue-900 mb-3 text-center">Welcome to Blue Chat</h3>
                <p class="text-blue-600 text-sm sm:text-lg mb-8 max-w-md text-center">
                  Select a user from the sidebar to start chatting.
                </p>
                <!-- Mobile instruction -->
                <div v-if="isMobile && !showUserList" class="p-4 bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl border border-blue-200 w-full max-w-md">
                  <p class="text-sm text-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    Tap on "Show User List" button above to see available users
                  </p>
                </div>
              </div>
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

/* Blue Scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

@media (min-width: 640px) {
  ::-webkit-scrollbar {
    width: 8px;
  }
}

::-webkit-scrollbar-track {
  background: rgba(219, 234, 254, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #3b82f6, #0ea5e9);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #2563eb, #0284c7);
}

/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.flex.justify-start div,
.flex.justify-end div {
  animation: fadeInUp 0.25s ease-out;
}

/* Message bubble animations */
.flex.justify-end div {
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
}

.flex.justify-start div {
  box-shadow: 0 4px 15px rgba(14, 165, 233, 0.1);
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Blue gradient backgrounds */
.bg-gradient-to-r.from-blue-500.to-sky-500 {
  background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
}

.bg-gradient-to-r.from-blue-600.to-sky-500 {
  background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
}

.bg-gradient-to-r.from-blue-50.to-sky-50 {
  background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
}
</style>