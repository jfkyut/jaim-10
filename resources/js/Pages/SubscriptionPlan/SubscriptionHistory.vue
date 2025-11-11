<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { DataTable } from 'primevue';

</script>

<template>
    <Head title="Subscription History" />
    <AuthentucatedLayout>
        <template #header>
            Subscription History
        </template>

        <div class="p-4 max-w-7xl mx-auto">
            <h2 class="text-2xl font-semibold mb-8 text-center">Your Subscription History</h2>
            <!-- Subscription history details go here -->
        </div>
    </AuthentucatedLayout>
</template>