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
</script>

<template>
  <Head title="Chat" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
          💬 Chat System
        </h2>
        <div class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium">
          {{ users.length }} users
        </div>
      </div>
    </template>

    <div class="py-8 max-w-6xl mx-auto px-4">
      <!-- Chat Container -->
      <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 h-[70vh]">
          
          <!-- User List Sidebar -->
          <div class="lg:col-span-1 border-r border-gray-200 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
              <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Online Users
              </h3>
              <p class="text-sm text-gray-600 mt-1">Select a user to start chatting</p>
            </div>

            <!-- User List -->
            <div class="flex-1 overflow-y-auto p-4">
              <div v-if="users.length === 0" class="text-center py-12">
                <div class="p-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl inline-block mb-4">
                  <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                  </svg>
                </div>
                <p class="text-gray-500">No users available.</p>
              </div>

              <ul class="space-y-2">
                <li
                  v-for="user in props.users"
                  :key="user.id"
                  @click="selectUser(user)"
                  class="group p-4 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-md"
                  :class="[
                    selectedUser?.id === user.id 
                      ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg' 
                      : 'bg-gradient-to-r from-gray-50 to-gray-100 hover:from-indigo-50 hover:to-purple-50'
                  ]"
                >
                  <div class="flex items-center">
                    <div class="relative">
                      <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg"
                           :class="[
                             selectedUser?.id === user.id
                               ? 'bg-gradient-to-r from-white/30 to-white/20 border-2 border-white/40'
                               : 'bg-gradient-to-r from-indigo-500 to-purple-500'
                           ]">
                        <span class="font-bold text-sm"
                              :class="selectedUser?.id === user.id ? 'text-white' : 'text-white'">
                          {{ user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) }}
                        </span>
                      </div>
                      <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="ml-4">
                      <div class="font-bold" :class="selectedUser?.id === user.id ? 'text-white' : 'text-gray-900'">
                        {{ user.name }}
                      </div>
                      <div class="text-sm" :class="selectedUser?.id === user.id ? 'text-white/80' : 'text-gray-600'">
                        {{ user.email }}
                      </div>
                    </div>
                    <div v-if="selectedUser?.id === user.id" class="ml-auto">
                      <svg class="w-5 h-5 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                      </svg>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Chat Window -->
          <div class="lg:col-span-2 flex flex-col">
            <!-- Chat Header -->
            <div v-if="selectedUser" class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">
                      {{ selectedUser.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                      {{ selectedUser.name }}
                      <span class="ml-2 w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    </h3>
                    <p class="text-sm text-gray-600">{{ selectedUser.email }}</p>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button class="p-2 bg-white hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col p-4 bg-gradient-to-b from-white to-gray-50/50">
              <div v-if="selectedUser" class="flex-1 flex flex-col">
                <!-- Messages Container -->
                <div
                  ref="chatBox"
                  class="flex-1 overflow-y-auto p-6 space-y-6 rounded-xl"
                  style="background: linear-gradient(180deg, rgba(249, 250, 251, 0.8) 0%, rgba(243, 244, 246, 0.8) 100%);"
                >
                  <!-- Empty State -->
                  <div v-if="chatMessages.length === 0" class="h-full flex flex-col items-center justify-center py-12">
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl mb-6">
                      <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                      </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">No messages yet</h4>
                    <p class="text-gray-600 mb-6">Send a message to start the conversation!</p>
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
                      class="max-w-[80%] rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-[1.02]"
                      :class="msg.sender_id === props.authUser.id
                        ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-br-none'
                        : 'bg-gradient-to-r from-white to-gray-100 text-gray-800 border border-gray-200 rounded-bl-none'"
                    >
                      <div class="px-5 py-3">
                        <p class="text-sm md:text-base" :class="msg.sender_id === props.authUser.id ? 'text-white' : 'text-gray-900'">
                          {{ msg.message }}
                        </p>
                        <div class="flex justify-between items-center mt-2">
                          <p class="text-xs opacity-80" :class="msg.sender_id === props.authUser.id ? 'text-white/80' : 'text-gray-600'">
                            {{ msg.sender_id === props.authUser.id ? 'You' : msg.sender?.name }}
                          </p>
                          <div class="flex items-center space-x-2">
                            <p class="text-xs opacity-60" :class="msg.sender_id === props.authUser.id ? 'text-white/60' : 'text-gray-500'">
                              {{ new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                            </p>
                            <svg v-if="msg.sender_id === props.authUser.id" class="w-3 h-3 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Message Input -->
                <div class="mt-4 p-4 bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-sm">
                  <div class="flex items-center space-x-3">
                    <div class="flex-1 relative">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                      </div>
                      <input
                        v-model="newMessage"
                        @keyup.enter="sendMessage"
                        type="text"
                        class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl shadow-sm transition-all outline-none"
                        placeholder="Type your message here..."
                      />
                      <div class="absolute inset-y-0 right-0 pr-3 flex items-center space-x-2">
                        <button class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                          </svg>
                        </button>
                        <button class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                    <button
                      @click="sendMessage"
                      class="group px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center"
                      :disabled="!newMessage.trim()"
                      :class="{ 'opacity-50 cursor-not-allowed': !newMessage.trim() }"
                    >
                      <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                      </svg>
                      Send
                    </button>
                  </div>
                </div>
              </div>

              <!-- No User Selected State -->
              <div v-else class="flex-1 flex flex-col items-center justify-center">
                <div class="p-8 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl mb-6">
                  <svg class="w-24 h-24 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Welcome to Chat System</h3>
                <p class="text-gray-600 text-lg mb-8 max-w-md text-center">
                  Select a user from the sidebar to start a conversation
                </p>
                <div class="flex items-center space-x-2 text-gray-500">
                  <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                  </svg>
                  <span class="text-sm">← Click on a user to begin chatting</span>
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

/* Custom scrollbar for chat */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(243, 244, 246, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #a5b4fc, #8b5cf6);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #818cf8, #7c3aed);
}

/* Message bubble animations */
.flex.justify-start div,
.flex.justify-end div {
  animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Online status animation */
.bg-green-500.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

/* User list item hover effect */
.group:hover {
  transform: translateX(4px);
  transition: transform 0.2s ease;
}

/* Chat container gradient border */
.bg-gradient-to-br {
  position: relative;
}

.bg-gradient-to-br::before {
  content: '';
  position: absolute;
  inset: -1px;
  border-radius: 1rem;
  padding: 1px;
  background: linear-gradient(135deg, #8b5cf6, #6366f1, #a855f7);
  -webkit-mask: 
    linear-gradient(#fff 0 0) content-box, 
    linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
}

/* Typing indicator */
@keyframes typing {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
}

.animate-pulse svg {
  animation: typing 1.2s ease-in-out infinite;
}

/* Message sent animation */
.justify-end div {
  animation: slideInRight 0.3s ease-out;
}

.justify-start div {
  animation: slideInLeft 0.3s ease-out;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>