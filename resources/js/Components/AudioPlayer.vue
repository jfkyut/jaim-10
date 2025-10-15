<script setup>
import { ref, onMounted, useTemplateRef, toRefs, computed, onUnmounted } from 'vue'
import { useAudioStore } from '@/Stores/audio'
import { storeToRefs } from 'pinia'
import { useMediaControls } from '@vueuse/core';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    autoplay: {
        type: Boolean,
        default: false
    },
    creator: {
        type: Object,
        default: null
    },
    album_cover: {
        type: String,
        default: null
    }
})

const { src, title, autoplay } = toRefs(props);

const audioStore = useAudioStore()
const { currentSong, queue } = storeToRefs(audioStore)

const audio = useTemplateRef('audio');

const { 
    playing, 
    currentTime, 
    duration, 
    volume,
    muted,
} = useMediaControls(audio, {
    volumeStep: 0.1,
    playbackRateStep: 0.1,
    seekStep: 5,
    // seekInterval: 200,
    src: src,
    loop: false,
    preload: 'metadata',
});

const formatTime = (time) => {
    if (!time) return '0:00'
    const minutes = Math.floor(time / 60)
    const seconds = Math.floor(time % 60)
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
}

const togglePlay = () => {
    playing.value = !playing.value;
}

// Add event listener for when song ends
const handleSongEnd = () => {
    if (audioStore.playNext()) {
        playing.value = true
    }
}

const handlePrevious = () => {
    if (audioStore.playPrevious()) {
        playing.value = true
    }
}

const handleNext = () => {
    if (audioStore.playNext()) {
        playing.value = true
    }
}

onMounted(() => {
    volume.value = 0.5;
    currentTime.value = 60;
})

onUnmounted(() => {
    if (audio.value) {
        audio.value.removeEventListener('ended', handleSongEnd)
    }
})

</script>

<template>
    <div class="bg-white dark:bg-zinc-800 shadow-lg rounded-lg p-4 border dark:border-zinc-700 w-full max-w-2xl mx-auto">
        <!-- Track Info -->
        <div class="flex justify-between items-center">
            <div class="flex items-center mb-2">
                <div class="w-12 h-12 bg-zinc-200 dark:bg-zinc-700 rounded-lg flex-shrink-0">
                    <!-- Album Art Placeholder -->
                    <div v-if="!album_cover" class="w-full h-full flex items-center justify-center">
                        <i class="ri-music-2-line text-xl text-zinc-400 dark:text-zinc-500"></i>
                    </div>
                    <img v-else :src="`/storage/${album_cover}`" alt="Album Cover" class="w-full h-full object-cover rounded-lg" />
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-zinc-800 dark:text-zinc-100">{{ title }}</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ creator?.first_name }} {{ creator?.first_name }}</p>
                </div>
            </div>

            <!-- Volume Control -->
            <div class="flex items-center">
                <i v-if="volume === 0" class="ri-volume-mute-line text-xl text-zinc-600 dark:text-zinc-400"></i>
                <i v-else class="ri-volume-up-line text-xl text-zinc-600 dark:text-zinc-400"></i>
                <div class="flex-1 mx-3">
                    <input
                        type="range"
                        min="0"
                        max="1"
                        step="0.01"
                        v-model="volume"
                        class="w-20 accent-green-600"
                    />
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-2">
            <div class="relative">
                <input
                    type="range"
                    min="0"
                    :max="duration"
                    step="0.01"
                    v-model="currentTime"
                    class="w-full h-1 bg-zinc-200 dark:bg-zinc-700 rounded-full cursor-pointer accent-green-600"
                />
            </div>
            <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                <span>{{ formatTime(currentTime) }}</span>
                <span>{{ formatTime(duration) }}</span>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex items-center justify-center space-x-6">
            <!-- previous song -->
            <button @click="handlePrevious" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-skip-back-line text-2xl"></i>
            </button>

            <!-- backward 5s -->
            <button @click="currentTime = currentTime - 5" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-rewind-fill text-xl"></i>
            </button>

            <!-- play -->
            <button @click="togglePlay" class="text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white focus:outline-none">
                <i v-if="playing" class="ri-pause-fill text-[2rem] rounded-full"></i>
                <i v-else class="ri-play-fill text-[2rem] rounded-full"></i>
            </button>

            <!-- forward 5s -->
            <button @click="currentTime = currentTime + 5" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-speed-fill text-xl"></i>
            </button>

            <!-- next song -->
            <button @click="handleNext" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-skip-forward-line text-2xl"></i>
            </button>
        </div>

        <audio ref="audio" :autoplay="autoplay" class="sr-only"></audio>
    </div>
</template>