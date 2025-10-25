<script setup>

import { Button } from 'primevue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    redeem: Object
});

const isShowModal = ref(false);
const isLoading = ref(false);
const toast = useToast();

const form = ref(
    useForm({})
);

const submit = (redeem) => {
    form.value.post(route('redeem.reject', redeem.id), {
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onSuccess: () => {
            toast.success('Redeem request rejected successfully!');
            isShowModal.value = false;
        },
        onError: (errors) => {
            toast.error('Failed to reject redeem request.');
            console.error(errors);
        },
        preserveScroll: true,
    });
};

</script>

<template>
    <Button 
        @click="isShowModal = true"
        :label="'Reject'" 
        class="p-button-danger p-button-text"
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
                            Reject Redeem Request
                        </h3>
                    </header>

                    <div class="flex gap-2 items-center">
                        <Button 
                            severity="danger"
                            label="Cancel" 
                            @click="isShowModal = false" 
                            class="p-button-text"
                        />
                        <Button 
                            severity="danger"
                            :loading="isLoading"
                            @click="submit(redeem)"
                            label="Reject" 
                        />
                    </div>
                        
                </div>
            </template>
            <template #body>
                Are you sure you want to reject this redeem request? This action cannot be undone.
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