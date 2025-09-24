<script setup>
import { provide, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import NavDropdown from '@/Components/NavDropdown.vue';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(true);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

provide('sidebarOpen', sidebarOpen);

</script>

<template>
    <div class="h-screen flex bg-zinc-100 dark:bg-zinc-900">
        <!-- Sidebar -->
        <div :class="[
            'transition-all duration-300 ease-in-out bg-white dark:bg-zinc-800 border-r border-zinc-100 dark:border-zinc-700',
            sidebarOpen ? 'w-64' : 'w-20'
        ]">
            <!-- Logo -->
            <div class="h-16 flex items-center px-4">
                <Link :href="route('dashboard')" class="flex items-center">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" />
                    <span v-if="sidebarOpen" class="ml-3 font-inter text-base font-medium tracking-wide text-zinc-800 dark:text-zinc-200">
                        {{ route().current().split('.').join(' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                    </span>
                </Link>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-8 px-4">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="sidebarOpen" class="ml-2">Dashboard</span>
                </NavLink>
                <NavLink :href="route('welcome')" :active="route().current('welcome')" class="mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="sidebarOpen" class="ml-2">welcome</span>
                </NavLink>
                <NavDropdown
                    title="Test Dropdown"
                    :active="route().current('test.*')"
                >
                    <template #icon>
                        <i class="ri-home-line"></i>
                    </template>
                    <NavLink href="">test</NavLink>
                    <NavLink href="">test</NavLink>
                    <NavLink href="">test</NavLink>
                    <NavLink href="">test</NavLink>

                </NavDropdown>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <div class="bg-white dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-700 h-16 flex items-center justify-between px-4">
                <!-- Sidebar Toggle Button -->
                <button @click="toggleSidebar" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                    <i class="ri-menu-4-line text-[1.5rem]"></i>
                </button>

                <!-- User Dropdown -->
                <div class="flex items-center">
                    here
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-100 dark:bg-zinc-900 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
