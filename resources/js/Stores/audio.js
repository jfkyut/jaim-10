import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioStore = defineStore('audio', () => {
    // state
    const currentSong = ref(null)
    const isPlaying = ref(false)

    // actions
    function playSong(song) {
        currentSong.value = song
    }

    function setIsPlaying(status) {
        isPlaying.value = status
    }

    function clearSong() {
        currentSong.value = null
        isPlaying.value = false
    }

    return {
        // state
        currentSong,
        isPlaying,
        // actions
        playSong,
        setIsPlaying,
        clearSong
    }
})