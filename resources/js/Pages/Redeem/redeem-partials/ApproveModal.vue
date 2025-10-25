<script setup>

import { Button } from 'primevue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

defineProps({
    redeem: Object
})

const isShowModal = ref(false);
const toast = useToast();
const isLoading = ref(false);

const form = ref(
    useForm({ })
);

const submit = (redeem) => {
    form.value.post(route('redeem.approve', redeem.id), {
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onSuccess: () => {
            toast.success('Redeem request approved successfully!');
            isShowModal.value = false;
        },
        onError: (errors) => {
            toast.error('Failed to approve redeem request.');
            console.error(errors);
        },
        preserveScroll: true,
    })
}




</script>

<template>
    <Button 
        :label="'Approve'" 
        @click="isShowModal = true"
        class="p-button-success p-button-text"
    />

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <div class="flex justify-between">
                    <header>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white uppercase">
                            Approve Redeem Request
                        </h3>
                    </header>

                    <div class="flex gap-2 items-center">
                        <Button 
                            label="Cancel" 
                            @click="isShowModal = false" 
                            class="p-button-text"
                        />
                        <Button 
                            :loading="isLoading"
                            @click="submit(redeem)"
                            label="Approve" 
                        />
                    </div>
                        
                </div>
            </template>
            <template #body>
                Are you sure you want to approve this redeem request? This action cannot be undone.
                <div class="mt-4">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">
                        User: {{ redeem.user.first_name }} {{ redeem.user.last_name }}<br>
                        Credits: {{ redeem.credits }}<br>
                        Amount: ₱{{ redeem.amount }}<br>
                        Claim Through: {{ redeem.claim_through || 'N/A' }}<br>
                        Claim Number: {{ redeem.claim_number || 'N/A' }}<br>
                    </p>
                </div>
            </template>
        </Container>
    </Modal>
</template>