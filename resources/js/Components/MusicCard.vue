<script setup>
import { useAudioStore } from '@/Stores/audio';
import { storeToRefs } from 'pinia';
import { Button } from 'primevue';
import { router, Link } from '@inertiajs/vue3';
import AddToPlaylistModal from './music-card-partials/AddToPlaylistModal.vue';
import { useWindowSize } from '@vueuse/core';
import { computed } from 'vue';

defineProps({
    musics: {
        type: Object,
        required: true
    }
})

const { width } = useWindowSize();
const isLargeScreen = computed(() => width.value >= 1024);

const audioStore = useAudioStore()
const { currentSong } = storeToRefs(useAudioStore());

const playSong = (music) => {
    audioStore.playSong(music)
}
</script>

<template>
    <div 
        v-for="(music, index) in musics" 
        :key="index"
        class="group px-2 sm:px-4 py-2 sm:py-3 border-b last:border-b-0 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all duration-300"
    >
        <div class="flex items-center gap-3 sm:gap-4">
            <!-- Album Cover with Play Button Overlay -->
            <div class="relative">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-zinc-200 dark:bg-zinc-700 rounded-lg flex-shrink-0 overflow-hidden group">
                    <div v-if="!music?.album?.photo_path" class="w-full h-full flex items-center justify-center">
                        <i class="ri-music-2-line text-xl text-zinc-400 dark:text-zinc-500"></i>
                    </div>
                    <img 
                        v-else 
                        :src="`/storage/${music?.album?.photo_path}`" 
                        :alt="`${music.album.name} Cover`" 
                        class="w-full h-full object-cover"
                    />
                    
                    <!-- Play Button Overlay -->
                    <button 
                        @click="playSong(music)"
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center"
                    >
                        <i 
                            :class="[
                                currentSong && currentSong.id === music.id 
                                    ? 'ri-volume-up-line' 
                                    : 'ri-play-fill',
                                'text-2xl text-white'
                            ]"
                        ></i>
                    </button>
                </div>
            </div>

            <!-- Song info and actions -->
            <div class="flex flex-1 justify-between items-center gap-2">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-neutral-800 dark:text-neutral-100 line-clamp-1">
                        {{ music.title }}
                        <span v-if="currentSong && currentSong.id === music.id" 
                              class="ml-2 text-xs text-green-500 font-normal">
                            Playing
                        </span>
                    </h3>
                    <div class="flex items-center gap-x-2 sm:gap-x-4 text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">
                        <span class="line-clamp-1">{{ music.creator?.first_name }} {{ music.creator?.last_name }}</span>
                        <span class="hidden sm:inline">•</span>
                        <span class="hidden sm:inline line-clamp-1">{{ music.album?.name }}</span>
                        <span class="hidden sm:inline">•</span>
                        <span class="line-clamp-1">{{ music.generated_by }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <AddToPlaylistModal 
                        v-if="$page.props.auth.user?.playlists && !route().current('playlist.*')" 
                        :musicId="music.id" 
                    />
                    <Button
                        v-if="isLargeScreen"
                        severity="secondary"
                        icon="ri-external-link-line"
                        text
                        as="a"
                        :href="`/storage/${music.file_path}`"
                        target="_blank" 
                        rel="noopener noreferrer"
                    >
                        Open
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>