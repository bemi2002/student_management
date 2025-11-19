<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, nextTick } from 'vue'
import axios from 'axios'

// Props
const props = defineProps({
  users: Array,
  authUser: Object,
})

// Reactive state
const selectedUser = ref(null)
const chatMessages = ref([])
const newMessage = ref('')
const chatBox = ref(null)

// Auto scroll to bottom
const scrollToBottom = () => {
  nextTick(() => {
    if (chatBox.value) {
      chatBox.value.scrollTop = chatBox.value.scrollHeight
    }
  })
}

// Load messages with selected user
const loadMessages = async (userId) => {
  try {
    const { data } = await axios.get(`/teacher-chat/messages/${userId}`)
    chatMessages.value = data
    scrollToBottom()
  } catch (error) {
    console.error("Failed to load messages:", error)
  }
}

// When clicking a user
const selectUser = (user) => {
  selectedUser.value = user
  loadMessages(user.id)
}

// Send a new message
const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) return

  try {
    const { data } = await axios.post('/teacher-chat', {
      receiver_id: selectedUser.value.id,
      message: newMessage.value,
    })

    chatMessages.value.push(data)
    newMessage.value = ''
    scrollToBottom()
  } catch (error) {
    console.error("Failed to send message:", error)
  }
}
</script>

<template>
  <Head title="Chat" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold text-gray-800">💬 Chat</h2>
    </template>

    <div class="py-8 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- User List -->
      <div class="bg-white rounded-xl shadow p-4">
        <h3 class="text-lg font-semibold mb-3 text-gray-700">Users</h3>

        <ul>
          <li
            v-for="user in props.users"
            :key="user.id"
            @click="selectUser(user)"
            class="p-3 rounded-lg cursor-pointer mb-2 transition hover:bg-blue-100"
            :class="{ 'bg-blue-200 font-semibold': selectedUser?.id === user.id }"
          >
            👤 {{ user.name }}
          </li>
        </ul>

        <p v-if="props.users.length === 0" class="text-gray-500 text-center mt-4">
          No users available.
        </p>
      </div>

      <!-- Chat Window -->
      <div class="bg-white rounded-xl shadow p-4 md:col-span-2 flex flex-col">
        
        <div v-if="selectedUser" class="flex-1 flex flex-col">
          <div class="border-b pb-2 mb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Chat with {{ selectedUser.name }}
            </h3>
          </div>

          <!-- Messages -->
          <div
            ref="chatBox"
            class="flex-1 overflow-y-auto p-3 space-y-3 border rounded-lg"
            style="min-height: 400px;"
          >
            <div
              v-for="msg in chatMessages"
              :key="msg.id"
              class="flex"
              :class="{
                'justify-end': msg.sender_id === props.authUser.id,
                'justify-start': msg.sender_id !== props.authUser.id
              }"
            >
              <div
                class="max-w-[70%] px-3 py-2 rounded-xl shadow-sm"
                :class="msg.sender_id === props.authUser.id
                  ? 'bg-blue-600 text-white rounded-br-none'
                  : 'bg-gray-100 text-gray-800 rounded-bl-none'"
              >
                <p>{{ msg.message }}</p>
                <p class="text-xs mt-1 opacity-70 text-right">
                  {{ msg.sender_id === props.authUser.id ? 'You' : msg.sender?.name }}
                </p>
              </div>
            </div>
          </div>

          <!-- Input -->
          <div class="mt-3 flex">
            <input
              v-model="newMessage"
              @keyup.enter="sendMessage"
              type="text"
              class="flex-1 border rounded-l-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
              placeholder="Type your message..."
            />
            <button
              @click="sendMessage"
              class="bg-blue-600 text-white px-5 rounded-r-lg hover:bg-blue-700 transition"
            >
              Send
            </button>
          </div>
        </div>

        <!-- No user selected -->
        <div v-else class="flex-1 flex items-center justify-center text-gray-400 text-lg">
          👈 Select a user to start chatting.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
