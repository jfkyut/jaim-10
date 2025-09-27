<script setup>

import NavButton from '@/Components/NavButton.vue';
import Modal from '@/Components/Modal.vue';
import Container from '@/Components/containers/Container.vue';
import { ref } from 'vue';
import { Button, InputText, Textarea } from 'primevue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const isShowModal = ref(false);

const form = ref(useForm({
    name: '',
    description: '',
    photo: null
}));

const submitButtonRef = ref(null);
const toast = useToast();

const setPhoto = (event) => {
    form.value.photo = event.target.files[0];
};

const submit = () => {
    form.value.post(route('album.store'), {
        onSuccess: () => {
            isShowModal.value = false;
            form.value.reset();

            toast.success("Album created successfully!")
        },
        onError: () => {
            toast.error("Failed to create album. Please try again.")
        },
        preserveScroll: true
    });
};

</script>

<template>
    <NavButton @click="isShowModal = true">
        <i class="ri-add-line"></i>
        <span class="ms-2">Create Album</span>
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
                            Create New Album
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
                            <label for="">Album Name</label>
                            <InputText 
                                v-model="form.name"
                                class="w-full" 
                                placeholder="Album Name"
                            />
                        </div>
                        <div class="space-y-2">
                            <label for="">Description</label>
                            <Textarea 
                                v-model="form.description"
                                class="w-full" 
                                placeholder="Description"
                                rows="3"
                                auto-resize
                            />
                        </div>
                        <div class="space-y-2">
                            <label for="">Album Cover (optional)</label>
                            <InputText 
                                @change="setPhoto"
                                type="file" 
                                class="block w-full" 
                                accept="image/*" 
                            />
                        </div>
                    </div>
                    <button ref="submitButtonRef" type="submit" class="sr-only"></button>
                </form>
            </template>

            <template #footer>
                <NavButton type="button" class="mr-2" @click="isShowModal = false">
                    Cancel
                </NavButton>
                <NavButton type="submit" class="bg-blue-600 hover:bg-blue-700 text-white">
                    Create Album
                </NavButton>
            </template>
        </Container>
    </Modal>
</template>