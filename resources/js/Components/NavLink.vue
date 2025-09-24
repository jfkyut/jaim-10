<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center w-full p-2 text-zinc-900 dark:text-zinc-100 bg-zinc-100 dark:bg-zinc-700 rounded-lg transition-colors duration-150 ease-in-out'
        : 'flex items-center w-full p-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 rounded-lg transition-colors duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>