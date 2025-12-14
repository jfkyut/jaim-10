<script setup>
import { computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useAuthLayoutStore } from '@/Stores/authlayout';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const { closeSidebar } = useAuthLayoutStore();
const { sidebarOpen } = storeToRefs(useAuthLayoutStore());
const isMobile = ref(window.innerWidth < 768);

const _handleResize = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => window.addEventListener('resize', _handleResize));
onUnmounted(() => window.removeEventListener('resize', _handleResize));

const classes = computed(() =>
    props.active
        ? 'flex items-center w-full p-2 text-zinc-900 dark:text-zinc-100 bg-zinc-100 dark:bg-zinc-700 rounded-lg transition-colors duration-150 ease-in-out'
        : 'flex items-center w-full p-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 rounded-lg transition-colors duration-150 ease-in-out'
);

const handleNavClick = () => {
    if (isMobile.value && sidebarOpen.value) {
        closeSidebar();
    }
};
</script>

<template>
    <Link :href="href" :class="classes" @click="handleNavClick">
        <slot />
    </Link>
</template>