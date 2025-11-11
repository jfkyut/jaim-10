<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useAudioStore } from '@/Stores/audio';
import AudioPlayer from '@/Components/AudioPlayer.vue';
import { useFollow } from '@/Composables/follow';

const props = defineProps({
    profile: {
        type: Object,
        required: true
    }
});

const isFollowing = ref(props.profile.is_following ?? false);
const followersCount = ref(props.profile.followers_count || 0);
const isLoading = ref(false);

const { follow, unfollow } = useFollow(props.profile);

const toggleFollow = async () => {
    if (isLoading.value) return;
    
    if (isFollowing.value) {

        isLoading.value = true;

        const data = await unfollow();

        isFollowing.value = data.is_following;
        followersCount.value = data.followers_count;

        isLoading.value = false;
    } else {
        isLoading.value = true;

        const data = await follow();

        isFollowing.value = data.is_following;
        followersCount.value = data.followers_count;

        isLoading.value = false;
    }
};

const audioStore = useAudioStore();

// Function to play a track and set queue
const playTrack = (track, index) => {
    // Set the queue with all tracks and start from the selected track
    audioStore.setQueue(props.profile.tracks, index);
    audioStore.setIsPlaying(true);
};

// Function to check if a track is currently playing
const isTrackPlaying = (track) => {
    return audioStore.currentSong?.id === track.id && audioStore.isPlaying;
};
</script>

<template>
    <Head :title="`${profile.first_name}'s Profile`" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <!-- Avatar -->
                        <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-white dark:ring-zinc-700">
                            <img 
                                :src="$page.props.auth.user_placeholder" 
                                :alt="`${profile.first_name}'s avatar`"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        
                        <div class="flex-1 text-center sm:text-left">
                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                {{ profile.first_name }} {{ profile.last_name }}
                            </h1>
                            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ profile.email }}</p>
                            
                            <!-- Stats -->
                            <div class="flex justify-center sm:justify-start gap-6 mt-4">
                                <div class="text-center">
                                    <span class="block text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ followersCount }}</span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Followers</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ profile.following_count }}</span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Following</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ profile.tracks_count }}</span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Tracks</span>
                                </div>
                            </div>
                        </div>

                        <!-- Follow Button -->
                        <div class="mt-4 sm:mt-0" v-if="$page.props.auth.user.id !== profile.id">
                            <button 
                                @click="toggleFollow"
                                :disabled="isLoading"
                                :class="[
                                    'px-6 py-2 rounded-full font-medium text-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-zinc-800',
                                    isFollowing 
                                        ? 'bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-800 dark:text-zinc-100' 
                                        : 'bg-emerald-500 hover:bg-emerald-600 text-white'
                                ]"
                            >
                                {{ isLoading ? 'Loading...' : (isFollowing ? 'Following' : 'Follow') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Tracks -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">Tracks</h2>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto">
                        <div 
                            v-for="(track, index) in profile.tracks" 
                            :key="track.id" 
                            class="flex items-center justify-between p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors"
                        >
                            <div class="flex items-center flex-1">
                                <!-- Play Button -->
                                <button 
                                    @click="playTrack(track, index)"
                                    class="mr-3 w-8 h-8 flex items-center justify-center rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-600"
                                >
                                    <i 
                                        :class="[
                                            isTrackPlaying(track) ? 'ri-pause-fill' : 'ri-play-fill',
                                            'text-xl text-zinc-700 dark:text-zinc-300'
                                        ]"
                                    ></i>
                                </button>
                                
                                <div>
                                    <h3 class="font-medium text-zinc-900 dark:text-zinc-100">{{ track.title }}</h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ track.generated_at }}</p>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                </div>

                <!-- Albums -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">Albums</h2>
                    
                    <div v-if="profile.albums?.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-h-[400px] overflow-y-auto">
                        <div 
                            v-for="album in profile.albums" 
                            :key="album.id" 
                            class="group relative aspect-square bg-zinc-50 dark:bg-zinc-700/50 rounded-xl overflow-hidden hover:shadow-md transition-all duration-200 hover:cursor-pointer"
                            @click="router.get(route('album.index', { id: album.id }))"
                        >
                            <!-- Album Cover -->
                            <div class="w-full h-full">
                                <template v-if="album.photo_path">
                                    <img 
                                        :src="`/storage/${album.photo_path}`"
                                        :alt="album.name"
                                        class="w-full h-full object-cover"
                                    >
                                </template>
                                <div v-else class="w-full h-full flex flex-col items-center justify-center bg-zinc-200 dark:bg-zinc-700">
                                    <i class="ri-album-line text-4xl text-zinc-400 dark:text-zinc-500"></i>
                                </div>
                            </div>

                            <!-- Album Info Overlay - Always visible -->
                            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/60 via-black/40 to-transparent">
                                <h3 class="font-medium text-white text-sm truncate">{{ album.name }}</h3>
                                <p class="text-xs text-zinc-200 truncate">
                                    {{ album.musics_count || 0 }} {{ album.musics_count === 1 ? 'track' : 'tracks' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <i class="ri-album-line text-4xl text-zinc-400 dark:text-zinc-500"></i>
                        <p class="mt-2 text-zinc-500 dark:text-zinc-400">No albums yet</p>
                    </div>
                </div>

                <!-- Playlists -->
                <!-- <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">Playlists</h2>
                    <div class="space-y-4">
                        <div v-for="playlist in profile.playlists" :key="playlist.id"
                             class="p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <h3 class="font-medium text-zinc-900 dark:text-zinc-100">{{ playlist.name }}</h3>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>