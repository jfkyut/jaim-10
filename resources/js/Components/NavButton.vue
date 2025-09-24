<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    active: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const classes = computed(() =>
    props.active
        ? 'flex items-center w-full p-2 text-zinc-900 dark:text-zinc-100 bg-zinc-100 dark:bg-zinc-700 rounded-lg transition-colors duration-150 ease-in-out disabled:opacity-50'
        : 'flex items-center w-full p-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 rounded-lg transition-colors duration-150 ease-in-out disabled:opacity-50'
);
</script>

<template>
    <button
        :type="type"
        :class="classes"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>