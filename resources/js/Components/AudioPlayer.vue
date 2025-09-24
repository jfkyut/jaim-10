<script setup>
import { ref, onMounted, computed, inject, watch, nextTick } from 'vue'
import { useAudioStore } from '@/Stores/audio'
import { storeToRefs } from 'pinia'

const currentGlobalAudio = inject('currentAudio', ref(null))

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
    }
})

const audioStore = useAudioStore()
const { isPlaying: storeIsPlaying } = storeToRefs(audioStore)

const audio = ref(null)
const currentTime = ref(0)
const duration = ref(0)
const volume = ref(1)

const formattedTime = computed(() => {
    return (time) => {
        const minutes = Math.floor(time / 60)
        const seconds = Math.floor(time % 60)
        return `${minutes}:${seconds.toString().padStart(2, '0')}`
    }
})

const progress = computed(() => {
    return (currentTime.value / duration.value) * 100
})

const togglePlay = () => {
    if (audio.value.paused) {
        if (currentGlobalAudio.value && currentGlobalAudio.value !== audio.value) {
            currentGlobalAudio.value.pause()
            currentGlobalAudio.value.currentTime = 0
        }
        currentGlobalAudio.value = audio.value
        audio.value.play().catch(error => {
            console.error('Error playing audio:', error)
            audioStore.setIsPlaying(false)
        })
        audioStore.setIsPlaying(true)
    } else {
        audio.value.pause()
        audioStore.setIsPlaying(false)
    }
}

const updateVolume = (event) => {
    audio.value.volume = event.target.value
    volume.value = event.target.value
}

onMounted(() => {
    audio.value.addEventListener('timeupdate', () => {
        currentTime.value = audio.value.currentTime
    })

    audio.value.addEventListener('loadedmetadata', () => {
        duration.value = audio.value.duration
        if (props.autoplay) {
            togglePlay()
        }
    })

    audio.value.addEventListener('ended', () => {
        audioStore.setIsPlaying(false)
        if (currentGlobalAudio.value === audio.value) {
            currentGlobalAudio.value = null
        }
    })

    audio.value.addEventListener('pause', () => {
        audioStore.setIsPlaying(false)
    })

    audio.value.addEventListener('play', () => {
        audioStore.setIsPlaying(true)
    })
})

watch(currentGlobalAudio, (newAudio) => {
    if (newAudio !== audio.value && storeIsPlaying.value) {
        audioStore.setIsPlaying(false)
    }
}, { flush: 'post' })
</script>

<template>
    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow">
        <audio ref="audio" :src="src" preload="metadata" class="hidden" />
        
        <div class="flex flex-col space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">{{ title }}</h3>
                <div class="flex items-center space-x-2">
                    <input
                        type="range"
                        min="0"
                        max="1"
                        step="0.01"
                        :value="volume"
                        @input="updateVolume"
                        class="w-20 accent-green-600"
                    />
                    <i class="ri-volume-up-line text-zinc-600 dark:text-zinc-400"></i>
                </div>
            </div>

            <div class="relative w-full h-1 bg-red-200 dark:bg-zinc-700 rounded cursor-pointer">
                <div class="absolute h-full bg-green-600 rounded" :style="{ width: `${progress}%` }"></div>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ formattedTime(currentTime) }}</span>
                
                <button
                    @click="togglePlay"
                    class="p-2 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-700"
                >
                    <i v-if="!storeIsPlaying" class="ri-play-circle-fill text-4xl text-green-600"></i>
                    <i v-else class="ri-pause-circle-fill text-4xl text-green-600"></i>
                </button>
                
                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ formattedTime(duration) }}</span>
            </div>
        </div>
    </div>
</template>