<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Container from '@/Components/containers/Container.vue';
import SearchForm from '@/Components/forms/SearchForm.vue';
import UserCard from '@/Components/UserCard.vue';
import { storeToRefs } from 'pinia';
import { useCreatorFilterStore } from '@/Stores/creatorsFilter';

const { params } = storeToRefs(useCreatorFilterStore());

// You'll need to receive people data from your backend
defineProps({
    people: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <Head title="People" />

    <AuthenticatedLayout>
        <template #header>
        </template>

        <Container>
            <template #header>
                <SearchForm :params="params" :url="route(route().current())" />
            </template>
            <template #body>
                <div v-if="people.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <UserCard v-for="user in people.data" :key="user.id" :user="user" />
                </div>
                <div v-else class="text-center text-zinc-500 dark:text-zinc-400">
                    No people found
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>