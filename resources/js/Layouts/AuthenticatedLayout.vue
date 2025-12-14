<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/dropdowns/DropdownLink.vue';
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
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import DropdownButton from '@/Components/dropdowns/DropdownButton.vue';
import CreditModal from './authlayout-partials/CreditModal.vue';

const { toggleSidebar, closeSidebar } = useAuthLayoutStore();
const { sidebarOpen } = storeToRefs(useAuthLayoutStore());
const audioStore = useAudioStore();
const { currentSong } = storeToRefs(audioStore);

const currentGlobalAudio = ref(null);
provide('currentAudio', currentGlobalAudio);

const isMobile = ref(window.innerWidth < 768);

// Tailwind accent classes (reusable) — no custom CSS, just class strings
const accentText = 'text-emerald-600 dark:text-emerald-400';
const accentGradient = 'bg-gradient-to-r from-emerald-500 via-green-500 to-blue-500';
const accentActiveBg = 'rounded-md bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900 dark:to-green-900';
const accentHover = 'hover:text-emerald-600 dark:hover:text-emerald-400';
// Sidebar-specific softer gradient and subtle ring/shadow when open
const sidebarSoftGradient = 'bg-gradient-to-b from-emerald-50 via-white to-white dark:from-zinc-900 dark:via-emerald-900 dark:to-zinc-800';
const sidebarOpenExtras = 'shadow-lg ring-1 ring-emerald-100 dark:ring-emerald-900';

// Helper to return classes for nav items when route active
const activeNavClass = (isActive) => isActive ? `${accentText} font-semibold ${accentActiveBg}` : '';

const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    if (isMobile.value && sidebarOpen.value) {
        // closing explicitly avoids unexpected toggles
        closeSidebar();
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
            'transition-all duration-300 ease-in-out border-r border-zinc-100 dark:border-zinc-700 h-[100vh] z-[100] overflow-y-auto',
            'absolute md:relative z-30',
            // When closed we fully hide the sidebar (no collapsed icon rail)
            sidebarOpen ? `translate-x-0 w-64 ${sidebarSoftGradient} ${sidebarOpenExtras}` : '-translate-x-full w-0 pointer-events-none',
        ]">
            <!-- Logo -->
                <div class="h-16 flex items-center flex-col w-full">
                    <!-- decorative accent stripe -->
                    <div class="w-full">
                        <div class="h-1 w-full rounded-b-md " :class="sidebarOpen ? accentGradient : 'hidden'"></div>
                    </div>
                    <div class="flex-1 w-full flex items-center">
                <!-- <ApplicationLogo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" /> -->
                <header
                    @click="router.get(route('welcome'))"
                    :class="[
                        'mt-4 hover:cursor-pointer w-full font-inter text-4xl font-bold text-center tracking-wide',
                        sidebarOpen ? accentGradient + ' text-white' : 'text-zinc-800 dark:text-zinc-200'
                    ]"
                >
                    <h1 v-if="sidebarOpen">JAM</h1>
                    <h1 v-else>J</h1>
                </header>
                    </div>
                </div>

            <!-- Navigation Links -->
            <nav class="mt-8 px-4 overflow-y-auto h-[calc(100vh-4rem)]">

                <NavLink v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'" :href="route('dashboard')" :active="route().current('dashboard')" :class="['mb-2 flex items-center', activeNavClass(route().current('dashboard')), accentHover]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="sidebarOpen" class="ml-2">Dashboard</span>
                </NavLink>

                <NavDropdown 
                    title="Redeem Requests" 
                    v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'"
                    :active="route().current('redeem.*')"
                >
                    <template #icon>
                        <i class="ri-list-check-3"></i>
                    </template>
                    <NavLink :href="route('redeem.pending')" :active="route().current('redeem.pending')" :class="[ activeNavClass(route().current('redeem.pending')), accentHover ]">
                        Pending
                    </NavLink>
                    <NavLink :href="route('redeem.approved')" :active="route().current('redeem.approved')" :class="[ activeNavClass(route().current('redeem.approved')), accentHover ]">
                        Approved
                    </NavLink>
                    <NavLink :href="route('redeem.rejected')" :active="route().current('redeem.rejected')" :class="[ activeNavClass(route().current('redeem.rejected')), accentHover ]">
                        Rejected
                    </NavLink>
                </NavDropdown>

                <NavDropdown 
                    title="Music Approval" 
                    v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'"
                    :active="route().current('music-approval.*')"
                >
                    <template #icon>
                        <i class="ri-list-check-3"></i>
                    </template>
                    <NavLink :href="route('music-approval.pending')" :active="route().current('music-approval.pending')" :class="[ activeNavClass(route().current('music-approval.pending')), accentHover ]">
                        Pending
                    </NavLink>
                    <NavLink :href="route('music-approval.review')" :active="route().current('music-approval.review')" :class="[ activeNavClass(route().current('music-approval.review')), accentHover ]">
                        Under Review
                    </NavLink>
                    <NavLink :href="route('music-approval.approved')" :active="route().current('music-approval.approved')" :class="[ activeNavClass(route().current('music-approval.approved')), accentHover ]">
                        Approved
                    </NavLink>
                    <NavLink :href="route('music-approval.rejected')" :active="route().current('music-approval.rejected')" :class="[ activeNavClass(route().current('music-approval.rejected')), accentHover ]">
                        Rejected
                    </NavLink>
                </NavDropdown>

                <NavLink v-if="$page.props.auth.user && $page.props.auth.user.role.name === 'admin'" :href="route('creator.index')" :active="route().current('creator.*')" :class="['mb-2 flex items-center', activeNavClass(route().current('creator.*')), accentHover]">
                    <i class="ri-user-6-line"></i>
                    <span class="ms-2" v-if="sidebarOpen">Creators</span>
                </NavLink>

                <NavLink :href="route('music.index')" :active="route().current('music.*')" :class="['mb-2 flex items-center', activeNavClass(route().current('music.*')), accentHover]">
                    <i class="ri-search-line"></i>
                    <span v-if="sidebarOpen" class="ml-2">Explore Music</span>
                </NavLink>

                <!-- explore albums -->
                <NavLink :href="route('album.index')" :active="route().current('album.*')" :class="['mb-2 flex items-center', activeNavClass(route().current('album.*')), accentHover]">
                    <i class="ri-album-line"></i>
                    <span v-if="sidebarOpen" class="ml-2">Explore Albums</span>
                </NavLink>

                <!-- creators and listeners -->
                <NavLink v-if="$page.props.auth.user" :href="route('people.index')" :active="route().current('people.*')" :class="['mb-2 flex items-center', activeNavClass(route().current('people.*')), accentHover]">
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
                    <NavLink :href="route('favorite.index')" :active="route().current('favorite.*')" :class="[ 'flex items-center', activeNavClass(route().current('favorite.*')), accentHover ]">
                        <i class="ri-heart-line"></i>
                        <span class="ml-2">Favorites</span>
                    </NavLink>
                    <!-- <NavLink href="">
                        <i class="ri-history-line"></i>
                        <span class="ms-2">Recents</span>
                    </NavLink> -->
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
                            :active="route().current('playlist') && route().params.id == playlist.id"
                            :class="[ 'px-2 py-1 flex items-center', activeNavClass(route().current('playlist') && route().params.id == playlist.id), accentHover ]"
                        >
                            <i class="ri-playlist-fill"></i>
                            <span class="ms-2">{{ playlist.name }}</span>
                        </NavLink>
                    </NavDropdown>
                    
                </NavDropdown>
                <NavDropdown
                    v-if="$page.props.auth.user.role.name !== 'listener'"
                    title="My Creations"
                    :active="route().current('creation.*')"
                >
                    <template #icon>
                        <i class="ri-folder-2-line"></i>
                    </template>
                    <CreateAlbumModal />
                    <NavLink :href="route('creation.myAlbums')" :active="route().current('creation.myAlbums')" :class="[ 'flex items-center', activeNavClass(route().current('creation.myAlbums')), accentHover ]">
                        <i class="ri-album-line"></i>
                        <span class="ms-2">My Albums</span>
                    </NavLink>
                    <NavLink :href="route('creation.myMusics')" :active="route().current('creation.myMusics')" :class="[ 'flex items-center', activeNavClass(route().current('creation.myMusics')), accentHover ]">
                        <i class="ri-music-2-line"></i>
                        <span class="ms-2">My Musics</span>
                    </NavLink>
                </NavDropdown>

                <NavDropdown 
                    title="Subscription Plans" 
                    v-if="$page.props.auth.user && $page.props.auth.user.role.name !== 'admin'"
                    :active="route().current('subscription.*')"
                >
                    <template #icon>
                        <i class="ri-money-dollar-circle-line"></i>
                    </template>

                    <NavLink v-if="$page.props.auth.user" :href="route('subscription.create')" :active="route().current('subscription.create')" :class="['mb-2 flex items-center', activeNavClass(route().current('subscription.create')), accentHover]">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span class="ms-2" v-if="sidebarOpen">Plan</span>
                    </NavLink>

                    <NavLink v-if="$page.props.auth.user" :href="route('subscription.index')" :active="route().current('subscription.index')" :class="['mb-2 flex items-center', activeNavClass(route().current('subscription.index')), accentHover]">
                        <i class="ri-file-history-line"></i>
                        <span class="ms-2" v-if="sidebarOpen">History</span>
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
                        <DropdownLink :to="route('people.show', $page.props.auth.user.id)">My Profile</DropdownLink>
                        <DropdownLink :to="route('profile.edit')">Edit Profile</DropdownLink>
                        
                        <CreditModal />

                        <DropdownButton>
                            <div class="flex justify-between">
                                <span>Dark</span>
                                <ThemeSwitcher />
                            </div>
                        </DropdownButton>

                        <DropdownLink
                            to="#"
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
            <main class="flex-1 overflow-x-hidden max-h-[92vh] overflow-y-auto bg-zinc-100 dark:bg-zinc-900 p-4 md:p-6 relative">
                <slot />

                <!-- <Ad /> -->

                <!-- Audio Player -->
                <div v-if="currentSong" class="fixed bottom-2 left-0 right-0 z-40">
                    <AudioPlayer 
                        :src="`/storage/${currentSong.file_path}`"
                        :title="currentSong.title"
                        :autoplay="true"
                        :creator="currentSong.creator"
                        :album_cover="currentSong?.album?.photo_path"
                    />
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