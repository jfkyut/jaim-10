<script setup>

import Container from '@/Components/containers/Container.vue';
import SearchForm from '@/Components/forms/SearchForm.vue';
import MusicCard from '@/Components/MusicCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useMusicParamStore } from '@/Stores/musicParams';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import Paginator from '@/Components/Paginator.vue';

const props = defineProps({
    favorites: Array
})

const { params } = storeToRefs(useMusicParamStore());

</script>

<template>

    <Head title="Favorites" />

    <AuthenticatedLayout>
        <template #header>
            Favorites
        </template>

        <Container>
            <template #header>
                <SearchForm :params="params" :url="route(route().current())" />
            </template>
            <template #body>
                <MusicCard 
                    :musics="favorites.data?.map(fav => fav?.music)"
                />
                <div class="pt-2" v-if="favorites.links.length > 3">
                    <Paginator 
                        :params="params"
                        :links="favorites.links"
                    />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>