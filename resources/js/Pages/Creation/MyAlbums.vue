<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from 'primevue';
import EditAlbumModal from './my-albums-partials/EditAlbumModal.vue';
import UploadModal from '@/Pages/Creation/my-albums-partials/edit-album-partials/UploadModal.vue';
import DeleteAlbumModal from './my-albums-partials/DeleteAlbumModal.vue';
import Container from '@/Components/containers/Container.vue';
import SearchForm from '@/Components/forms/SearchForm.vue';
import { storeToRefs } from 'pinia';
import { useMyAlbumStore } from '@/Stores/myAlbums';
import Paginator from '@/Components/Paginator.vue';

defineProps({ 
    albums: Array 
});

const { params } = storeToRefs(useMyAlbumStore());

</script>

<template>
    <Head title="My Albums" />

    <AuthenticatedLayout>
        <template #header>
            My Albums
        </template>

        <Container>
            <template #header>
                <SearchForm 
                    :url="route(route().current())" 
                    :params="params"
                />
            </template>
            <template #body>
                <div v-if="albums.length === 0" class="text-center text-neutral-600 dark:text-neutral-400">
                    You have not created any albums yet.
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div v-for="album in albums.data" :key="album.id" class="bg-white dark:bg-neutral-800 rounded-lg shadow p-4 border dark:border-neutral-700">
                        
                        <div class="mb-4">
                            <img 
                                v-if="album.photo_path" 
                                :src="`/storage/${album.photo_path}`" 
                                alt="Album Photo" 
                                class="w-full h-48 object-cover rounded"
                            />
                            <div v-else class="w-full h-48 bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center rounded">
                                <span class="text-neutral-500 dark:text-neutral-400">No Photo</span>
                            </div>
                        </div>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-2">{{ album.name }}</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">{{ album.description || 'No description provided.' }}</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Number of Songs: {{ album.musics.length }}</p>
                    
                        <div class="mt-2 flex gap-2 items-center">
                            
                            <EditAlbumModal :album="album" />
                            <DeleteAlbumModal :album="album" />
                        </div>
                    </div>
                </div>
                <div class="mt-2" v-if="albums?.links.length > 3">
                    <Paginator :links="albums?.links" :params="params" />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>