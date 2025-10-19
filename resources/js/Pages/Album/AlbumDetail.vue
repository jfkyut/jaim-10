<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MusicCard from '@/Components/MusicCard.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, toRefs } from 'vue';
import { storeToRefs } from 'pinia';
import { useAudioStore } from '@/Stores/audio';

const props = defineProps({ 
    album: {
        type: Object,
        required: true
    }
});

const { album } = toRefs(props);

const { queue } = storeToRefs(useAudioStore());

onMounted(() => {
    setTimeout(() => {
        queue.value = album.value.musics || [];
    }, 500);
})

</script>

<template>
    <Head title="Album Detail" />

    <AuthenticatedLayout>
        <template #header>
            Album Detail
        </template>

        <div class="p-3 sm:p-6 space-y-4 sm:space-y-8">
            <!-- Album Information Card -->
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <!-- Album Cover -->
                    <div class="w-full md:w-1/3 p-3 sm:p-6">
                        <img 
                            v-if="album.photo_path" 
                            :src="`/storage/${album.photo_path}`" 
                            :alt="album.name" 
                            class="w-full h-[200px] sm:h-[300px] object-cover rounded-lg shadow"
                        />
                        <div 
                            v-else 
                            class="w-full h-[200px] sm:h-[300px] bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center rounded-lg"
                        >
                            <span class="text-sm sm:text-base text-neutral-500 dark:text-neutral-400">No Photo</span>
                        </div>
                    </div>

                    <!-- Album Details -->
                    <div class="w-full md:w-2/3 p-3 sm:p-6">
                        <div class="space-y-3 sm:space-y-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-neutral-100">
                                {{ album.name }}
                            </h2>

                            <div class="flex flex-wrap items-center gap-2 text-sm sm:text-base text-neutral-600 dark:text-neutral-400">
                                <span class="font-semibold">Album Creator:</span>
                                <span>{{ album.creator?.first_name }} {{ album.creator?.last_name }}</span>
                            </div>

                            <div class="space-y-1 sm:space-y-2">
                                <h3 class="text-base sm:text-lg font-semibold">Description</h3>
                                <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-400">
                                    {{ album.description || 'No description provided.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <div class="text-sm sm:text-base text-neutral-600 dark:text-neutral-400">
                                    <span class="font-semibold">Total Songs:</span>
                                    <span class="ml-2">{{ album.musics?.length || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Songs Section -->
            <div class="space-y-3 sm:space-y-4">
                <h3 class="text-xl sm:text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                    Songs in this Album
                </h3>

                <!-- Using MusicCard component -->
                <div>
                    <MusicCard :musics="album.musics" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>