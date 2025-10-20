<script setup>
defineProps({
  type: {
    type: String,
    default: 'button'
  },
  class: String
})
const emit = defineEmits(['click', 'mouseover', 'mouseout']);
</script>

<template>
  <li class="list-none">
    <button
      :type="type"
      @click="emit('click')"
      @mouseover="emit('mouseover')"
      @mouseout="emit('mouseout')"
      :class="class"
      class="block px-4 py-2 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white w-full text-start">
      <slot/>
    </button>
  </li>
</template>