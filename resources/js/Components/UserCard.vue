<script setup>

import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const isFollowing = ref(props.user.is_following ?? false);
const followersCount = ref(props.user.followers?.length || 0);
const isLoading = ref(false);

const toggleFollow = async () => {
    if (isLoading.value) return;
    
    try {
        isLoading.value = true;
        const response = await axios.post(route('user.follow', props.user.id));
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
    <div class="border dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <div class="p-6">
            <!-- Profile Picture with placeholder image -->
            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden mb-6 ring-4 ring-white dark:ring-zinc-800 shadow-lg">
                <img 
                    :src="$page.props.auth.user_placeholder" 
                    alt="Profile picture"
                    class="w-full h-full object-cover"
                >
            </div>

            <!-- User Info with improved typography -->
            <div class="text-center space-y-2">
                <h3 class="text-xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ user.first_name }} {{ user.last_name }}
                </h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">
                    {{ user.email }}
                </p>
            </div>

            <!-- Follow Button -->
            <div class="mt-4 text-center">
                <button 
                    @click="toggleFollow"
                    :disabled="isLoading"
                    :class="[
                        'px-6 py-2 rounded-full font-medium text-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-zinc-800',
                        isFollowing 
                            ? 'bg-zinc-200 hover:bg-zinc-300 text-zinc-800 focus:ring-zinc-500' 
                            : 'bg-emerald-500 hover:bg-emerald-600 text-white focus:ring-emerald-500'
                    ]"
                >
                    {{ isLoading ? 'Loading...' : (isFollowing ? 'Following' : 'Follow') }}
                </button>
            </div>

            <!-- Stats with improved visual separation -->
            <div class="flex justify-center gap-8 mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-700">
                <div class="text-center group cursor-pointer">
                    <span class="block text-lg font-bold text-zinc-800 dark:text-zinc-100 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                        {{ followersCount }}
                    </span>
                    <span class="text-xs uppercase tracking-wide font-medium text-zinc-500 dark:text-zinc-400">Followers</span>
                </div>
                <div class="text-center group cursor-pointer">
                    <span class="block text-lg font-bold text-zinc-800 dark:text-zinc-100 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                        {{ user.following?.length || 0 }}
                    </span>
                    <span class="text-xs uppercase tracking-wide font-medium text-zinc-500 dark:text-zinc-400">Following</span>
                </div>
                <div class="text-center group cursor-pointer">
                    <span class="block text-lg font-bold text-zinc-800 dark:text-zinc-100 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                        {{ user.tracks?.length || 0 }}
                    </span>
                    <span class="text-xs uppercase tracking-wide font-medium text-zinc-500 dark:text-zinc-400">Tracks</span>
                </div>
            </div>
        </div>
    </div>
</template>