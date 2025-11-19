<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const events = ref([])
const newEvent = ref({ title: '', content: '', start: '', end: '' })
const editingEvent = ref(null)

const contentModel = computed({
  get() {
    return editingEvent.value ? editingEvent.value.content : newEvent.value.content
  },
  set(val) {
    if (editingEvent.value) editingEvent.value.content = val
    else newEvent.value.content = val
  }
})

const startModel = computed({
  get() {
    return editingEvent.value ? editingEvent.value.start : newEvent.value.start
  },
  set(val) {
    if (editingEvent.value) editingEvent.value.start = val
    else newEvent.value.start = val
  }
})

const endModel = computed({
  get() {
    return editingEvent.value ? editingEvent.value.end : newEvent.value.end
  },
  set(val) {
    if (editingEvent.value) editingEvent.value.end = val
    else newEvent.value.end = val
  }
})

// Load, add, edit, delete methods...
</script>

<template>
  <textarea v-model="contentModel" placeholder="Content" class="border p-2 mb-2 w-full"></textarea>
  <input type="date" v-model="startModel" class="border p-2 mb-2 w-full"/>
  <input type="date" v-model="endModel" class="border p-2 mb-2 w-full"/>
</template>
