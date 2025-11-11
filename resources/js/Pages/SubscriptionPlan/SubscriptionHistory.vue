<script setup>
import Container from '@/Components/containers/Container.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { DataTable, Column, Tag } from 'primevue';
import { computed } from 'vue';
import Paginator from '@/Components/Paginator.vue';

defineProps({
    subscriptions: Object
})

// Format date helper
const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};
</script>

<template>
    <Head title="Subscription History" />
    <AuthenticatedLayout>
        <template #header>
            Subscription History
        </template>

        <Container>
            <template #header>
            
            </template>
            <template #body>
                <DataTable
                    :value="subscriptions.data"
                    scrollable
                    scrollHeight="70vh"
                    :rows="5"
                    stripedRows
                    removableSort
                    resizable-columns
                    show-gridlines
                >
                    <Column field="id" header="ID" sortable />
                    <Column header="Plan">
                        <template #body="slotProps">
                            {{ slotProps.data.plan.name }}
                        </template>
                    </Column>
                    <Column field="status" header="Status" sortable>
                        <template #body="slotProps">
                            <Tag :value="slotProps.data.status" :severity="slotProps.data.status === 'active' ? 'success' : 'danger'" />
                        </template>
                    </Column>
                    <Column field="starts_at" header="Started At" sortable>
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.starts_at) }}
                        </template>
                    </Column>
                    <Column field="ends_at" header="Ended At" sortable>
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.ends_at) }}
                        </template>
                    </Column>
                </DataTable>

                <div class="mt-2" v-if="subscriptions?.links?.length > 3">
                    <Paginator :params="{}" :links="subscriptions.links" />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>