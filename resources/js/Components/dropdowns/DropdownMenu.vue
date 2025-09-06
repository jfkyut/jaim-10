<script setup>

defineProps({
  position: {
    type: String,
    default: 'right-0 top-12'
  },
  show: {
    type: Boolean,
    default: true
  },
  isDivided: {
    type: Boolean,
    default: false
  }
});

</script>

<template>
  <div
        id="dropdownBottom"
        :class="[show ? 'opacity-100' : 'opacity-0 invisible', position]"
        class="z-10 absolute bg-white divide-y divide-zinc-100 duration-300 rounded-lg shadow-lg w-44 dark:bg-zinc-700 border dark:border-zinc-600">
        <ul class="py-2 text-xs text-zinc-700 dark:text-zinc-200" :class="isDivided && 'divide-y divide-zinc-100 dark:divide-zinc-600'" aria-labelledby="dropdownBottomButton">
            <slot />
        </ul>
  </div>
</template>
