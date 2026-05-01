import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioStore = defineStore('audio', () => {
    // state
    const currentSong = ref(null)
    const isPlaying = ref(false)
    const queue = ref([])
    const currentIndex = ref(-1)

    const isApproved = (song) => song && song.status === 'approved'

    function playSong(song) {
        if (!isApproved(song)) {
            return false
        }

        const queueIndex = queue.value.findIndex((item) => item.id === song.id)
        if (queueIndex !== -1) {
            currentIndex.value = queueIndex
        }

        currentSong.value = song
        return true
    }

    function playNext() {
        let nextIndex = currentIndex.value + 1
        while (nextIndex < queue.value.length) {
            if (isApproved(queue.value[nextIndex])) {
                currentIndex.value = nextIndex
                currentSong.value = queue.value[currentIndex.value]
                return true
            }
            nextIndex++
        }

        return false
    }

    function playPrevious() {
        let prevIndex = currentIndex.value - 1
        while (prevIndex >= 0) {
            if (isApproved(queue.value[prevIndex])) {
                currentIndex.value = prevIndex
                currentSong.value = queue.value[currentIndex.value]
                return true
            }
            prevIndex--
        }

        return false
    }

    function setQueue(songs, startIndex = 0) {
        const approvedSongs = songs.filter((song) => isApproved(song))
        queue.value = approvedSongs

        if (approvedSongs.length === 0) {
            currentIndex.value = -1
            currentSong.value = null
            return
        }

        let selectedSong = null
        if (startIndex >= 0 && startIndex < songs.length) {
            selectedSong = songs.slice(startIndex).find((song) => isApproved(song))
        }

        if (!selectedSong) {
            selectedSong = approvedSongs[0]
        }

        currentIndex.value = approvedSongs.findIndex((song) => song.id === selectedSong.id)
        currentSong.value = selectedSong
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