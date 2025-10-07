<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useLocalStorage } from '@vueuse/core';

const props = defineProps({
    profile: {
        type: Object,
        required: true
    }
});

// Persistent state using localStorage
const storageKey = computed(() => `user_${props.profile.id}_stats`);
const persistedStats = useLocalStorage(storageKey.value, {
    isFollowing: props.profile.is_following,
    followersCount: props.profile.followers_count,
    followingCount: props.profile.following_count,
    tracksCount: props.profile.tracks_count
});

const isFollowing = computed({
    get: () => persistedStats.value.isFollowing,
    set: (val) => persistedStats.value.isFollowing = val
});

const followersCount = computed({
    get: () => persistedStats.value.followersCount,
    set: (val) => persistedStats.value.followersCount = val
});

const isLoading = ref(false);

const toggleFollow = async () => {
    if (isLoading.value) return;
    
    try {
        isLoading.value = true;
        const response = await axios.post(route('user.follow', props.profile.id));
        isFollowing.value = response.data.following;
        followersCount.value = response.data.followers_count;
    } catch (error) {
        console.error('Failed to toggle follow:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head :title="`${profile.first_name} ${profile.last_name}'s Profile`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-zinc-100 to-white dark:from-zinc-900 dark:to-zinc-800">
            <!-- Profile Header -->
            <div class="relative">
                <!-- Cover Image -->
                <div class="h-48 w-full bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-400">
                    <div class="absolute inset-0 bg-black/20"></div>
                </div>

                <!-- Profile Info Card -->
                <div class="max-w-5xl mx-auto px-4">
                    <div class="relative -mt-24">
                        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg p-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <img 
                                        :src="$page.props.auth.user_placeholder" 
                                        :alt="`${profile.first_name}'s profile`"
                                        class="w-32 h-32 rounded-xl object-cover ring-2 ring-white dark:ring-zinc-700 shadow"
                                    >
                                </div>

                                <!-- User Info -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                                                {{ profile.first_name }} {{ profile.last_name }}
                                            </h1>
                                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                                {{ profile.email }}
                                            </p>
                                        </div>
                                        <button 
                                            @click="toggleFollow"
                                            :disabled="isLoading"
                                            class="px-6 py-2 rounded-lg font-medium text-sm transition-all duration-200 
                                                focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-zinc-800
                                                disabled:opacity-50"
                                            :class="[
                                                isFollowing 
                                                    ? 'bg-zinc-100 hover:bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-white' 
                                                    : 'bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white'
                                            ]"
                                        >
                                            {{ isLoading ? 'Loading...' : (isFollowing ? 'Following' : 'Follow') }}
                                        </button>
                                    </div>

                                    <!-- Stats Grid -->
                                    <div class="mt-6 grid grid-cols-3 gap-3">
                                        <div class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-xl text-center">
                                            <span class="block text-2xl font-bold text-emerald-500">{{ followersCount }}</span>
                                            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Followers</span>
                                        </div>
                                        <div class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-xl text-center">
                                            <span class="block text-2xl font-bold text-emerald-500">{{ profile.following_count }}</span>
                                            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Following</span>
                                        </div>
                                        <div class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-xl text-center">
                                            <span class="block text-2xl font-bold text-emerald-500">{{ profile.tracks_count }}</span>
                                            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Tracks</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Sections -->
                    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Tracks Section -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm p-4">
                                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white mb-4">Latest Tracks</h2>
                                <div class="space-y-3">
                                    <div v-if="profile?.tracks?.length === 0" 
                                        class="text-center py-8">
                                        <p class="text-zinc-500 dark:text-zinc-400">No tracks published yet</p>
                                    </div>
                                    <div v-for="track in profile.tracks" :key="track.id" 
                                        class="group flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg shadow-sm"></div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-sm text-zinc-900 dark:text-white truncate">{{ track.title }}</h3>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-1">{{ track.description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Albums -->
                            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm p-4">
                                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white mb-4">Albums</h2>
                                <div class="space-y-2">
                                    <div v-if="profile?.albums?.length === 0" 
                                        class="text-center py-6">
                                        <p class="text-zinc-500 dark:text-zinc-400">No albums created yet</p>
                                    </div>
                                    <div v-for="album in profile.albums" :key="album.id"
                                        class="p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <h3 class="font-medium text-sm text-zinc-900 dark:text-white">{{ album.name }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Playlists -->
                            <!-- <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm p-4">
                                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white mb-4">Playlists</h2>
                                <div class="space-y-2">
                                    <div v-if="profile?.playlists?.length === 0" 
                                        class="text-center py-6">
                                        <p class="text-zinc-500 dark:text-zinc-400">No playlists created yet</p>
                                    </div>
                                    <div v-for="playlist in profile.playlists" :key="playlist.id"
                                        class="p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <h3 class="font-medium text-sm text-zinc-900 dark:text-white">{{ playlist.name }}</h3>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>