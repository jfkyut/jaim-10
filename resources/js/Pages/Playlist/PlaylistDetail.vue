<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import MusicCard from '@/Components/MusicCard.vue';
import Container from '@/Components/containers/Container.vue';
import { Button } from 'primevue';
import { useAudioStore } from '@/Stores/audio';

defineProps({ 
    playlist: Object 
});

const audioStore = useAudioStore();

const handlePlayPlaylist = (playlist) => {
    if (playlist.musics && playlist.musics.length > 0) {
        audioStore.setQueue(playlist.musics, 0); // Set queue starting from first song
    }
};

</script>

<template>
    <Head :title="playlist?.name" />

    <AuthenticatedLayout>
        <template #header>
            {{ playlist?.name }}
        </template>

        <Container>
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="flex gap-2 items-center">
                        <Button @click="router.get(route('music.index'))">
                            <i class="ri-add-line"></i>
                            <span>Add Music</span>
                        </Button>
                        <Button 
                            severity="secondary"
                            v-if="playlist && playlist.musics && playlist.musics.length > 0"
                            @click="handlePlayPlaylist(playlist)"
                            class="bg-green-600 hover:bg-green-700 text-white"
                        >
                            <i class="ri-play-fill"></i>
                            <span>Play All</span>
                        </Button>
                        
                    </div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ playlist?.musics ? playlist.musics.length : 0 }} songs
                    </span>
                </div>
            </template>
            <template #body>
                <div v-if="!playlist || !playlist.musics || playlist.musics.length === 0" class="text-center text-neutral-600 dark:text-neutral-400">
                    This playlist has no music yet.
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <MusicCard :musics="playlist.musics" />
                </div>
            </template>
        </Container>    
    </AuthenticatedLayout>
</template>