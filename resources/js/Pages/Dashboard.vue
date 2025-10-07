<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    total_users: Number,
    total_tracks: Number,
    total_albums: Number,
    recent_users: Array,
    recent_tracks: Array,
    top_creators: Array,
    monthly_tracks: Array
});

console.log('Dashboard Props:', props);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200">
                    Admin Dashboard
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="text-lg font-semibold text-zinc-600 dark:text-zinc-300">Total Users</h3>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ total_users }}</p>
                    </div>
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="text-lg font-semibold text-zinc-600 dark:text-zinc-300">Total Tracks</h3>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ total_tracks }}</p>
                    </div>
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="text-lg font-semibold text-zinc-600 dark:text-zinc-300">Total Albums</h3>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ total_albums }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Recent Users -->
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-800 dark:text-zinc-200">Recent Users</h3>
                        <div class="space-y-4">
                            <div v-for="user in recent_users" :key="user.id" 
                                 class="flex items-center justify-between p-3 transition-colors duration-200 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">
                                        {{ user.first_name }} {{ user.last_name }}
                                    </p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ user.email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Tracks -->
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-800 dark:text-zinc-200">Recent Tracks</h3>
                        <div class="space-y-4">
                            <div v-for="track in recent_tracks" :key="track.id" 
                                 class="flex items-center justify-between p-3 transition-colors duration-200 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ track.title }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        by {{ track.creator.first_name }} {{ track.creator.last_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Creators -->
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-800 dark:text-zinc-200">Top Creators</h3>
                        <div class="space-y-4">
                            <div v-for="creator in top_creators" :key="creator.id" 
                                 class="flex items-center justify-between p-3 transition-colors duration-200 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">
                                        {{ creator.first_name }} {{ creator.last_name }}
                                    </p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ creator.tracks_count }} tracks
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Tracks Chart -->
                    <div class="p-6 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-800 dark:text-zinc-200">Monthly Track Creation</h3>
                        <div class="space-y-4">
                            <div v-for="stat in monthly_tracks" :key="stat.month" 
                                 class="flex items-center justify-between p-3 transition-colors duration-200 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <span class="text-zinc-900 dark:text-white">{{ stat.month }}</span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ stat.count }} tracks</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>