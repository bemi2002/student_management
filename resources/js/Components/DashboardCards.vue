<script setup>
defineProps({
  title: String,
  count: [Number, String],
})
</script>

<template>
  <div class="bg-white shadow rounded p-4">
    <h3 class="text-lg font-medium">{{ title }}</h3>
    <p class="text-2xl font-bold mt-2">{{ count }}</p>
  </div>
</template>
