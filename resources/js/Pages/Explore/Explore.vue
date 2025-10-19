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
                <div v-if="musics.data.length">
                    <MusicCard :musics="musics.data" />
                </div>
                <div v-if="musics.links.length > 3" class="mt-4">
                    <Paginator :links="musics.links" :params="params" />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>