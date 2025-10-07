<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Container from '@/Components/containers/Container.vue';
import SearchForm from '@/Components/forms/SearchForm.vue';
import { storeToRefs } from 'pinia';
import { DataTable, Column } from 'primevue';
import { useCreatorFilterStore } from '@/Stores/creatorsFilter';
import Paginator from '@/Components/Paginator.vue';

defineProps({ creators: Object });

const { params } = storeToRefs(useCreatorFilterStore());

</script>

<template>
    <Head title="Creators" />

    <AuthenticatedLayout>
        <template #header>
            Creators
        </template>

        <Container>
            <template #header>
                <SearchForm :params="params" :url="route(route().current())" />
            </template>
            <template #body>
                <DataTable
                    striped-rows
                    scrollable 
                    removable-sort
                    scrollHeight="400px"
                    show-gridlines
                    :value="creators.data"
                >
                    <Column header="Username" sortable reorderable-column>
                        <template #body="slotProps">
                            {{ slotProps.data?.first_name }} {{ slotProps.data?.last_name }}
                        </template>
                    </Column>
                    <Column 
                        header="Tracks" 
                        field="tracks_count"
                        sortable 
                        reorderable-column
                    />
                    <Column 
                        header="Playlists" 
                        field="playlists_count"
                        sortable 
                        reorderable-column
                    />
                    <Column 
                        header="Albums"
                        field="albums_count"
                        sortable
                        reorderable-column
                    />
                    <Column 
                        header="Followers"
                        field="followers_count"
                        sortable
                        reorderable-column
                    />
                    <Column 
                        header="Following"
                        field="following_count"
                        sortable
                        reorderable-column
                    />
                </DataTable>
                
                <div class="mt-4" v-if="creators?.links?.length > 3">
                    <Paginator :links="creators.links" :params="params" />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>