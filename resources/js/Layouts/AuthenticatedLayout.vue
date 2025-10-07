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
import UploadModal from '../Pages/Creation/my-albums-partials/edit-album-partials/UploadModal.vue';
import { useAuthLayoutStore } from '@/Stores/authlayout';
import AudioPlayer from '@/Components/AudioPlayer.vue';
import { useAudioStore } from '@/Stores/audio';
import { storeToRefs } from 'pinia';
import { ref, provide, onMounted, onUnmounted } from 'vue';
import CreateAlbumModal from './authlayout-partials/CreateAlbumModal.vue';
import { router } from '@inertiajs/vue3';
import CreatePlaylistModal from './authlayout-partials/CreatePlaylistModal.vue';

const { toggleSidebar } = useAuthLayoutStore();
const { sidebarOpen } = storeToRefs(useAuthLayoutStore());
const audioStore = useAudioStore();
const { currentSong } = storeToRefs(audioStore);

const currentGlobalAudio = ref(null);
provide('currentAudio', currentGlobalAudio);

const isMobile = ref(window.innerWidth < 768);

const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    if (isMobile.value && sidebarOpen.value) {
        toggleSidebar();
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <div class="min-h-screen flex bg-zinc-100 dark:bg-zinc-900">
        <!-- Sidebar -->
        <div :class="[
            'transition-all duration-300 ease-in-out bg-white dark:bg-zinc-800 border-r border-zinc-100 dark:border-zinc-700 h-[100vh] z-[100] overflow-y-auto',
            'fixed md:relative z-30',
            sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 md:w-20',
        ]">
            <!-- Logo -->
            <div class="h-16 flex items-center">
                <!-- <ApplicationLogo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" /> -->
                <header @click="router.get(route('welcome'))" class="mt-4 hover:cursor-pointer ml-3 w-full font-inter text-4xl font-bold text-center tracking-wide text-zinc-800 dark:text-zinc-200">
                    <h1 v-if="sidebarOpen">JAIM</h1>
                    <h1 v-else>J</h1>
                </header>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-8 px-4 overflow-y-auto h-[calc(100vh-4rem)]">

                <NavLink v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'" :href="route('dashboard')" :active="route().current('dashboard')" class="mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="sidebarOpen" class="ml-2">Dashboard</span>
                </NavLink>

                <NavLink v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'" :href="route('creator.index')" :active="route().current('creator.*')" class="mb-2 flex items-center">
                    <i class="ri-user-6-line"></i>
                    <span class="ms-2" v-if="sidebarOpen">Creators</span>
                </NavLink>

                <NavLink :href="route('music.index')" :active="route().current('music.*')" class="mb-2 flex items-center">
                    <i class="ri-search-line"></i>
                    <span v-if="sidebarOpen" class="ml-2">Explore Music</span>
                </NavLink>

                <!-- explore albums -->
                <NavLink :href="route('album.index')" :active="route().current('album.*')" class="mb-2 flex items-center">
                    <i class="ri-album-line"></i>
                    <span v-if="sidebarOpen" class="ml-2">Explore Albums</span>
                </NavLink>

                <!-- creators and listeners -->
                <NavLink v-if="$page.props.auth.user" :href="route('people.index')" :active="route().current('people.*')" class="mb-2 flex items-center">
                    <i class="ri-group-line"></i>
                    <span class="ms-2" v-if="sidebarOpen">Discover People</span>
                </NavLink>

                <!-- library -->
                <NavDropdown
                    v-if="$page.props.auth.user" 
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
                    <NavDropdown title="Playlist" :active="route().current('test.*')">
                        <template #icon>
                            <i class="ri-play-list-line"></i>
                        </template>
                        <CreatePlaylistModal />
                        <div v-if="$page.props.auth.user.playlists.length === 0" class="text-center text-neutral-600 dark:text-neutral-400 p-2">
                            No playlists found.
                        </div>
                        <NavLink 
                            v-for="playlist in $page.props.auth.user.playlists" 
                            :key="playlist.id"
                            :href="route('playlist.show', playlist.id)"
                            :active="route().current('playlist.show') && route().params.id == playlist.id"
                        >
                            <i class="ri-playlist-fill"></i>
                            <span class="ms-2">{{ playlist.name }}</span>
                        </NavLink>
                    </NavDropdown>
                    
                </NavDropdown>
                <NavDropdown
                    v-if="$page.props.auth.user"
                    title="My Creations"
                    :active="route().current('creation.*')"
                >
                    <template #icon>
                        <i class="ri-folder-2-line"></i>
                    </template>
                    <CreateAlbumModal />
                    <NavLink :href="route('creation.myAlbums')" :active="route().current('creation.myAlbums')">
                        <i class="ri-album-line"></i>
                        <span class="ms-2">My Albums</span>
                    </NavLink>
                </NavDropdown>
                
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full">
            <!-- Top Navigation -->
            <div class="bg-white dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-700 h-16 flex items-center justify-between px-4 sticky top-0 z-20">
                <!-- Sidebar Toggle Button -->
                <button 
                    @click="toggleSidebar" 
                    class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                >
                    <i class="ri-menu-4-line text-[1.5rem]"></i>
                </button>

                <h2 class="font-semibold text-zinc-800 dark:text-zinc-200 leading-tight uppercase">
                    <slot name="header" />
                </h2>

                <!-- User Dropdown -->
                <Dropdown v-if="$page.props.auth.user">
                    <template #trigger>
                        <Button severity="secondary" type="button" class="truncate max-w-[200px]">
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
                <div v-else>
                    <Button 
                        severity="secondary" 
                        label="Log In" 
                        @click="router.get(route('login'))"
                    />
                    <Button 
                        severity="primary" 
                        label="Register" 
                        class="ms-2"
                        @click="router.get(route('register'))"
                    />
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-100 dark:bg-zinc-900 p-4 md:p-6 relative">
                <slot />

                <!-- Audio Player -->
                <div v-if="currentSong" class="absolute bottom-0 left-0 right-0 z-40 px-2 md:px-4 pb-2 md:pb-4">
                    <div class="max-w-4xl mx-auto">
                        <AudioPlayer 
                            :src="`/storage/${currentSong.file_path}`"
                            :title="currentSong.title"
                            :autoplay="true"
                            :creator="currentSong.creator"
                            :album_cover="currentSong?.album?.photo_path"
                        />
                    </div>
                    {{ console.log(currentSong) }}
                </div>
            </main>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div 
            v-if="sidebarOpen && isMobile" 
            class="fixed inset-0 bg-black bg-opacity-50 z-20"
            @click="toggleSidebar"
        ></div>
    </div>
</template>