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

// Edit state
const editingMessageId = ref(null)
const editMessageText = ref('')

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
  editingMessageId.value = null
  editMessageText.value = ''
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

// Start editing a message
const startEdit = (message) => {
  if (message.sender_id !== props.authUser.id) return
  
  editingMessageId.value = message.id
  editMessageText.value = message.message
}

// Cancel editing
const cancelEdit = () => {
  editingMessageId.value = null
  editMessageText.value = ''
}

// Save edited message
const saveEdit = async () => {
  if (!editMessageText.value.trim()) return

  try {
    const { data } = await axios.put(`/chat/messages/${editingMessageId.value}`, {
      message: editMessageText.value,
    })

    // Update the message in the local array
    const index = chatMessages.value.findIndex(msg => msg.id === editingMessageId.value)
    if (index !== -1) {
      chatMessages.value[index] = data
    }

    cancelEdit()
  } catch (err) {
    console.error('Failed to edit message:', err)
    alert('Failed to edit message. Please try again.')
  }
}

// Delete message
const deleteMessage = async (messageId, senderId) => {
  if (senderId !== props.authUser.id) return
  
  if (!confirm('Are you sure you want to delete this message?')) return

  try {
    await axios.delete(`/chat/messages/${messageId}`)

    // Remove the message from the local array
    chatMessages.value = chatMessages.value.filter(msg => msg.id !== messageId)
  } catch (err) {
    console.error('Failed to delete message:', err)
    alert('Failed to delete message. Please try again.')
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
                'justify-start': msg.sender_id !== props.authUser.id,
              }"
            >
              <div
                class="max-w-[70%] px-3 py-2 rounded-xl shadow-sm relative group"
                :class="msg.sender_id === props.authUser.id
                  ? 'bg-blue-600 text-white rounded-br-none'
                  : 'bg-gray-100 text-gray-800 rounded-bl-none'"
              >
                <!-- Edit mode -->
                <div v-if="editingMessageId === msg.id" class="space-y-2">
                  <textarea
                    v-model="editMessageText"
                    @keydown.enter.prevent="saveEdit"
                    class="w-full p-2 border rounded text-gray-800"
                    rows="3"
                    autofocus
                  ></textarea>
                  <div class="flex space-x-2 justify-end">
                    <button
                      @click="cancelEdit"
                      class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm"
                    >
                      Cancel
                    </button>
                    <button
                      @click="saveEdit"
                      class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm"
                    >
                      Save
                    </button>
                  </div>
                </div>

                <!-- View mode -->
                <div v-else>
                  <p>{{ msg.message }}</p>
                  <div class="flex justify-between items-center mt-1">
                    <p class="text-xs opacity-70">
                      {{ msg.sender_id === props.authUser.id ? 'You' : msg.sender?.name }}
                      <span v-if="msg.updated_at !== msg.created_at" class="italic">
                        (edited)
                      </span>
                    </p>
                    
                    <!-- Actions (only for own messages) -->
                    <div 
                      v-if="msg.sender_id === props.authUser.id"
                      class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <button
                        @click="startEdit(msg)"
                        class="text-xs text-blue-300 hover:text-blue-100"
                        title="Edit"
                      >
                        ✏️
                      </button>
                      <button
                        @click="deleteMessage(msg.id, msg.sender_id)"
                        class="text-xs text-red-300 hover:text-red-100"
                        title="Delete"
                      >
                        🗑️
                      </button>
                    </div>
                  </div>
                </div>
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
              :disabled="editingMessageId !== null"
            />
            <button
              @click="sendMessage"
              class="bg-blue-600 text-white px-5 rounded-r-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="editingMessageId !== null"
            >
              Send
            </button>
          </div>
        </div>

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