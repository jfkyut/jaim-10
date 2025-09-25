<script setup>
import { ref, onMounted, useTemplateRef, toRefs, computed } from 'vue'
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
    }
})

const { src, title, autoplay } = toRefs(props);

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



onMounted(() => {
    volume.value = 0.5;
    currentTime.value = 60;
})


</script>

<template>
    <div class="audio-player bg-white dark:bg-zinc-800 shadow-lg rounded-lg p-4">
        <!-- Track Info -->
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-zinc-200 dark:bg-zinc-700 rounded-lg flex-shrink-0">
                <!-- Album Art Placeholder -->
                <div class="w-full h-full flex items-center justify-center">
                    <i class="ri-music-2-line text-xl text-zinc-400 dark:text-zinc-500"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="font-semibold text-zinc-800 dark:text-zinc-100">{{ title }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ creator?.first_name }} {{ creator?.first_name }}</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-4">
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
            <!-- prev -->
            <button @click="currentTime = currentTime - 5" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-skip-back-fill text-2xl"></i>
            </button>

            <!-- play -->
            <button @click="togglePlay" class="text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white focus:outline-none">
                <i v-if="playing" class="ri-pause-fill text-[2rem] rounded-full"></i>
                <i v-else class="ri-play-fill text-[2rem] rounded-full"></i>
            </button>

            <!-- next -->
            <button @click="currentTime = currentTime + 5" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 focus:outline-none">
                <i class="ri-skip-forward-fill text-2xl"></i>
            </button>
        </div>

        <!-- Volume Control -->
        <div class="flex items-center mt-4">
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

        <audio ref="audio" :autoplay="autoplay" class="sr-only"></audio>
    </div>
</template>