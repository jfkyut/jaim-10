<script setup>

import Container from '@/Components/containers/Container.vue';
import SearchForm from '@/Components/forms/SearchForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useMusicParamStore } from '@/Stores/musicParams';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { DataTable, Column, Tag, Button } from 'primevue';
import Paginator from '@/Components/Paginator.vue';
import ApproveModal from './redeem-partials/ApproveModal.vue';
import RejectModal from './redeem-partials/RejectModal.vue';

defineProps({ 
    redeems: Object,
    pageTitle: String
});

const { params } = storeToRefs(useMusicParamStore());

</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <template #header>
            {{ pageTitle }}
        </template>

        <Container>
            <template #header>
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white uppercase">
                   <SearchForm 
                        :params="params"
                        :url="route(route().current())"
                   />
                </h3>
            </template>
            <template #body>
                <DataTable
                    :value="redeems.data"
                    scrollable
                    scrollHeight="70vh"
                    :rows="5"
                    stripedRows
                    removableSort
                    resizable-columns
                    show-gridlines
                >
                    <Column field="id" header="ID" sortable />
                    <Column header="User">
                        <template #body="slotProps">
                            {{ slotProps.data.user.first_name }} {{ slotProps.data.user.last_name }}
                        </template>
                    </Column>
                    <Column field="credits" header="Credits" sortable />
                    <Column field="amount" header="Amount (PHP)" sortable />
                    <Column field="claim_through" header="Claim Through" sortable>
                        <template #body="slotProps">
                            {{ slotProps.data.claim_through || 'N/A' }}
                        </template>
                    </Column>
                    <Column field="claim_number" header="Claim Number" sortable>
                        <template #body="slotProps">
                            {{ slotProps.data.claim_number || 'N/A' }}
                        </template>
                    </Column>
                    <Column header="Status" sortable>
                        <template #body="slotProps">
                            <Tag 
                                :value="slotProps.data.status.name" 
                                :severity="
                                    slotProps.data.status.name === 'pending' ? 'info' :
                                    slotProps.data.status.name === 'approved' ? 'success' :
                                    slotProps.data.status.name === 'rejected' ? 'danger' : 'secondary'
                                " 
                            />
                        </template>
                    </Column>
                    <Column field="created_at" header="Created At" sortable />
                    <Column field="updated_at" header="Updated At" sortable />
                    <Column v-if="route().current().includes('pending')" frozen align-frozen="right" header="Actions">
                        <template #body="slotProps">
                            <div class="flex flex-col gap-2">
                                <!-- <Button 
                                    :label="'View Details'" 
                                    @click="$emit('viewDetails', slotProps.data.id)" 
                                    class="p-button-text"
                                /> -->
                                <ApproveModal :redeem="slotProps.data" />
                                <RejectModal :redeem="slotProps.data" />
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <div class="mt-2" v-if="redeems?.links?.length > 3">
                    <Paginator 
                        :params="params"
                        :links="redeems.links"
                    />
                </div>
            </template>
        </Container>
    </AuthenticatedLayout>
</template>