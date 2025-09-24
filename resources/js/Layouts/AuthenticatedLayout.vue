<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import NavDropdown from '@/Components/NavDropdown.vue';
import NavButton from '@/Components/NavButton.vue';
import { Button } from 'primevue';
import UploadModal from './authlayout-partials/UploadModal.vue';
import { useAuthLayoutStore } from '@/Stores/authlayout';
import AudioPlayer from '@/Components/AudioPlayer.vue';
import { useAudioStore } from '@/Stores/audio';
import { storeToRefs } from 'pinia';
import { ref, provide } from 'vue';

const { toggleSidebar } = useAuthLayoutStore();

const { sidebarOpen } = storeToRefs(useAuthLayoutStore());

const audioStore = useAudioStore();
const { currentSong } = storeToRefs(audioStore);

const currentGlobalAudio = ref(null);
provide('currentAudio', currentGlobalAudio);

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
                <Link :href="route('welcome')" class="flex items-center">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" />
                    <header v-if="sidebarOpen" class="ml-3 font-inter text-base font-medium tracking-wide text-zinc-800 dark:text-zinc-200">
                        <h1>
                            JAIM
                        </h1>
                    </header>
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
                <NavLink :href="route('music.index')" :active="route().current('music.*')" class="mb-2 flex items-center">
                    <i class="ri-search-line"></i>
                    <span v-if="sidebarOpen" class="ml-2">Explore Music</span>
                </NavLink>
                <NavDropdown
                    title="Library"
                    :active="route().current('test.*')"
                >
                    <template #icon>
                        <i class="ri-music-2-line"></i>
                    </template>
                    <NavLink href="">
                        <i class="ri-heart-line"></i>
                        <span class="ml-2">Favorites</span>
                    </NavLink>
                    <NavLink href="">
                        <i class="ri-history-line"></i>
                        <span class="ms-2">Recents</span>
                    </NavLink>
                    <NavDropdown
                        title="Playlist"
                        :active="route().current('test.*')"
                    >
                        <template #icon>
                            <i class="ri-play-list-line"></i>
                        </template>
                        <NavButton type="button" class="mb-2 flex items-center">
                            <i class="ri-add-line"></i>
                            <span v-if="sidebarOpen" class="ml-2">Create Playlist</span>
                        </NavButton>
                        <NavLink href="">playlist 1</NavLink>
                        <NavLink href="">test</NavLink>
                        <NavLink href="">test</NavLink>
                        <NavLink href="">test</NavLink>
                    </NavDropdown>
                </NavDropdown>
                <UploadModal />
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

                <slot name="header" />

                <!-- User Dropdown -->
                <Dropdown>
                    <template #trigger>
                        <Button severity="secondary" type="button">
                            {{ $page.props.auth.user.email }}
                        </Button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink
                            href="#"
                            @click.prevent="$inertia.post(route('logout'))"
                        >
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-100 dark:bg-zinc-900 p-6 relative">
                <slot />

                <div v-if="currentSong" class="absolute bottom-2 left-2 right-2 max-w-4xl mx-auto">
                    <AudioPlayer 
                        :src="`/storage/${currentSong.file_path}`"
                        :title="currentSong.title"
                        :autoplay="true"
                    />
                </div>
            </main>
            
        </div>
    </div>
</template>
