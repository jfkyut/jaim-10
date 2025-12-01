<script setup>
import { ref, computed, inject, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { useAuthLayoutStore } from '@/Stores/authlayout';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    active: {
        type: Boolean,
        default: false
    }
});

const { toggleSidebar } = useAuthLayoutStore();
const { sidebarOpen } = storeToRefs(useAuthLayoutStore());

// Local accent vars (keep consistent with layout)
const accentText = 'text-emerald-600 dark:text-emerald-400';
const accentActiveBg = 'bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900 dark:to-green-900';
const accentHover = 'hover:text-emerald-600 dark:hover:text-emerald-400';

// Generate a unique key for this dropdown based on title
const storageKey = `navDropdown_${props.title.toLowerCase().replace(/\s+/g, '_')}`;

const isOpen = ref(false);

// Load the initial state from localStorage
onMounted(() => {
    const savedState = sessionStorage.getItem(storageKey);
    if (savedState !== null) {
        isOpen.value = JSON.parse(savedState);
    }
});

// Watch for changes and save to sessionStorage
watch(isOpen, (newValue) => {
    sessionStorage.setItem(storageKey, JSON.stringify(newValue));
});

const toggle = () => {
    isOpen.value = !isOpen.value;

    // If on mobile and opening the dropdown, close the sidebar
    if (!sidebarOpen.value) {
        toggleSidebar();
    }
};

const buttonClasses = computed(() => {
    return [
        'flex items-center w-full p-2 rounded-lg transition-colors duration-150 ease-in-out',
        {
            // active/open state uses accent text + subtle gradient bg
            [accentText + ' ' + accentActiveBg]: props.active || isOpen.value,
            // default state uses neutral text with accent hover
            ['text-zinc-600 dark:text-zinc-400 ' + accentHover + ' hover:bg-zinc-50 dark:hover:bg-zinc-700/50']: !props.active && !isOpen.value
        }
    ];
});
</script>

<template>
    <div class="mb-2">
        <button @click="toggle" :class="buttonClasses">
            <slot name="icon" />
            <span class="ml-2 flex-1 text-left font-medium" :class="{ 'hidden': !sidebarOpen }">
                {{ title }}
            </span>
            <svg 
                :class="[
                    'h-4 w-4 transition-transform duration-200',
                    { 'rotate-180': isOpen, 'hidden': !sidebarOpen }
                ]"
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <div 
            v-show="isOpen && sidebarOpen"
            class="mt-1 space-y-1"
        >
            <div class="pl-4 pt-2 border-l border-emerald-100 dark:border-emerald-800 space-y-2 overflow-y-auto max-h-[500px]">
                <slot />
            </div>
        </div>
    </div>
</template>