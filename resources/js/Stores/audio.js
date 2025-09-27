import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioStore = defineStore('audio', () => {
    // state
    const currentSong = ref(null)
    const isPlaying = ref(false)
    const queue = ref([])
    const currentIndex = ref(-1)

    // actions
    function playSong(song, index = null) {
        if (index !== null) {
            currentIndex.value = index
        }
        currentSong.value = song
    }

    function playNext() {
        if (currentIndex.value < queue.value.length - 1) {
            currentIndex.value++
            currentSong.value = queue.value[currentIndex.value]
            return true
        }
        return false
    }

    function playPrevious() {
        if (currentIndex.value > 0) {
            currentIndex.value--
            currentSong.value = queue.value[currentIndex.value]
            return true
        }
        return false
    }

    function setQueue(songs, startIndex = 0) {
        queue.value = songs
        currentIndex.value = startIndex
        if (songs.length > 0) {
            currentSong.value = songs[startIndex]
        }
    }

    function setIsPlaying(status) {
        isPlaying.value = status
    }

    function clearSong() {
        currentSong.value = null
        isPlaying.value = false
        currentIndex.value = -1
    }

    return {
        currentSong,
        isPlaying,
        queue,
        currentIndex,
        playSong,
        playNext,
        playPrevious,
        setQueue,
        setIsPlaying,
        clearSong
    }
})