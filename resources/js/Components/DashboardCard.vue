<script setup>
defineProps({
  title: String,
  count: Number
})
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6 text-center">
    <h3 class="text-lg font-semibold">{{ title }}</h3>
    <p class="text-2xl mt-2">{{ count }}</p>
  </div>
</template>



