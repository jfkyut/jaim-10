<script setup>
import Container from '@/Components/containers/Container.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { useAudioStore } from '@/Stores/audio'
import { storeToRefs } from 'pinia'
import MusicCard from '@/Components/MusicCard.vue'
import SearchForm from '@/Components/forms/SearchForm.vue'
import Paginator from '@/Components/Paginator.vue'
import { useMusicParamStore } from '@/Stores/musicParams'

const props = defineProps({ 
    musics: Object 
})

const { params } = storeToRefs(useMusicParamStore())

</script>

<template>
    <Head title="Explore Music" />

    <AuthenticatedLayout>
        <template #header>
            Explore Music
        </template>

        <Container>
            <template #header>
                <SearchForm 
                    :url="route(route().current())" 
                    :params="params"
                />
            </template>
            <template #body>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 p-2">
                    <MusicCard :musics="musics.data" />
                </div>
                <div v-if="musics.links.length > 3" class="mt-4">
                    <Paginator :links="musics.links" :params="params" />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>