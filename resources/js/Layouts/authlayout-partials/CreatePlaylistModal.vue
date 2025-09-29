<script setup>

import NavButton from '@/Components/NavButton.vue';
import Modal from '@/Components/Modal.vue';
import { useAuthLayoutStore } from '@/Stores/authlayout';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';
import Container from '@/Components/containers/Container.vue';
import { Button, InputText } from 'primevue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const { sidebarOpen } = storeToRefs(useAuthLayoutStore());

const isShowModal = ref(false);
const submitButtonRef = ref(null);
const toast = useToast();

const form = ref(useForm({
    name: ''
}));

const submit = () => {
    form.value.post(route('playlist.store'), {
        onSuccess: () => {
            isShowModal.value = false;
            form.value.reset();

            toast.success("Playlist created successfully!")
        },
        onError: () => {
            toast.error("Failed to create playlist. Please try again.")
        },
        preserveScroll: true
    });
};

</script>

<template>
    <NavButton @click="isShowModal = true" type="button" class="mb-2 flex items-center">
        <i class="ri-add-line"></i>
        <span v-if="sidebarOpen" class="ml-2">Create Playlist</span>
    </NavButton>

    <Modal
        :show="isShowModal"
        @close="isShowModal = false"
    >
        <Container>
            <template #header>
                <div class="flex justify-between items-center">
                    <header>
                        <h3 class="text-lg font-medium uppercase text-zinc-900 dark:text-zinc-100">
                            Create New Playlist
                        </h3>
                    </header>
                    <div class="flex gap-2 items-center">
                        <Button @click="submitButtonRef.click()">
                            <i class="ri-save-line"></i>
                            <span class="ms-2">Save</span>
                        </Button>
                    </div>
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label for="">Playlist Name</label>
                            <InputText 
                                required
                                v-model="form.name"
                                class="w-full" 
                                placeholder="Playlist Name"
                            />
                        </div>
                    </div>
                    <button class="sr-only" ref="submitButtonRef"></button>
                </form>
            </template>
        </Container>        
    </Modal>
</template>